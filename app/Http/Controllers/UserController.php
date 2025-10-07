<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store', 'updateStatus']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['delete']]);
    }



    public function index()
    {
        $users = User::select(
            'users.id',
            'users.name as username',
            'users.mobile_number',
            'users.email',
            'roles.name',
            'users.status',
        )
            ->where('users.id', '!=', '1')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->paginate(config('app.per_page'));

        return view('users.index', ['users' => $users]);
    }


    public function create()
    {
        $roles = Role::get();

        return view('users.add', ['roles' => $roles]);
    }


    public function store(Request $request)
    {
        // $request->validate([
        //     'email' => 'unique:users,email',
        // ]);

        DB::beginTransaction();
        try {

            // Store Data
            $user = User::create([
                'name'    => $request->name,
                'email'   => $request->email,
                'address' => $request->address,
                'password' => Hash::make($request->password),
                'mobile_number' => $request->mobile_number,
                'role_id' => $request->role_id ?? 3,
                'status'  => $request->status,
            ]);
            // dd($user);
            // Delete Any Existing Role
            DB::table('model_has_roles')->where('model_id', $user->id)->delete();

            // Assign Role To User
            $user->assignRole($user->role_id);

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('users.index')->with('success', 'User Created Successfully.');
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }


    public function updateStatus($user_id, $status)
    {
        // Validation
        $validate = Validator::make([
            'user_id'   => $user_id,
            'status'    => $status
        ], [
            'user_id'   =>  'required|exists:users,id',
            'status'    =>  'required|in:0,1',
        ]);

        // If Validations Fails
        if ($validate->fails()) {
            return redirect()->route('users.index')->with('error', $validate->errors()->first());
        }

        try {
            DB::beginTransaction();

            // Update Status
            User::whereId($user_id)->update(['status' => $status]);

            // Commit And Redirect on index with Success Message
            DB::commit();
            return redirect()->route('users.index')->with('success', 'User Status Updated Successfully!');
        } catch (\Throwable $th) {

            // Rollback & Return Error Message
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }


    public function edit(Request $request, $id)
    {
        $user = User::where('id', $id)->first();

        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found or inactive.');
        }

        $roles = Role::where('id', '!=', '1')->get();


        return view('users.edit')->with([
            'roles' => $roles,
            'user'  => $user,
        ]);
    }


    public function update(Request $request, User $user)
    {
        // Validations
        $request->validate([
            'email' => 'required|unique:users,email,' . $user->id . ',id',
        ]);

        DB::beginTransaction();
        try {

            $ireportTo = 0;
            if ($request->role_id == 2) {
                $ireportTo  =  0;
            } else {
                $ireportTo = $request->ireportTo;
            }
            $user_updated = User::whereId($user->id)->update([
                'name'    => $request->name,
                'email'         => $request->email,
                'address'     => $request->address,
                'mobile_number' => $request->mobile_number,
                'role_id'       => $request->role_id,
                'status'        => $request->status,

            ]);
            //dd($user_updated);

            // Delete Any Existing Role
            DB::table('model_has_roles')->where('model_id', $user->id)->delete();

            // Assign Role To User
            $user->assignRole($user->role_id);

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('users.index')->with('success', 'User Updated Successfully.');
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }


    public function delete(Request $request, User $user)
    {
        // dd($request);
        // dd($user);
        DB::beginTransaction();
        try {
            // Delete User
            User::whereId($request->id)->delete();

            DB::commit();
            return redirect()->route('users.index')->with('success', 'User Deleted Successfully!.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }


    public function importUsers()
    {
        return view('users.import');
    }

    public function uploadUsers(Request $request)
    {
        Excel::import(new UsersImport, $request->file);

        return redirect()->route('users.index')->with('success', 'User Imported Successfully');
    }



    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }


    public function passwordupdate(Request $request)
    {
        // dd($user);
        //dd($request);
        $newpassword = ($request->newpassword);
        $confirmpassword = ($request->confirmpassword);

        if ($newpassword == $confirmpassword) {
            // dd('if');
            $Password = DB::table('users')
                ->where(['id' => $request->id])
                ->update([
                    'password' => Hash::make($request->confirmpassword),
                ]);
            // dd($Password);
            return redirect()->route('users.index')->with('success', 'User Password Updated Successfully.');
        } else {
            // dd('eles');
            return redirect()->route('users.index')->with('error', 'password and confirm password does not match');
        }
    }
}
