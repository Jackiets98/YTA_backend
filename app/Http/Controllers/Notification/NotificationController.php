<?php

namespace App\Http\Controllers\Notification;

use App\Events\Notification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Broadcast;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\Auth;


class NotificationController extends Controller
{

    // Add a constructor to apply the 'auth' middleware
    public function __construct()
    {
        $this->middleware('auth');
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

    public function getImei()
    {
        $imeis = DB::table('vehicles')
            ->select('imei')
            ->distinct()
            ->pluck('imei');

        return $imeis;
    }

    public function geofenceAlert()
    {
        $client = new Client();
        $apiUrl = 'https://open.iopgps.com/api/device/alarm';

        $accessToken = $this->getAccessToken();
        $imeis = $this->getImei();
        
        // Calculate the startTime as the current Unix time minus 3 weeks
        $startTime = Carbon::now()->subWeeks(3)->timestamp;

        $alertData = [];

        foreach ($imeis as $imei) {
            // Include the access token and other query parameters in the request
            $query = [
                'accessToken' => $accessToken,
                'imei' => $imei,
                'startTime' => $startTime,
            ];

            $response = $client->get($apiUrl, [
                'query' => $query,
            ]);

            $responseData = json_decode($response->getBody(), true);

            // Check if the 'details' key exists in the response and is not empty
            if (isset($responseData['details']) && !empty($responseData['details'][0])) {
                // Access the first element of the 'details' array
                $currentDetail = $responseData['details'][0];

                // Retrieve the previous detail from the cache
                $cacheKey = 'notification_details_' . $imei;
                $previousDetail = Cache::get($cacheKey);

                // Compare the current and previous details
                if ($currentDetail != $previousDetail) {
                    // Check if the 'alarmCode' key exists in the current detail
                    if (isset($currentDetail['alarmCode'])) {
                        // Check if the 'alarmCode' is 'FENCEIN' or 'FENCEOUT'
                        if (in_array($currentDetail['alarmCode'], ['FENCEIN', 'FENCEOUT'])) {
                            // Determine the appropriate message based on the alarmCode
                            $message = ($currentDetail['alarmCode'] == 'FENCEIN') ? 'The driver is now within the geofence' : 'The driver has exited from the geofence';
                            $redirectPath = 'https://staging.yessirgps.com/get-location-data/' . $imei;

                            $this->saveNotification($message, 2, $redirectPath);
                            // Trigger the GeofenceAlert event with the message
                            broadcast(new Notification(2, $message, $redirectPath))->toOthers();
                        }
                    }

                    // Update the cache with the current detail for the next comparison
                    Cache::put($cacheKey, $currentDetail);
                }

                // Merge the data for each IMEI into the result array
                $alertData = array_merge($alertData, $responseData);

            }
        }

        return $alertData;
    }

    public function speedAlert(Request $request)
    {
        $settings = DB::table('settings')->where('id', 1)->first();

        if ($settings) {
            $speedLimit = $settings->speed_limit;
        } else {
            $speedLimit = null;
        }

        $client = new Client();
        $apiUrl = 'https://open.iopgps.com/api/device/status';

        $accessToken = $this->getAccessToken();
        $imeis = $this->getImei();

        foreach ($imeis as $imei) {
            $query = [
                'accessToken' => $accessToken,
                'imei' => $imei,
            ];

            $response = $client->get($apiUrl, [
                'query' => $query,
            ]);

            $vehicleData = json_decode($response->getBody(), true);

            $speed = $vehicleData['data'][0]['speed'];

            // Check if the current speed is greater than the speed limit
            if ($speed >= $speedLimit) {
                $message = "Driver is overspeeding! Current speed: $speed km/h";
                $redirectPath = 'https://staging.yessirgps.com/get-vehicle-data/' . $imei;

                $this->saveNotification($message, 3, $redirectPath);

                // Broadcast the geofence alert message
                broadcast(new Notification(3, $message, $redirectPath))->toOthers();
            }
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

    public function getNotifications()
    {
        try {
            // Fetch notifications from the database
            $notifications = DB::table('admin_notifications')->orderBy('created_at', 'desc')->get();

            return response()->json($notifications);
        } catch (\Exception $e) {
            \Log::error('Error fetching notifications: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Internal Server Error'], 500);
        }
    }

    
    public function markAsViewed()
    {
        // Sleep for 4 seconds
        sleep(4);

        // Assuming your notifications are stored in a table named 'admin_notifications'
        DB::table('admin_notifications')->update(['viewedStatus' => 0, 'updated_at'=>now()]);

        // You can also use Artisan command to delay the response
        // Artisan::call('sleep:4');

        return response()->json(['message' => 'Notifications marked as viewed']);
    }





}