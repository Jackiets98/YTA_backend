<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class AdminController extends Controller
{
    // Add a constructor to apply the 'auth' middleware
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // You don't need to check Auth::user() here because the 'auth' middleware already handles it

        $admins = DB::table('admins')
            ->select('admins.*')
            ->where('admins.usertype', '1')
            ->orderBy('admins.created_at', 'asc')
            ->get();

        $subAdmins = DB::table('admins')
            ->select('admins.*')
            ->where('admins.usertype', '2')
            ->orderBy('admins.created_at', 'asc')
            ->get();

        return view('admins.index', compact('admins', 'subAdmins'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAdmin()
    {
        // You don't need to check Auth::user() here because the 'auth' middleware already handles it

        $data = request()->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:admins,email',
            'phoneNo' => 'required|numeric',
            'password' => 'required|min:8|confirmed',
            'status' => 'required'
        ]);

        DB::table('admins')
            ->insert([
                'id' => Str::random(20),
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'phone_no' => $data['phoneNo'],
                'usertype' => '1',
                'status' => $data['status'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

        return redirect('/admins')->with('success', 'Admin created successfully!');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeSubAdmin()
    {
        // You don't need to check Auth::user() here because the 'auth' middleware already handles it

        $data = request()->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:admins,email',
            'phoneNo' => 'required|numeric',
            'password' => 'required|min:8|confirmed',
            'status' => 'required'
        ]);

        DB::table('admins')
            ->insert([
                'id' => Str::random(20),
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'phone_no' => $data['phoneNo'],
                'usertype' => '2',
                'status' => $data['status'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

        return redirect('/admins')->with('success', 'Admin created successfully!');
    }
    /**
 * Display the specified resource.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function show(Request $request)
{
    $adminID = $request->segment(2);

    $admin = DB::table('admins')
        ->select('admins.*')
        ->where('admins.id', $adminID)
        ->first();

    return view('admins.show', compact('admin'));
}

/**
 * Show the form for editing the specified resource.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function edit(Request $request)
{
    $adminID = $request->segment(2);

    $admin = DB::table('admins')
        ->select('admins.*')
        ->where('admins.id', $adminID)
        ->first();

    return view('admins.edit', compact('admin'));
}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($admin)
{
    $data = request()->validate([
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email',
        'phoneNo' => 'required|numeric',
        'password' => 'nullable|min:8|confirmed'
    ]);

    if ($data['password'] != "") {
        DB::table('admins')
            ->where('admins.id', $admin)
            ->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone_no' => $data['phoneNo'],
                'password' => Hash::make($data['password']),
                'updated_at' => now()
            ]);
    } else {
        DB::table('admins')
            ->where('admins.id', $admin)
            ->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone_no' => $data['phoneNo'],
                'updated_at' => now()
            ]);
    }

    return redirect('/admins')->with('success', 'Admin updated successfully!');
}
    public function updateStatus($admin, $status)
{
    $updateStatus = DB::table('admins')
        ->where('admins.id', $admin)
        ->update([
            'status' => $status,
            'updated_at' => now()
        ]);

    if ($updateStatus) {
        return redirect('/admins')->with('success', 'Admin status updated successfully!');
    } else {
        return redirect('/admins')->with('error', 'Failed to update admin status!');
    }
}

}
