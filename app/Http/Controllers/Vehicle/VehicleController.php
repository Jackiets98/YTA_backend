<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Driver;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use PDF;
use GuzzleHttp\Client;

class VehicleController extends Controller
{

    // Add a constructor to apply the 'auth' middleware
    public function __construct()
    {
        // Apply 'auth' middleware to all methods except 'saveMileage'
        $this->middleware('auth', ['except' => ['saveMileage']]);
    }
    
    public function index()
    {
        $vehicles = DB::table('vehicles')
        ->select('*')
        ->get();

        return view('Vehicle.index',compact('vehicles'));
    }

    public function newVehiclePage(){

        $drivers = DB::table('drivers')
        ->select('*')
        ->get();

        $customers = DB::table('customers')
        ->select('*')
        ->get();

        return view('Vehicle.new_vehicle', compact('drivers','customers'));
    }

    public function createNewVehicle(Request $request){
        $vin_no = $request->input('vin_no');
        $plate_no = $request->input('plate_no');
        $contract = $request->input('contract_no');
        $vehicleOwner = $request->input('vehicle_owner');
        $contactPerson = $request->input('contact_person');
        $phone = $request->input('phone_no');
        $vehicleBrand = $request->input('vehicle_brand');
        $vehicleColor = $request->input('vehicle_color');
        $vehicleMileage = $request->input('vehicle_mileage');
        $device_name = $request->input('device_name');
        $imei = $request->input('imei');
        $sim = $request->input('sim_no');
        $remarks = $request->input('remarks');

        if ($remarks != NULL){
            DB::table('vehicles')
            ->insert([
                'id' => Str::random(30),
                'vin_no' => $vin_no,
                'plate_no' => $plate_no,
                'contract_no' => $contract,
                'vehicle_owner' => $vehicleOwner,
                'contact_person' => $contactPerson,
                'phone_no' => $phone,
                'vehicle_brand' => $vehicleBrand,
                'vehicle_color' => $vehicleColor,
                'vehicle_mileage' => $vehicleMileage,
                'device_name' => $device_name,
                'imei' => $imei,
                'sim_no' => $sim,
                'status' => '1',
                'last_param' => 1,
                'remarks' => $remarks,
                'created_at' => now()
            ]);
        }else{
            DB::table('vehicles')
            ->insert([
                'id' => Str::random(30),
                'vin_no' => $vin_no,
                'plate_no' => $plate_no,
                'contract_no' => $contract,
                'vehicle_owner' => $vehicleOwner,
                'contact_person' => $contactPerson,
                'phone_no' => $phone,
                'vehicle_brand' => $vehicleBrand,
                'vehicle_color' => $vehicleColor,
                'vehicle_mileage' => $vehicleMileage,
                'device_name' => $device_name,
                'imei' => $imei,
                'sim_no' => $sim,
                'status' => '1',
                'last_param' => 1,
                'remarks' => 'No Remarks Yet',
                'created_at' => now()
            ]);
        }

        $vehicles = DB::table('vehicles')
        ->select('*')
        ->get();

        return Redirect::back()->with('success','New Vehicle Added Successfully');
        
    }

