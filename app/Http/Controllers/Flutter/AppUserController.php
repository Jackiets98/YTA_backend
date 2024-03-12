<?php

namespace App\Http\Controllers\Flutter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use PDF;
use GuzzleHttp\Client;
use Carbon\Carbon;

class AppUserController extends Controller
{
    public function login(Request $request){
        $phone = $request->input('phone_number');
        $trimmed_phone = ltrim($phone, '0');
        $device_id = $request->input('deviceID');
        $hashedPassword = $request->input('password');
        $device_token = $request->input('device_token');

        DB::table('customers')
        ->where('phone_no', '=', $trimmed_phone)
        ->update([
            'device_id' => $device_id,
            'updated_at' => now(),
            'device_token'=>$device_token
        ]);

        $user = DB::table('customers')
            ->where('phone_no', '=', $trimmed_phone)
            ->first();


        if ($user && Hash::check($hashedPassword, $user->password) && $user->status == "1") {
            return response()->json([
                'success' => true, 
                'user_id' => $user->id,
                'user_name' => $user->first_name,
                'user_device' => $user->device_id
            ]);
        } elseif($user->status == "0"){
            return response()->json([
                'success' => 'disabled'
            ]);
        }else {
            return response()->json(['success' => false], 200);
        }
    }

    public function profilePage(Request $request, $id){
        $user = DB::table('customers')
        ->where('id',"=",$id)
        ->first();

        return response()->json([
            'success' => true,
            'user_name' => $user->first_name,
            'user_surname' => $user->last_name,
            'user_email' => $user->email,
            'user_phone' => $user->phone_no,
            'user_address' => $user->address,
            'user_city' => $user->city,
            'user_postcode' => $user->postcode,
            'user_state' => $user->state,
            'user_image' => $user->user_image,
            'user_device' => $user->device_id
        ]);
    }

    public function updateProfile(Request $request, $id) {
        $phone = $request->input('phone_number');
        $trimmed_phone = ltrim($phone, '0');
        $name = $request->input('name');
        $surname = $request->input('surname');
        $address = $request->input('address');
        $email = $request->input('email');
        $city = $request->input('city');
        $state = $request->input('state');
        $postcode = $request->input('postcode');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $id.'.'.$image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);

