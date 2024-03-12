<?php

namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Customer;
use App\Shipment; 


class CustomerController extends Controller
{
    // Add a constructor to apply the 'auth' middleware
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {

        return view('Customer.index');
    }

    public function customerList()
    {
        // You don't need to check Auth::user() here because the 'auth' middleware already handles it

        $customers = DB::table('customers')
            ->select('customers.*')
            ->orderBy('customers.created_at', 'asc')
            ->get();

        return $customers;
    }



    public function store()
    {
        // You don't need to check Auth::user() here because the 'auth' middleware already handles it

        $data = request()->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:customers,email',
            'phoneNo' => 'required|numeric',
            'password' => 'required|min:8|confirmed',
            'address' => 'required',
            'state' => 'required',
            'city' => 'required',
            'postcode' => 'required|numeric',
            'status' => 'required'
        ]);

        // Trim leading values from the 'phoneNo' input
        $phoneNo = ltrim($data['phoneNo'], '0');
        $phoneNo = ltrim($data['phoneNo'], '60');
        $phoneNo = ltrim($data['phoneNo'], '+60');
        $phoneNo = ltrim($data['phoneNo'], '+');

        DB::table('customers')
            ->insert([
                'id' => Str::random(20),
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'phone_no' => $phoneNo,
                'address' => $data['address'],
                'state' => $data['state'],
                'city' => $data['city'],
                'postcode' => $data['postcode'],
                'status' => $data['status'],
                'user_image' => 'unknown_pic.webp',
                'created_at' => now(),
                'updated_at' => now()
            ]);

        return redirect('/customers')->with('success', 'Customer created successfully!');
    }
    

    public function show(Request $request)
    {
        $customerID = $request->segment(2);

        $customer = DB::table('customers')
            ->select('customers.*')
            ->where('customers.id', $customerID)
            ->first();

        // Retrieve the shipments for the customer, including the associated driver's surname
        $customerShipments = DB::table('shipments')
            ->join('drivers', 'shipments.driver', '=', 'drivers.id')
            ->select('shipments.*', 'drivers.surname as driver_surname')
            ->where('shipments.customer', $customerID)
            ->orderBy('shipments.created_at', 'desc') // Order by date in descending order (most recent first)
            ->get();

                // Retrieve the shipments for the customer, including the associated driver's surname
        $totalCustomerShipments = DB::table('shipments')
        ->join('drivers', 'shipments.driver', '=', 'drivers.id')
        ->select('shipments.*', 'drivers.surname as driver_surname')
        ->where('shipments.customer', $customerID)
        ->count();

        return view('Customer.show', compact('customer', 'customerShipments','totalCustomerShipments'));
    }

    public function edit(Request $request)
    {
        $customerID = $request->segment(2);

        $customer = DB::table('customers')
            ->select('customers.*')
            ->where('customers.id', $customerID)
            ->first();

        return view('Customer.edit', compact('customer'));
    }


    public function update($customer)
    {
        $data = request()->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phoneNo' => 'required|numeric',
            'password' => 'nullable|min:8|confirmed',
            'address' => 'required',
            'state' => 'required',
            'city' => 'required',
            'postcode' => 'required|numeric'
        ]);

        // Trim the first digit 0 from the phoneNo
        $data['phoneNo'] = ltrim($data['phoneNo'], '0');
        $data['phoneNo'] = ltrim($data['phoneNo'], '60');
        $data['phoneNo'] = ltrim($data['phoneNo'], '+60');
        $data['phoneNo'] = ltrim($data['phoneNo'], '+');

        if ($data['password'] != "") {
            DB::table('customers')
                ->where('customers.id', $customer)
                ->update([
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email'],
                    'phone_no' => $data['phoneNo'],
                    'password' => Hash::make($data['password']),
                    'address' => $data['address'],
                    'state' => $data['state'],
                    'city' => $data['city'],
                    'postcode' => $data['postcode'],
                    'updated_at' => now()
                ]);
        } else {
            DB::table('customers')
                ->where('customers.id', $customer)
                ->update([
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email'],
                    'phone_no' => $data['phoneNo'],
                    'address' => $data['address'],
                    'state' => $data['state'],
                    'city' => $data['city'],
                    'postcode' => $data['postcode'],
                    'updated_at' => now()
                ]);
        }

        // Redirect to the customer's profile page with the updated data
        return redirect("/customers/{$customer}")->with('success', 'Customer updated successfully!');
    }

    public function updateStatus($id, $status_code)
    {
        $updateStatus = DB::table('customers')
            ->where('customers.id', $id)
            ->update([
                'status' => $status_code,
                'updated_at' => now()
            ]);

        if ($updateStatus) {
            return redirect()->route('customer.show', ['customer' => $id])->with('success', 'Customer status updated successfully!');
        } else {
            return redirect('/customers')->with('error', 'Failed to update customer status!');
        }
    }

    public function checkShipment($id){
        $customer = DB::table('customers')
            ->join('shipments', 'shipments.customer', '=', 'customers.id')
            ->where('shipments.id', $id)
            ->select('customers.first_name', 'customers.last_name')
            ->first();
    
        $item_code = DB::table('shipments')
        ->where('id', $id)
        ->pluck('item_code')
        ->first();
    
        $amount = DB::table('shipments')
        ->where('id', $id)
        ->pluck('amount')
        ->first();
    
        $shipmentDetail = DB::table('shipments')
        ->join('drivers', 'drivers.id', '=', 'shipments.driver')
        ->join('customers', 'customers.id', '=', 'shipments.customer')
        ->join('vehicles', 'vehicles.id', '=', 'shipments.truck_plate_no')
        ->where('shipments.id', $id)
        ->select('shipments.*', 'drivers.name', 'customers.last_name', 'vehicles.plate_no', 'vehicles.imei')
        ->get();
    
        $form = DB::table('shipments')
        ->join('drivers', 'drivers.id', '=', 'shipments.driver')
        ->join('vehicles', 'vehicles.id', '=', 'shipments.truck_plate_no')
        ->where('shipments.id', $id)
        ->select('shipments.*', 'drivers.name', 'vehicles.plate_no')
        ->get();
    
        $allVehicle = DB::table('vehicles')
        ->select('*')
        ->get();
    
        $drivers = DB::table('drivers')
        ->select('*')
        ->get();
    
        $customers = DB::table('customers')
        ->select('*')
        ->get();
    
        return view('Customer.checkShipment', compact('customer', 'item_code', 'amount', 'shipmentDetail', 'form', 'drivers', 'customers', 'allVehicle'));
    }
    

}