    public function vehicleDetail($id){

        $vin_no = DB::table('vehicles')
        ->where('id',"=",$id)
        ->pluck('vin_no')
        ->first();

        $currentMileage = DB::table('vehicles')
            ->where('id',"=",$id)
            ->pluck('vehicle_mileage')
            ->first();

        $plate_no = DB::table('vehicles')
            ->where('id',"=",$id)
            ->pluck('plate_no')
            ->first();

        $vehicleDetail = DB::table('vehicles')
            ->where('vehicles.id',"=",$id)
            ->select('*')
            ->get();

        $form = DB::table('vehicles')
            ->where('vehicles.id',"=",$id)
            ->get();

        $imei = DB::table('vehicles')
            ->where('id',"=",$id)
            ->pluck('imei')
            ->first();

         $totalMileage = DB::table('daily_mileage')
            ->where('imei',"=",$imei)
            ->sum('mileage');

        $totalMileage += $currentMileage;

         // Calculate start time (today 00:00:00) and end time (today 23:59:59) in milliseconds
        $startTime = strtotime('today midnight') * 1000;  // Multiply by 1000 to convert to milliseconds
        $endTime = strtotime('today 23:59:59') * 1000;    // Multiply by 1000 to convert to milliseconds

        // Get the access token using the getAccessToken method
        $accessToken = $this->getAccessToken();

        // Fetch GPS data using the access token and the supplied IMEI
        $gpsData = $this->fetchMileageData($accessToken, $imei, $startTime, $endTime);
    
        // Extract lat, lng, and course from the GPS data
        $miles = $gpsData['miles'];

        $distance = round($miles * 1.60934, 2);

        return view('Vehicle.vehicle_detail', compact('vin_no', 'plate_no', 'vehicleDetail', 'form','id','distance','totalMileage'));
    }

    public function updateVehicleDetails(Request $request, $id){
        $vin_no = $request->input('vin_no');
        $plate_no = $request->input('plate_no');
        $contract = $request->input('contract_no');
        $vehicleOwner = $request->input('vehicle_owner');
        $contactPerson = $request->input('contact_person');
        $phone = $request->input('phone_no');
        $vehicleBrand = $request->input('vehicle_brand');
        $vehicleColor = $request->input('vehicle_color');
        $vehicleMileage = $request->input('vehicle_mileage');
        $device_name = $request->input('device_name');
        $imei = $request->input('imei');
        $sim = $request->input('sim_no');
        $status = $request->input('status');
        $remarks = $request->input('remarks');

        DB::table('vehicles')
        ->where('id',"=",$id)
        ->update([
            'vin_no' => $vin_no,
            'plate_no' => $plate_no,
            'contract_no' => $contract,
            'vehicle_owner' => $vehicleOwner,
            'contact_person' => $contactPerson,
            'phone_no' => $phone,
            'vehicle_brand' => $vehicleBrand,
            'vehicle_color' => $vehicleColor,
            'vehicle_mileage' => $vehicleMileage,
            'device_name' => $device_name,
            'imei' => $imei,
            'sim_no' => $sim,
            'status' => '1',
            'remarks' => $remarks,
            'updated_at' => now()
        ]);

        return Redirect::back()->with('success','Details Updated Successfully');
    }

    public function getAccessToken()
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

    public function fetchGPSData($accessToken, $imei)
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

    public function fetchMileageData($accessToken, $imei, $startTime, $endTime)
    {
        $client = new Client();
        $apiUrl = 'https://open.iopgps.com/api/device/miles';

        // Include the access token and other query parameters in the request
        $query = [
            'accessToken' => $accessToken,
            'imei' => $imei,
            'startTime' => $startTime,
            'endTime' => $endTime
        ];

        $response = $client->get($apiUrl, [
            'query' => $query,
        ]);

        $gpsData = json_decode($response->getBody(), true);

        return $gpsData;
    }

    public function getGPSData($imei)
    {
        // Get the access token using the getAccessToken method
        $accessToken = $this->getAccessToken();

        // Fetch GPS data using the access token and the supplied IMEI
        $gpsData = $this->fetchGPSData($accessToken, $imei);

        // Extract lat, lng, and course from the GPS data with default 'n/A' if missing
        $lat = $gpsData['data'][0]['lat'] ?? 'n/A';
        $lng = $gpsData['data'][0]['lng'] ?? 'n/A';
        $course = $gpsData['data'][0]['course'] ?? 'n/A';
        $speed = $gpsData['data'][0]['speed'] ?? 'n/A';
        $extVoltage = $gpsData['data'][0]['extVoltage'] ?? 0;
        // Calculate battery with default 'n/A' if extVoltage is missing
        $battery = $extVoltage ? $extVoltage / 10 : 0;
        $status = $gpsData['data'][0]['status'] ?? 'n/A';
        $engine = $gpsData['data'][0]['accStatus'] ?? 'n/A';
        $imei = $gpsData['data'][0]['imei'] ?? 'n/A';
        $plateNo = $gpsData['data'][0]['licenseNumber'] ?? 'n/A';

        // Pass the lat, lng, and course to the view
        return view('Vehicle.vehicle_location', compact('lat', 'lng', 'course', 'imei', 'speed', 'battery', 'status', 'engine', 'imei', 'plateNo'));
    }