            DB::table('customers')
            ->where('id',"=",$id)
            ->update([
                'user_image' => $imageName,
                'phone_no' => $trimmed_phone,
                'first_name' => $name,
                'last_name' => $surname,
                'address' => $address,
                'email' => $email,
                'city' => $city,
                'state' => $state,
                'postcode' => $postcode,
                'updated_at' => now()
            ]);
        }else{
            DB::table('customers')
            ->where('id',"=",$id)
            ->update([
                'phone_no' => $trimmed_phone,
                'first_name' => $name,
                'last_name' => $surname,
                'address' => $address,
                'email' => $email,
                'city' => $city,
                'state' => $state,
                'postcode' => $postcode,
                'updated_at' => now()
            ]);
        }

        return response()->json(['success' => true, 'user_id' => $id]);
    }

    public function getPassword(Request $request, $id){
        $user = DB::table('customers')
        ->where('id',"=",$id)
        ->first();
    
        return response()->json([
            'success' => true,
            'password' => $user->password,
        ]);
    }

    public function updatePassword($id, Request $request)
    {
        $password = $request->input('password');
        $hashedPassword = Hash::make($password);

        DB::table('customers')
        ->where('id',"=", $id)
        ->update([
            'password' => $hashedPassword,
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function getShipmentList($id) {

        // delivery_status == '0' To Be Delivered    
        // delivery_status == '1' Delivering
        // delivery_status == '2' Delivered
        // delivery_status == '3' On Hold 
        // delivery_status == '4' Cancelled

        $shipment = DB::table('shipments')
        ->join('drivers','drivers.id',"=",'shipments.driver')
        ->join('customers','customers.id',"=",'shipments.customer')
        ->join('vehicles','vehicles.id',"=",'shipments.truck_plate_no')
        ->where('shipments.customer',"=",$id)
        ->select('shipments.*','drivers.name','customers.last_name','customers.address as c_address','drivers.address as d_address','customers.phone_no','drivers.phone_num','customers.device_id','vehicles.imei')
        ->orderBy('created_at','desc')
        ->get();

        return response()->json([
            'success' => true,
            'shipments' => $shipment
        ]);
    }

    public function updateStatusForDelivery($id, Request $request){
        $status = $request->input('status');

        if($status == '1'){
            DB::table('shipments')
            ->where('id',"=",$id)
            ->update([
                'delivery_status' => $status,
                'departed_time' => now(),
                'updated_at' => now()
            ]);
        }elseif($status == '2'){
            DB::table('shipments')
            ->where('id',"=",$id)
            ->update([
                'delivery_status' => $status,
                'delivered_time' => now(),
                'updated_at' => now()
            ]);
        }else{
            DB::table('shipments')
            ->where('id',"=",$id)
            ->update([
                'delivery_status' => $status,
                'updated_at' => now()
            ]);
        }
        

        return response()->json([
            'success' => true,
        ]);
    }

    public function getDeviceID($id) {
        $device_id = DB::table('customers')
        ->where('id',"=",$id)
        ->pluck('device_id')
        ->first();

        return response()->json([
            'success' => true,
            'user_device' => $device_id,
        ]);
    }

    public function disableAccount($id, Request $request) {
        $status = $request->input('status');

        DB::table('customers')
        ->where('id',"=",$id)
        ->update([
            'status' => $status
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function termsAndCondition(){
        return view('Customer.terms');
    }

    public function privacyPolicy(){
        return view('Customer.privacyPolicy');
    }

    public function getOrderTimelines($shipmentId)
    {
        $driverTimelines = DB::table('driver_timelines')
            ->where('shipment_id', $shipmentId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'driver_timelines' => $driverTimelines,
        ]);
    }

    public function getOrderDetails($timelineId)
    {
        $driverTimelines = DB::table('driver_timelines')
            ->where('id', $timelineId)
            ->first();

        return response()->json([
            'success' => true,
            'location' => $driverTimelines->location,
            'description' => $driverTimelines->description,
            'media' => $driverTimelines->media
        ]);
    }

    public function requestAccessToken()
    {
        $username = 'yessirgps';
        $timestamp = time();
        $loginKey = '2t!#SzKYS&B$mdJN^cuAxBQ4W9VSg&U6';
        $cacheKey = 'access_token';

        // Check if the access token is cached and not expired
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Calculate the signature
        $signature = md5(md5($loginKey) . $timestamp);

        $client = new Client();
        $authApiUrl = 'https://open.iopgps.com/api/auth';

        $jsonData = [
            'appid' => $username,
            'time' => $timestamp,
            'signature' => $signature,
        ];

        $response = $client->post($authApiUrl, [
            'json' => $jsonData,
        ]);

        $authData = json_decode($response->getBody(), true);
        $accessToken = $authData['accessToken'];

        // Cache the access token with an expiration time (e.g., 1 hour)
        Cache::put($cacheKey, $accessToken, 60);

        return $accessToken;
    }

    public function fetchBackGPSData($accessToken, $imei)
    {
        $client = new Client();
        $apiUrl = 'https://open.iopgps.com/api/device/status';

        // Include the access token and other query parameters in the request
        $query = [
            'accessToken' => $accessToken,
            'imei' => $imei,
        ];

        $response = $client->get($apiUrl, [
            'query' => $query,
        ]);

        $gpsData = json_decode($response->getBody(), true);

        return $gpsData;
    }

    public function getShipmentDetails($id) {
        $shipmentDetails = DB::table('shipments')
        ->where('truck_plate_no', $id)
        ->orderBy('created_at', 'desc')
        ->get();

        return $shipmentDetails;
    }

    public function getVehicleDetails($id) {

        // Retrieve shipments
        // $shipments = DB::table('shipments')
        //     ->join('drivers','drivers.id',"=",'shipments.driver')
        //     ->join('customers','customers.id',"=",'shipments.customer')
        //     ->join('vehicles','vehicles.id',"=",'shipments.truck_plate_no')
        //     ->where('shipments.customer',"=",$id)
        //     ->where(function($query) {
        //         $query->where('shipments.delivery_status', 0)
        //               ->orWhere('shipments.delivery_status', 1);
        //     })
        //     ->select('shipments.*','drivers.name','customers.last_name','customers.address as c_address','drivers.address as d_address','customers.phone_no','drivers.phone_num','customers.device_id','vehicles.imei','vehicles.vehicle_color as vehicle_color','vehicles.plate_no as vehicle_plate_no')
        //     ->orderBy('created_at','desc')
        //     ->get();

            $shipments = DB::table('vehicles')
            ->leftjoin('shipments', 'shipments.truck_plate_no',"=","vehicles.id")
            // ->where('shipments.delivery_status', 1)
            ->select('vehicles.*', 'shipments.shipment_code as shipment_code', 'shipments.item_code as item_code', 'shipments.item_description as item_description', 'shipments.item_color as item_color', 'shipments.amount as amount', 'shipments.truck_plate_no', 'shipments.delivery_status as delivery_status', 'shipments.created_at as shipment_created_at')
            ->orderBy('created_at','desc')
            ->get();
    
        $vehicleDataArray = []; // Array to store vehicle data for each shipment
    
        // Loop through each shipment
        foreach ($shipments as $shipment) {
            // Get IMEI from current shipment
            $imei = $shipment->imei;
    
            // Get the access token using the getAccessToken method
            $accessToken = $this->requestAccessToken();
        
            // Fetch GPS data using the access token and the supplied IMEI
            $vehicleData = $this->fetchBackGPSData($accessToken, $imei);
    
            // Determine the engine status based on conditions
            $vehicleStatus = '';
            if ($vehicleData['data'][0]['accStatus'] == true) {
                if ($vehicleData['data'][0]['status'] == '静止') {
                    $vehicleStatus = 'Idle';
                } elseif ($vehicleData['data'][0]['status'] == '行驶') {
                    $vehicleStatus = 'Moving';
                }
            } else {
                $vehicleStatus = 'Stopped';
            }

            // $extVoltage = $vehicleData['data'][0]['extVoltage'] != NULL ?  $vehicleData['data'][0]['extVoltage'] : 0;
            // $battery = $extVoltage / 10;
    
            // Append vehicle data to the array
            $vehicleDataArray[$imei] = [
                'lat' => $vehicleData['data'][0]['lat'],
                'lng' => $vehicleData['data'][0]['lng'],
                'status' => $vehicleData['data'][0]['status'],
                'engine' => $vehicleData['data'][0]['accStatus'],
                'speed'=>$vehicleData['data'][0]['speed'],
                //'battery' => $battery,
                'vehicleStatus' => $vehicleStatus,
            ];
        }
    
        // Join shipments and vehicle data based on IMEI
        $shipmentData = [];
        foreach ($shipments as $shipment) {
            $imei = $shipment->imei;
            if (isset($vehicleDataArray[$imei])) {
                $shipmentData[] = (object)array_merge((array)$shipment, (array)$vehicleDataArray[$imei]);
            }
        }
    
        return response()->json([
            'success' => true,
            'shipments' => $shipmentData
        ]);
    }
    
    public function getAdminReports(Request $request)
    {
        // Assuming you have pagination logic in place, adjust as needed
        $adminReports = DB::table('admin_reports')->paginate(3);
    
        return response()->json([
            'success' => true,
            'adminReports' => $adminReports
        ]);
    }

    public function loadMoreAdminReports(Request $request)
{
    // Assuming you have pagination logic in place
    $offset = $request->query('offset', 0); // You may need to adjust this according to your pagination logic
    $limit = $request->query('limit', 3); // Adjust the limit as needed

    $adminReports = DB::table('admin_reports')
        ->offset($offset)
        ->limit($limit)
        ->get();

    return response()->json([
        'success' => true,
        'adminReports' => $adminReports
    ]);
}

    
    public function getBackGPSData($imei)
    {
        // Get the access token using the getAccessToken method
        $accessToken = $this->requestAccessToken();
    
        // Fetch GPS data using the access token and the supplied IMEI
        $gpsData = $this->fetchBackGPSData($accessToken, $imei);
    
        // Extract lat, lng, and course from the GPS data
        $lat = $gpsData['data'][0]['lat'];
        $lng = $gpsData['data'][0]['lng'];
        $course = $gpsData['data'][0]['course'];
        $speed = $gpsData['data'][0]['speed'];
        $extVoltage = $gpsData['data'][0]['extVoltage'];
        $battery = $extVoltage / 10;
        $status = $gpsData['data'][0]['status'];
        $engine = $gpsData['data'][0]['accStatus'];
        $imei =  $gpsData['data'][0]['imei'];
        $plateNo = $gpsData['data'][0]['licenseNumber'];

        if($engine == "true") {
            $engineStatus = "ON";
        }else{
            $engineStatus = "OFF";
        }
    
        // Return the GPS data as JSON
        return response()->json([
            'lat' => $lat,
            'lng' => $lng,
            'course' => $course,
            'speed' => $speed,
            'battery' => $battery,
            'status' => $status,
            'engine' => $engineStatus,
            'imei' => $imei,
            'plateNo' => $plateNo
        ]);
    }

    public function getUserRating(Request $request, $id) {

        $userRating = DB::table('shipments')
            ->where('id',"=",$id)
            ->first();

        return response()->json($userRating->rating, 200);
    }

    public function storeRating(Request $request, $id) {
        $rating = $request->input('rating');

        DB::table('shipments')
            ->where('id',"=",$id)
            ->update([
                'rating' => $rating,
                'updated_at' => now()
            ]);
        $driver = DB::table('shipments')
        ->where('id',"=", $id)
        ->pluck('driver')
        ->first();

        $customer = DB::table('shipments')
        ->where('id',"=", $id)
        ->pluck('customer')
        ->first();

        $order_id = DB::table('shipments')
        ->where('id',"=", $id)
        ->pluck('item_code')
        ->first();

        $cust_first_name= DB::table('customers')
        ->where('id',"=", $customer)
        ->pluck('first_name')
        ->first();

        $cust_last_name= DB::table('customers')
        ->where('id',"=", $customer)
        ->pluck('last_name')
        ->first();

        $deviceToken = DB::table('drivers')
        ->where('id',"=", $driver)
        ->pluck('device_token')
        ->first();

        $this->registerFCMToken($deviceToken, $cust_first_name, $cust_last_name, $driver, $order_id);
        return response()->json(['message' => 'Rating successfully stored'], 200);
    }

    public function registerFCMToken($deviceToken, $cust_first_name, $cust_last_name, $driver, $order_id)
    {
        $API_ACCESS_KEY = 'AAAAs6L9978:APA91bGQ1Av5D-BgoTFeASTU19_-P_zr22LCel7ad_lJFX_2COVI21lIo8HBkL8XurHrQ0kxUMykl5dHi_0eKKRWa8ZsERK_EOUcU2QnTfvjZGo0yvit6cubWwCAkiWczUIQByUMFLNw';
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        

        $notification = [
            'title' => 'Yes Sir Driver',
            'body' => 'You have been rated by '.$cust_first_name.' '.$cust_last_name,
            'icon' => 'myIcon',
            'sound' => 'mySound'
        ];
        $extraNotificationData = ["message" => $notification, "moredata" => 'dd'];

        $fcmNotification = [
            'to' => $deviceToken, //single token
            'notification' => $notification,
            'data' => $extraNotificationData
        ];

        $headers = [
            'Authorization: key=' . $API_ACCESS_KEY,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);

        DB::table('driver_notifications')
        ->insert([
            'id' => Str::random(30),
            'driver' => $driver,
            'notification_text' => 'You have been rated by '.$cust_first_name.' '.$cust_last_name.' for Order #'.$order_id,
            'created_at' => now()
        ]);

        return response()->json(['message' => 'FCM message sent', 'response' => $result]);
    }

    public function getNotification($id){

        $notifications = DB::table('customer_notifications')
        ->where('customer',"=",$id)
        ->select('*')
        ->orderBy('created_at','desc')
        ->get();

        return response()->json($notifications);
    }

    public function getDeviceQuery($accessToken)
    {
        $client = new Client();
        $apiUrl = 'https://open.iopgps.com/api/device/status';

        // Include the access token and other query parameters in the request
        $query = [
            'accessToken' => $accessToken,
        ];

        $response = $client->get($apiUrl, [
            'query' => $query,
        ]);

        $devices = json_decode($response->getBody(), true);

        return $devices;
    }

    public function deviceList(){
        // Get the access token using the getAccessToken method
        $accessToken = $this->requestAccessToken();
    
        // Fetch GPS data using the access token and the supplied IMEI
        $devices = $this->getDeviceQuery($accessToken);
    
        // Initialize an array to store all device information
        $deviceList = [];
    
        // Loop through each device in the data array
        foreach ($devices['data'] as $device) {
            // Check if 'extVoltage' key exists in the device
            if (isset($device['extVoltage'])) {
                // Extract lat, lng, and course from each device
                $lat = $device['lat'];
                $lng = $device['lng'];
                $course = $device['course'];
                $speed = $device['speed'];
                $extVoltage = $device['extVoltage'];
                $battery = $extVoltage / 10;
                $status = $device['status'];
                $engine = $device['accStatus'];
                $imei =  $device['imei'];
                $plateNo = $device['licenseNumber'];

                if($status == "行驶" && $engine == "true") {
                    $newStatus = 'MOVING';
                }else if($status == "静止" && $engine == "true"){
                    $newStatus = 'IDLE';
                }else{
                    $newStatus = 'STOPPED';
                }

                // Determine engine status
                $engineStatus = ($engine == "true") ? "ON" : "OFF";

                // Add device information to the list
                $deviceList[] = [
                    'lat' => $lat,
                    'lng' => $lng,
                    'course' => $course,
                    'speed' => $speed,
                    'battery' => $battery,
                    'status' => $status,
                    'engine' => $engineStatus,
                    'imei' => $imei,
                    'plateNo' => $plateNo,
                    'newStatus' => $newStatus
                ];
            } else {
                // If 'extVoltage' doesn't exist, show placeholder value or indicate it's not available
                if($device['status'] == "行驶" && $device['accStatus'] == "true") {
                    $newStatus = 'MOVING';
                }else if($device['status'] == "静止" && $device['accStatus'] == "true"){
                    $newStatus = 'IDLE';
                }else{
                    $newStatus = 'STOPPED';
                }

                $deviceList[] = [
                    'lat' => $device['lat'],
                    'lng' => $device['lng'],
                    'course' => $device['course'],
                    'speed' => $device['speed'],
                    'battery' => null, // or any placeholder value
                    'status' => $device['status'],
                    'engine' => ($device['accStatus'] == "true") ? "ON" : "OFF",
                    'imei' => $device['imei'],
                    'plateNo' => $device['licenseNumber'],
                    'newStatus' => $newStatus
                ];
            }
        }
    
        // Return the list of device information as JSON
        return response()->json($deviceList);
    }

    public function addAppOnlineStatus(Request $request, $id)
    {
        $status = $request->input('status');

        if ($status == "ONLINE") {
            DB::table('customers')
                ->where('id', $id)
                ->update([
                    'app_online_status' => 1
                ]);
        } else {
            DB::table('customers')
                ->where('id', $id)
                ->update([
                    'app_online_status' => 0
                ]);
        }

        return response()->json(['message' => 'App status updated successfully']);
    }

    public function getDriverReports(Request $request)
    {
        $driverReports = DB::table('driver_reports')
            ->join('drivers', 'drivers.id', '=', 'driver_reports.user_id')
            ->join('shipments','shipments.driver',"=", 'driver_reports.user_id')
            ->join('vehicles','vehicles.id',"=", 'shipments.truck_plate_no')
            ->select('driver_reports.*', 'drivers.name as driver_name', 'drivers.surname as driver_surname', 'drivers.user_image as driver_image','vehicles.plate_no')
            ->orderBy('created_at', 'desc')
            ->paginate(8);
    
        // Convert created_at to Malaysia time (GMT+8)
        foreach ($driverReports as $report) {
            $report->created_at = Carbon::parse($report->created_at)->addHours(8)->toDateTimeString();
        }
    
        return response()->json([
            'success' => true,
            'driverReports' => $driverReports
        ]);
    }
}
