<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Models\Driver;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use PDF;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{

    // Add a constructor to apply the 'auth' middleware
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $drivers = DB::table('drivers')
        ->select('*')
        ->get();

        return view('Driver.index',compact('drivers'));
    }

    public function addDriver(Request $request){
        $name = $request->input('name');
        $surname = $request->input('surname');
        $ic_no = $request->input('ic_no');
        $license = $request->input('license');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $address = $request->input('address');
        $city = $request->input('city');
        $state = $request->input('state');
        $postcode = $request->input('postcode');
        $password = $request->input('password');

        $phone = preg_replace('/^(\+?60|0)/', '', $phone);

        DB::table('drivers')
        ->insert([
            'id' => Str::random(30),
            'name' => $name,
            'surname' => $surname,
            'identity_card' => $ic_no,
            'license' => $license,
            'address' => $address,
            'email' => $email,
            'phone_num' => $phone,
            'user_image' => 'unknown_pic.webp',
            'city' => $city,
            'password' => Hash::make($password),
            'state' => $state,
            'postcode' => $postcode,
            'status' => 1,
            'created_at' => now()
        ]);

        return redirect()->back();
    }

    public function driverDetail($id) {

        $driverDetail = DB::table('drivers')
        ->where('id',"=", $id)
        ->select('*')
        ->get();

        $totalRating = DB::table('shipments')
        ->where('driver', $id)
        ->where('rating', '!=', 0)
        ->avg('rating');

        $totalShipmentDelivered = DB::table('shipments')
        ->where('driver', $id)
        ->where('delivery_status',"=",2)
        ->count();

        // Format $totalRating to two decimal places
        $formattedRating = number_format($totalRating, 2);

        $form = DB::table('drivers')
        ->where('id',"=", $id)
        ->select('*')
        ->get();

        $name = DB::table('drivers')
        ->where('id',"=", $id)
        ->pluck('name')
        ->first();

        return view('Driver.driver_details', compact('driverDetail','form', 'name','formattedRating','totalShipmentDelivered'));
    }

    public function updateStatus($id,$status_code)
    {
        try {
            $updateDriver = DB::table('drivers')
            ->where('id',"=",$id)
            ->update([
                'status' => $status_code
            ]);

            if($updateDriver){
                return Redirect::back()->with('success','Status Updated Successfully');
            }else{
                return Redirect::back()->with('failure','Status Update Error');
            }

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateDriverDetails(Request $request, $id){
        $name = $request->input('name');
        $surname = $request->input('surname');
        $ic_no = $request->input('ic_no');
        $license = $request->input('license');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $address = $request->input('address');
        $city = $request->input('city');
        $state = $request->input('state');
        $postcode = $request->input('postcode');

        $phone = preg_replace('/^(\+?60|0)/', '', $phone);

        DB::table('drivers')
        ->where('id',"=",$id)
        ->update([
            'name' => $name,
            'surname' => $surname,
            'identity_card' => $ic_no,
            'license' => $license,
            'address' => $address,
            'email' => $email,
            'phone_num' => $phone,
            'city' => $city,
            'state' => $state,
            'postcode' => $postcode,
            'updated_at' => now()
        ]);

        return redirect()->back();
    }

}
