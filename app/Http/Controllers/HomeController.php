<?php

namespace App\Http\Controllers;

use App\Models\User;
// use App\Models\Category;
// use App\Models\Dealer;
// use App\Models\Product;
// use App\Models\Inquiry;
// use App\Models\PhotoGallery;
use Illuminate\Http\Request;
use App\Models\PatientAppointment;
use App\Models\Labwork;
use App\Models\Patient;
use App\Models\Notes;
use App\Models\MaintenanceRegister;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
// use App\Models\VideoGallery;
// use App\Models\ProductInquiry;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $todayAppointmentsCount = PatientAppointment::where('is_disrupted', 0) // Ignore disrupted entries
            ->where(function ($query) {
                $query->whereNull('rescheduled_date')
                    ->where('appointment_date', today())
                    ->orWhere('rescheduled_date', today());
            })
            ->count();

        // Count Labworks that are pending collection
        $pendingCollectedCount = Labwork::whereNull('collection_date')->count();

        // Count Labworks that are collected but pending received
        $pendingReceivedCount = Labwork::whereNotNull('collection_date')
            ->whereNull('received_date')
            ->count();

        $MarkAsReceivedPending = MaintenanceRegister::where(['repair_received_date' => null, 'received_comment' => null])->count();

        $todayPatients = Patient::with([
            'notes' => function ($q) {
                $q->whereDate('date', today())
                    ->where('is_completed', 0)
                    ->with('treatment');
            },
            'payments' => function ($q) {
                $q->whereDate('payment_date', today())
                    ->where('is_completed', 0);
            }
        ])
            ->where(function ($query) {
                $query->whereHas('notes', function ($q) {
                    $q->whereDate('date', today())
                        ->where('is_completed', 0);
                })
                    ->orWhereHas('payments', function ($q) {
                        $q->whereDate('payment_date', today())
                            ->where('is_completed', 0);
                    });
            })
            ->get()
            ->map(function ($patient) {

                // Total amount of all notes
                $patient->total_amount = $patient->notes()->sum('Net_amount');

                // Today's payment only
                $patient->paid_amount = $patient->payments->sum('amount');

                // Total paid till date
                $totalPaid = $patient->payments()->sum('amount');

                // Overall due
                $patient->due_amount = $patient->total_amount - $totalPaid;

                return $patient;
            });


        //     $completedPatients = Patient::with([
        //     'notes' => function ($query) {
        //         $query->whereDate('date', today())
        //             ->where('is_completed', 1)
        //             ->with('treatment');
        //     },

        //     'payments' => function ($query) {
        //         $query->whereDate('payment_date', today())
        //             ->where('is_completed', 1);
        //     },

        //     'appointments' => function ($query) {
        //         $query->whereDate('created_at', today());
        //     },
        // ])
        // ->where('is_completed', 1)

        // ->where(function ($query) {
        //     $query->whereHas('notes', function ($noteQuery) {
        //         $noteQuery->whereDate('date', today())
        //             ->where('is_completed', 1);
        //     })
        //     ->orWhereHas('payments', function ($paymentQuery) {
        //         $paymentQuery->whereDate('payment_date', today())
        //             ->where('is_completed', 1);
        //     });

        // })

        // ->get()
        // ->map(function ($patient) {
        //     $patient->total_amount = $patient->notes->sum('Net_amount');
        //     $patient->paid_amount = $patient->payments->sum('amount');

        //     $patient->due_amount = max(
        //         0,
        //         $patient->total_amount - $patient->paid_amount
        //     );

        //     return $patient;
        // });

        $searchDate = $request->date;
        $perPage = 15;
        if ($searchDate) {

            // Search ki hui koi bhi date
            $fromDate = Carbon::parse($searchDate)->startOfDay();
            $toDate   = Carbon::parse($searchDate)->endOfDay();

            $isSearch = true;
        } else {

            // Default: only today
            $fromDate = Carbon::today()->startOfDay();
            $toDate   = Carbon::today()->endOfDay();

            $isSearch = false;
        }

        $completedPatients = Patient::with([

            'notes' => function ($q) use ($fromDate, $toDate, $isSearch) {

                $q->whereBetween('date', [$fromDate, $toDate]);

                // Default page par hi completed check karna
                if (!$isSearch) {
                    $q->where('is_completed', 1);
                }

                $q->with('treatment');
            },

            'payments' => function ($q) use ($fromDate, $toDate, $isSearch) {

                $q->whereBetween('payment_date', [$fromDate, $toDate]);

                if (!$isSearch) {
                    $q->where('is_completed', 1);
                }
            },

            'appointments'
        ])

            // ->where('is_completed', 1)

            ->where(function ($query) use ($fromDate, $toDate, $isSearch) {

                $query->whereHas('notes', function ($q) use (
                    $fromDate,
                    $toDate,
                    $isSearch
                ) {

                    $q->whereBetween('date', [$fromDate, $toDate]);

                    if (!$isSearch) {
                        $q->where('is_completed', 1);
                    }
                })

                    ->orWhereHas('payments', function ($q) use (
                        $fromDate,
                        $toDate,
                        $isSearch
                    ) {

                        $q->whereBetween('payment_date', [$fromDate, $toDate]);

                        if (!$isSearch) {
                            $q->where('is_completed', 1);
                        }
                    });
            })

            ->get()

            ->map(function ($patient) {

                $patient->total_amount = $patient->notes->sum('Net_amount');

                $patient->paid_amount = $patient->payments->sum('amount');

                $patient->due_amount = max(
                    0,
                    $patient->total_amount - $patient->paid_amount
                );

                // Latest activity
                $noteDate = $patient->notes->max('date');

                $paymentDate = $patient->payments->max('payment_date');

                $patient->latest_activity_date = collect([
                    $noteDate,
                    $paymentDate
                ])->filter()->max();

                return $patient;
            })

            ->sortByDesc('latest_activity_date')
            ->values();
        // Pagination
        $currentPage = request()->get('page', 1);

        $completedPatients = new LengthAwarePaginator(
            $completedPatients->forPage($currentPage, $perPage),
            $completedPatients->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );


        $todayAppointments = PatientAppointment::with(['patient', 'doctor'])
            ->where('is_disrupted', 0)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereNull('rescheduled_date')
                        ->whereDate('appointment_date', today());
                })->orWhereDate('rescheduled_date', today());
            })
            ->orderBy('appointment_time', 'ASC')
            ->get();

        $todayDuePatients = Notes::with('patient')
            ->selectRaw('patient_id, SUM(Net_amount) as total_due')
            ->whereDate('date', today())
            ->groupBy('patient_id')
            ->get();
        return view('home', compact('todayPatients', 'todayAppointments', 'todayDuePatients', 'todayAppointmentsCount', 'pendingCollectedCount', 'pendingReceivedCount', 'MarkAsReceivedPending', 'completedPatients', 'searchDate'));
    }


    public function getProfile()
    {
        $session = Auth::user()->id;
        // dd($session);
        $users = User::where('users.id', $session)
            ->first();
        // dd($users);

        return view('profile', compact('users'));
    }

    public function EditProfile()
    {
        $roles = Role::where('id', '!=', '1')->get();

        return view('Editprofile', compact('roles'));
    }

    public function updateProfile(Request $request)
    {
        $session = auth()->user()->id;
        $user = User::where(['status' => 1, 'id' => $session])->first();

        $request->validate([
            'email' => 'required|unique:users,email,' . $user->id . ',id',
        ]);

        try {
            DB::beginTransaction();

            #Update Profile Data
            User::whereId(auth()->user()->id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'mobile_number' => $request->mobile_number,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            #Commit Transaction
            DB::commit();

            #Return To Profile page with success
            return back()->with('success', 'Profile Updated Successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function changePassword(Request $request)
    {
        $session = Auth::user()->id;

        $user = User::where('id', '=', $session)->where(['status' => 1])->first();

        if (Hash::check($request->current_password, $user->password)) {
            $newpassword = $request->new_password;
            $confirmpassword = $request->new_confirm_password;

            if ($newpassword == $confirmpassword) {
                $Student = DB::table('users')
                    ->where(['status' => 1, 'id' => $session])
                    ->update([
                        'password' => Hash::make($confirmpassword),
                    ]);
                return back()->with('success', 'User Password Updated Successfully.');
            } else {
                return back()->with('error', 'password and confirm password does not match');
            }
        } else {
            return back()->with('error', 'Current Password does not match');
        }
    }
}
