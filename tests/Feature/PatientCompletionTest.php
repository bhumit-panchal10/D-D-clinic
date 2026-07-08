<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientCompletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_can_be_marked_complete(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role_id' => 1,
            'status' => 1,
        ]);

        $patient = Patient::create([
            'name' => 'Test Patient',
            'mobile1' => '9876543210',
            'case_no' => 'C-1001',
        ]);

        $response = $this->actingAs($user)->post(route('patient.complete', $patient));

        $response->assertRedirect();
        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'is_completed' => 1,
        ]);
    }
}
