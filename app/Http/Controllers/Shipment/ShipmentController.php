<?php

namespace App\Http\Controllers\Shipment;

use App\Events\Notification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Driver;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use PDF;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Broadcast;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class ShipmentController extends Controller
{

    // Add a constructor to apply the 'auth' middleware
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $shipments = DB::table('shipments')
        ->join('customers','customers.id',"=",'shipments.customer')
        ->join('drivers','drivers.id',"=",'shipments.driver')
        ->select('shipments.*','customers.last_name', 'shipments.item_code')
        ->orderBy('shipments.created_at', 'desc')
        ->get();

        return view('Shipment.index',compact('shipments'));
    }

    public function newShipmentPage(){

        $drivers = DB::table('drivers')
        ->select('*')
        ->get();

        $vehicles = DB::table('vehicles')
        ->select('*')
        ->get();

        $customers = DB::table('customers')
        ->select('*')
        ->get();

        return view('Shipment.new_shipment', compact('drivers','customers','vehicles'));
    }

    public function createNewShipment(Request $request)
    {
        // Validation rules
        $validationRules = [
            'shipment_code' => [
                'required',
                Rule::unique('shipments', 'shipment_code'),
            ],
            'item_code.*' => 'required',
            'item_desc.*' => 'required',
            'amount.*' => 'required|numeric',
            'item_color.*' => 'required', // Add validation rule for item_color
            'driver' => 'required',
            'truck_plate' => 'required',
            'customer' => 'required',
            'delivery_status' => 'required',
        ];

        // Custom error messages
        $customMessages = [
            'shipment_code.required' => 'The shipment number is required.',
            'shipment_code.unique' => 'The shipment number must be unique.',
            'item_code.*.required' => 'Item code is required for all items.',
            'item_desc.*.required' => 'Item description is required for all items.',
            'amount.*.required' => 'Amount is required for all items.',
            'amount.*.numeric' => 'Amount must be a number for all items.',
            'item_color.*.required' => 'Item color is required for all items.', // Custom message for item_color
            'driver.required' => 'Assigned driver is required.',
            'truck_plate.required' => 'Truck plate number is required.',
            'customer.required' => 'Customer is required.',
            'delivery_status.required' => 'Delivery status is required.',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $validationRules, $customMessages);

        // Check if validation fails
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        // Retrieve data from the request
        $shipment_code = $request->input('shipment_code');
        $item_codes = $request->input('item_code');
        $item_descs = $request->input('item_desc');
        $item_colors = $request->input('item_color'); // Retrieve item colors
        $amounts = $request->input('amount');
        $driver = $request->input('driver');
        $truck_plate = $request->input('truck_plate');
        $customer = $request->input('customer');
        $delivery_status = $request->input('delivery_status');
        $remarks = $request->input('remarks');

        // Check if delivery_status is 1 (Delivering)
        if ($delivery_status == 1) {
            // Check if there is already a shipment with status "Delivering" for the given truck_plate_no
            $existingDeliveringShipment = DB::table('shipments')
                ->where('truck_plate_no', $truck_plate)
                ->where('delivery_status', 1)
                ->exists();

            $existingDeliveringDriver = DB::table('shipments')
                ->where('driver', $driver)
                ->where('delivery_status', 1)
                ->exists();
                

            // Check if there is an existing delivering shipment
            if ($existingDeliveringShipment) {
                return Redirect::back()->with('error', 'Only one shipment delivery can be made for one vehicle. The current selected vehicle has another shipment delivery status on Delivering.')->withInput();
            } else if ($existingDeliveringDriver) {
                return Redirect::back()->with('error', 'Only one shipment delivery can be made for one driver. The current selected driver has another shipment delivery status on Delivering.')->withInput();
            }

        }

        // Initialize arrays to store item details
        $itemCodes = [];
        $itemDescs = [];
        $itemColors = []; // Initialize array for item colors
        $amountsArray = [];

        // Insert each item separately
        for ($i = 0; $i < count($item_codes); $i++) {
            $itemCode = $item_codes[$i];
            $itemDesc = $item_descs[$i];
            $itemColor = $item_colors[$i]; // Retrieve item color
            $amount = $amounts[$i];

            // Store item details in arrays
            $itemCodes[] = $itemCode;
            $itemDescs[] = $itemDesc;
            $itemColors[] = $itemColor; // Store item color
            $amountsArray[] = $amount;
        }

        // Implode arrays to create strings for storage in the database
        $itemCodesString = implode('==', $itemCodes);
        $itemDescsString = implode('==', $itemDescs);
        $itemColorsString = implode('==', $itemColors); // Implode item colors array
        $amountsString = implode('==', $amountsArray);

        // Insert the shipment into the database
        DB::table('shipments')->insert([
            'id' => Str::random(30),
            'shipment_code' => $shipment_code,
            'item_code' => $itemCodesString,
            'item_description' => $itemDescsString,
            'item_color' => $itemColorsString, // Store item colors
            'amount' => $amountsString,
            'delivery_status' => $delivery_status,
            'truck_plate_no' => $truck_plate,
            'driver' => $driver,
            'customer' => $customer,
            'remarks' => $remarks != null ? $remarks : 'No Remarks Yet.',
            'rating' => 0,
            'created_at' => now(),
        ]);

        // Redirect with success message
        return Redirect::back()->with('success', 'New Shipment Added Successfully');
    }


    public function shipmentDetail($id){
        $shipment_code = DB::table('shipments')
        ->where('id',"=",$id)
        ->pluck('shipment_code')
        ->first();

        $itemCodes = DB::table('shipments')
        ->where('id', "=", $id)
        ->pluck('item_code')
        ->first();

        $distinctItemCodes = explode('==', $itemCodes);
        $totalOrders = count(array_unique($distinctItemCodes));

        $shipmentDetail = DB::table('shipments')
        ->join('drivers','drivers.id',"=",'shipments.driver')
        ->join('customers','customers.id',"=",'shipments.customer')
        ->join('vehicles','vehicles.id',"=",'shipments.truck_plate_no')
        ->where('shipments.id',"=",$id)
        ->select('shipments.*','drivers.name','customers.last_name','vehicles.plate_no','vehicles.imei')
        ->get();

        $form = DB::table('shipments')
        ->join('drivers','drivers.id',"=",'shipments.driver')
        ->join('vehicles','vehicles.id',"=",'shipments.truck_plate_no')
        ->where('shipments.id',"=",$id)
        ->select('shipments.*','drivers.name','vehicles.plate_no')
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

        return view('Shipment.shipment_detail',compact('shipment_code','totalOrders','shipmentDetail','form','drivers','customers', 'allVehicle'));
    }

    public function updateShipmentDetails(Request $request, $id){
        $item_code = implode("==", $request->input('item_code'));
        $item_description = implode("==", $request->input('item_description'));
        $amount = implode("==", $request->input('amount'));
        $driver = $request->input('driver');
        $customer = $request->input('customer');
        $truck_plate = $request->input('truck_plate');
        $status = $request->input('status');
        $remarks = $request->input('remarks');
        $shipment_code = $request->input('shipment_code');
        $itemColor = implode("==", $request->input('item_color'));
    
        $originalStatus = DB::table('shipments')->where('id', $id)->value('delivery_status');
        
        // Check if the new status is "Delivering" (status code 1)
        if ($status == 1) {
            // Check if there is already a shipment with status "Delivering" for the given truck_plate_no
            $existingDeliveringShipment = DB::table('shipments')
                ->where('truck_plate_no', $truck_plate)
                ->where('delivery_status', 1)
                ->where('id', '!=', $id) // Exclude the current shipment being updated
                ->exists();

                $existingDeliveringDriver = DB::table('shipments')
                ->where('driver', $driver)
                ->where('delivery_status', 1)
                ->where('id', '!=', $id) // Exclude the current shipment being updated
                ->exists();
    
            // If there is an existing delivering shipment, return an error message
            if ($existingDeliveringShipment) {
                return Redirect::back()->with('error','Update Failed. One vehicle may only have one shipment delivery. Please ensure that the current vehicle shipment status has been Delivered, then update the status.');
            } else if ($existingDeliveringDriver) {
                return Redirect::back()->with('error','Update Failed. One driver may only have one shipment delivery. Please ensure that the current driver shipment status has been Delivered, then update the status.');
            }
        }
    
        DB::table('shipments')
        ->where('id', "=", $id)
        ->update([
            'shipment_code' => $shipment_code,
            'item_code' => $item_code,
            'item_description' => $item_description,
            'amount' => $amount,
            'delivery_status' => $status,
            'truck_plate_no' => $truck_plate,
            'driver' => $driver,
            'customer' => $customer,
            'remarks' => $remarks,
            'item_color' => $itemColor, // Include item_color in the update
            'updated_at' => now()
        ]);
    
        // delivery_status == '0' To Be Delivered    
        // delivery_status == '1' Delivering
        // delivery_status == '2' Delivered
        // delivery_status == '3' On Hold 
        // delivery_status == '4' Cancelled
        if ($status != $originalStatus) {
            // delivery_status == '0' To Be Delivered    
            // delivery_status == '1' Delivering
            // delivery_status == '2' Delivered
            // delivery_status == '3' On Hold 
            // delivery_status == '4' Cancelled
            if ($status == 0) {
                $status = 'To Be Delivered';
                $message = 'Order ' . $shipment_code . ' delivery status has been changed back ' . $status;
            } elseif ($status == 1) {
                $status = 'Delivering';
                $message = 'Order ' . $shipment_code . ' delivery status has updated to ' . $status;
            } elseif ($status == 2) {
                $status = 'Delivered';
                $message = 'Order ' . $shipment_code . ' delivery status has updated to ' . $status;
            } elseif ($status == 3) {
                $status = 'On Hold';
                $message = 'Order ' . $shipment_code . ' delivery status has updated to ' . $status;
            } elseif ($status == 4) {
                $status = 'Cancelled';
                $message = 'Order ' . $shipment_code . ' has been ' . $status;
            } else {
                $message = 'Order ' . $shipment_code . ' has updated delivery status to ' . $status;
            }
    
            $redirectPath = 'https://staging.yessirgps.com/shipment_detail/' . $id;
    
            $this->saveNotification($message, 1, $redirectPath);
    
            broadcast(new Notification(1, $message, $redirectPath))->toOthers();
        }
    
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

    public function getGPSData($imei)
    {
        // Get the access token using the getAccessToken method
        $accessToken = $this->getAccessToken();
    
        // Fetch GPS data using the access token and the supplied IMEI
        $gpsData = $this->fetchGPSData($accessToken, $imei);
    
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
    
        // Pass the lat, lng, and course to the view
        return view('Shipment.shipment_location', compact('lat', 'lng', 'course','imei','speed','battery','status','engine','imei','plateNo'));

    }

    public function requestAccessToken()
    {
        $username = 'yessirgps'; // Replace with your actual username
        $timestamp = time();
        $loginKey = '2t!#SzKYS&B$mdJN^cuAxBQ4W9VSg&U6'; // Your login key

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

    public function fetchPlaybackTrace($accessToken, $imei, $startTime, $endTime)
    {
        $client = new Client();
        $apiUrl = 'https://open.iopgps.com/api/device/track/history';

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

        $trackingData = json_decode($response->getBody(), true);

        return $trackingData;
    }

    public function getTrackingData($imei, $startTime, $endTime)
    {
        // Get the access token using the getAccessToken method
        $accessToken = $this->requestAccessToken();
    
        // Fetch GPS data using the access token and the supplied IMEI
        $trackingData = $this->fetchPlaybackTrace($accessToken, $imei, $startTime, $endTime);
    
        // Return the GPS data as JSON
        return response()->json($trackingData);
    }

    public function fetchCurrentLocation($accessToken, $imei)
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

        $locationData = json_decode($response->getBody(), true);

        return $locationData;
    }

    public function fetchGeofenceData($accessToken, $imei)
    {
        $client = new Client();
        $apiUrl = 'https://open.iopgps.com/api/fence/query';

        // Include the access token and other query parameters in the request
        $query = [
            'accessToken' => $accessToken,
            'imei' => $imei,
            'mapType' => 1,
        ];

        $response = $client->get($apiUrl, [
            'query' => $query,
        ]);

        $geofenceData = json_decode($response->getBody(), true);

        return $geofenceData;
    }

    public function getLocationData($imei)
    {
        // Get the access token using the getAccessToken method
        $accessToken = $this->getAccessToken();
    
        // Fetch locatino and geofence data using the access token and the supplied IMEI
        $locationData = $this->fetchCurrentLocation($accessToken, $imei);
        $geofenceData = $this->fetchGeofenceData($accessToken, $imei);
    
        // Extract information from the location data and geofence data
        $lat = $locationData['data'][0]['lat'];
        $lng = $locationData['data'][0]['lng'];

        $fenceId = [];
        $fenceName = [];
        $fenceCoordinates = [];
        $fenceType = [];

        for ($i = 3; $i < count($geofenceData['fenceBeanList']); $i++) {
            $fenceId[] = $geofenceData['fenceBeanList'][$i]['fenceId'];
            $fenceName[] = $geofenceData['fenceBeanList'][$i]['fenceName'];
            $fenceCoordinates[] = $geofenceData['fenceBeanList'][$i]['setting'];
            $fenceType[] = $geofenceData['fenceBeanList'][$i]['type'];
        }

        // Pass the data values to the view
        return view('Shipment.shipment_geofence', compact('lat', 'lng', 'imei', 'fenceId', 'fenceName', 'fenceCoordinates', 'fenceType'));

    }

    public function saveGeofenceData(Request $request, $imei)
    {
        // Retrieve geofence data from the POST request
        $geofenceName = $request->input('fenceName');
        $alertType = $request->input('triggerType');
        $triggerOnce = $request->input('oneTime');
        $type = $request->input('type');
        $setting = $request->input('setting');
        $mapType = 1;
        $imei = $request->input('imei');

        // You can add any additional data needed for the API request

        // Perform the API request to save the geofence data
        $accessToken = $this->getAccessToken(); // Get the access token


        $client = new Client();
        $apiUrl = 'https://open.iopgps.com/api/fence/add?accessToken=' . $accessToken; // Replace with the actual API URL

        // Define the data to be sent in the API request
        $postData = [
            'imei' => $imei, // Use the IMEI from the parameter
            'fenceName' => $geofenceName,
            'triggerType' => $alertType,
            'oneTime' => $triggerOnce,
            'type' => $type,
            'setting' => $setting,
            'mapType' => $mapType 
        ];

        // Send the API request to save geofence data
        $response = $client->post($apiUrl, [
            'json' => $postData,
        ]);

        // Handle the API response as needed

        // Redirect or return a response back to your application

        // For example:
        if ($response->getStatusCode() === 200) {
            return redirect()->route("get-location-data")->with('success', 'Geofence data saved successfully');
        } else {
            return redirect()->route("get-location-data")->with('error', 'Failed to save geofence data');
        }
    }

    public function deleteGeofenceData(Request $request, $imei, $fenceId)
    {
        // Get the access token using the getAccessToken method
        $accessToken = $this->getAccessToken();

        // Prepare and execute the API call to delete the specific geofence
        $url = 'https://open.iopgps.com/api/fence/del/' . $fenceId . '?imei=' . $imei . '&accessToken=' . $accessToken;
        
        // Use Laravel's HTTP client to perform the API request
        $response = Http::delete($url);

        // Handle the response as needed (check status, error handling, etc.)
        if ($response->successful()) {
            return redirect()->route('get-location-data', ['imei' => $imei]);
        } else {
            // Handle error response or failed deletion
        }
    }

    public function saveNotification($message, $eventId, $redirectPath)
    {
        try {
            // Generate a random 30-character ID
            $id = Str::random(30);

            // Save the notification to the database
            DB::table('admin_notifications')->insert([
                'id' => $id,
                'message' => $message,
                'eventId' => $eventId,
                'viewedStatus' => 1,
                'redirectPath' => $redirectPath,
                'created_at' => now(),
            ]);

            return true;
        } catch (\Exception $e) {
            \Log::error('Error saving notification: ' . $e->getMessage());
            return false;
        }
    }
}