    public function updateEngineStatus(Request $request, $imei)
    {
        // Retrieve the access token
        $accessToken = $this->getAccessToken();

        // Get the last_param value from the database based on the provided IMEI
        $lastParam = DB::table('vehicles')
            ->where('imei', $imei)
            ->pluck('last_param')
            ->first();

        // Toggle the parameter value between 1 and 2
        $newParam = ($lastParam == 1) ? 2 : 1;

        // Construct the API URL with the access token
        $apiUrl = 'https://open.iopgps.com/api/instruction/relay?accessToken=' . $accessToken;

        // Define the parameters for the POST request
        $postData = [
            'parameter' => $newParam,
            'imeis' => [$imei],
            // Add other parameters as needed
        ];

        // Use a HTTP client (e.g., Guzzle) to make a POST request to the API
        $client = new Client();
        $response = $client->post($apiUrl, [
            'json' => $postData,
        ]);

        if ($response->getStatusCode() === 200) {
            // If the API call is successful, update the last_param value in the database
            DB::table('vehicles')
                ->where('imei', $imei)
                ->update(['last_param' => $newParam]);
        }else {
            // Handle API call failure and display an error message to the user
            return redirect()->back()->with('error', 'Update Engine Status failed. Please try again.');
        }

        // Redirect back or return a response to the user
        return redirect()->back()->with('success', 'Engine status updated successfully');
    }

    public function getDashcam($id) {

        $vin_no = DB::table('vehicles')
        ->where('id',"=",$id)
        ->pluck('vin_no')
        ->first();

        return view('Vehicle.vehicle_dashcam', compact('vin_no','id'));
    }

    public function saveMileage() {

        $imeiList = DB::table('vehicles')->pluck('imei');
    
        foreach ($imeiList as $imei) {
            // Calculate start time (today 00:00:00) and end time (today 23:59:59) in milliseconds
            $startTime = strtotime('today midnight') * 1000;  // Multiply by 1000 to convert to milliseconds
            $endTime = strtotime('today 23:59:59') * 1000;    // Multiply by 1000 to convert to milliseconds
        
            // Get the access token using the getAccessToken method
            $accessToken = $this->getAccessToken();
        
            // Fetch GPS data using the access token and the current IMEI
            $gpsData = $this->fetchMileageData($accessToken, $imei, $startTime, $endTime);
        
            // Extract lat, lng, and course from the GPS data
            $miles = $gpsData['miles'];
        
            $distance = round($miles * 1.60934, 2);
        
            // Check if a record with the given imei and a created_at timestamp within the specified range exists
            $existingRecord = DB::table('daily_mileage')
                ->where('imei', $imei)
                ->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])
                ->first();
            
            if ($existingRecord) {
                // Update the existing record
                DB::table('daily_mileage')
                    ->where('id', $existingRecord->id)
                    ->update([
                        'mileage' => $distance,
                        'updated_at' => now()
                    ]);
            } else {
                // Insert a new record
                DB::table('daily_mileage')
                    ->insert([
                        'id' => Str::random(30),
                        'imei' => $imei,
                        'mileage' => $distance,
                        'created_at' => now(),
                    ]);
            }
        }
        
        // Return the result, or handle it as needed
        return 'ok';
    }
}
