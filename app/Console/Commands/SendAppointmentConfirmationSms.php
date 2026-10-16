<?php

namespace App\Console\Commands;

use App\Models\Patient;
use App\Models\PatientAppointment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendAppointmentConfirmationSms extends Command
{
    protected $signature = 'appointments:send-confirmation-sms';

    protected $description = 'Send pending appointment confirmation SMS';

    public function handle(): int
    {
        $config = config('services.onlysms');

        foreach (
            [
                'url',
                'user_id',
                'password',
                'sender_id',
                'template_id',
                'clinic_phone',
            ] as $key
        ) {
            if (empty($config[$key])) {
                $this->error("Missing SMS configuration: {$key}");

                return self::FAILURE;
            }
        }

        $lock = Cache::lock('appointment-confirmation-sms', 3600);

        if (! $lock->get()) {
            $this->info('SMS command is already running.');

            return self::SUCCESS;
        }

        try {
            $sent = 0;
            $failed = 0;

            PatientAppointment::query()
                ->whereNotNull('confirmation_sms_due_at')
                ->where('confirmation_sms_due_at', '<=', now())
                ->whereNull('confirmation_sms_sent_at')
                ->chunkById(100, function ($appointments) use (
                    $config,
                    &$sent,
                    &$failed
                ) {
                    foreach ($appointments as $appointment) {
                        try {
                            $patient = Patient::find($appointment->patient_id);

                            $mobile = trim((string) $appointment->mobile_no);

                            if (
                                ! $patient ||
                                ! preg_match('/^[6-9][0-9]{9}$/', $mobile)
                            ) {
                                $failed++;

                                Log::warning('Appointment SMS: invalid patient or mobile', [
                                    'appointment_id' => $appointment->id,
                                ]);

                                continue;
                            }

                            $name = trim(implode(' ', array_filter([
                                $patient->name,
                                $patient->middle_name,
                                $patient->last_name,
                            ])));

                            $date = Carbon::parse($appointment->appointment_date)
                                ->format('d/m/Y');

                            $time = Carbon::parse($appointment->appointment_time)
                                ->format('H:i');

                            $message = "Hi {$name}, your appointment is confirmed at "
                                . "D&D Dental Clinic & Implant Center on {$date}, "
                                . "at {$time}. For more info call {$config['clinic_phone']}";

                            $payload = [
                                'UserID' => $config['user_id'],
                                'UserPass' => $config['password'],
                                'MobileNo' => $mobile,
                                'GSMID' => $config['sender_id'],
                                'TEMPID' => $config['template_id'],
                                'UNICODE' => 'TEXT',
                                'Message' => $message,
                            ];

                            // Optional: the provider can use the account's PEID.
                            if (! empty($config['peid'])) {
                                $payload['PEID'] = $config['peid'];
                            }

                            $response = Http::asForm()
                                ->connectTimeout(10)
                                ->timeout(30)
                                ->post($config['url'], $payload);

                            $body = trim($response->body());

                            // Matches your successful Postman response: 100=Api_...
                            if (
                                ! $response->successful() ||
                                ! preg_match('/^100=.+$/s', $body)
                            ) {
                                $failed++;

                                Log::warning('Appointment SMS rejected or unconfirmed', [
                                    'appointment_id' => $appointment->id,
                                    'http_status' => $response->status(),
                                ]);

                                continue;
                            }

                            // This records provider acceptance, not delivery.
                            $appointment->confirmation_sms_sent_at = now();
                            $appointment->save();

                            $sent++;

                            $this->info(
                                "SMS submitted for appointment {$appointment->id}"
                            );
                        } catch (\Throwable $exception) {
                            $failed++;

                            // Do not log credentials or the full request.
                            Log::error('Appointment SMS processing failed', [
                                'appointment_id' => $appointment->id,
                                'exception_type' => get_class($exception),
                            ]);
                        }
                    }
                });

            $this->info("Submitted: {$sent}. Failed/unconfirmed: {$failed}.");

            return $failed > 0 ? self::FAILURE : self::SUCCESS;
        } finally {
            $lock->release();
        }
    }
}
