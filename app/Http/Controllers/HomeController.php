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
use App\Models\MaintenanceRegister;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
// use App\Models\VideoGallery;
// use App\Models\ProductInquiry;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
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

        return view('home', compact('todayAppointmentsCount', 'pendingCollectedCount', 'pendingReceivedCount', 'MarkAsReceivedPending'));
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
