<?php

namespace App\Http\Controllers\Flutter;

use App\Events\DeliveryStatus;
use App\Events\Notification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use PDF;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use Kreait\Firebase\Messaging\CloudMessage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AppDriverController extends Controller
{

    public function register(Request $request){
        $phone = $request->input('phone_number');
        $trimmed_phone = ltrim($phone, '0');
        $name = $request->input('name');
        $surname = $request->input('surname');
        $ic_no = $request->input('ic_no');
        $license = $request->input('license');
        $address = $request->input('address');
        $email = $request->input('email');
        $password = $request->input('password');
        $city = $request->input('city');
        $state = $request->input('state');
        $postcode = $request->input('postcode');
        $hashedPassword = Hash::make($password);
        $id = Str::random(30);

        DB::table('drivers')
        ->insert([
            'id' => $id,
            'name' => $name,
            'surname' => $surname,
            'identity_card' => $ic_no,
            'license' => $license,
            'address' => $address,
            'email' => $email,
            'phone_num' => $trimmed_phone,
            'city' => $city,
            'state' => $state,
            'postcode' => $postcode,
            'password' => $hashedPassword,
            'created_at' => now()
        ]);

        return response()->json(['success' => true, 'user_id' => $id]);
    }

    public function login(Request $request){
        $phone = $request->input('phone_number');
        $trimmed_phone = ltrim($phone, '0');
        $device_id = $request->input('deviceID');
        $hashedPassword = $request->input('password');
        $device_token = $request->input('device_token');

        DB::table('drivers')
        ->where('phone_num', '=', $trimmed_phone)
        ->update([
            'device_id' => $device_id,
            'updated_at' => now(),
            'device_token'=>$device_token
        ]);

        $user = DB::table('drivers')
            ->where('phone_num', '=', $trimmed_phone)
            ->first();

        if ($user && Hash::check($hashedPassword, $user->password) && $user->status == "1") {
            return response()->json([
                'success' => true, 
                'user_id' => $user->id,
                'user_name' => $user->name,
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
        $user = DB::table('drivers')
        ->where('id',"=",$id)
        ->first();

        return response()->json([
            'success' => true,
            'user_name' => $user->name,
            'user_surname' => $user->surname,
            'user_email' => $user->email,
            'user_phone' => $user->phone_num,
            'user_address' => $user->address,
            'user_ic' => $user->identity_card,
            'user_license' => $user->license,
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
            $image->move(public_path('drivers'), $imageName);

            DB::table('drivers')
            ->where('id',"=",$id)
            ->update([
                'user_image' => $imageName,
                'phone_num' => $trimmed_phone,
                'name' => $name,
                'surname' => $surname,
                'address' => $address,
                'email' => $email,
                'city' => $city,
                'state' => $state,
                'postcode' => $postcode,
                'updated_at' => now()
            ]);
        }else{
            DB::table('drivers')
            ->where('id',"=",$id)
            ->update([
                'phone_num' => $trimmed_phone,
                'name' => $name,
                'surname' => $surname,
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
        $user = DB::table('drivers')
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

        DB::table('drivers')
        ->where('id',"=", $id)
        ->update([
            'password' => $hashedPassword,
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function getShipmentList($id, $status) {

        // delivery_status == '0' To Be Delivered    
        // delivery_status == '1' Delivering
        // delivery_status == '2' Delivered
        // delivery_status == '3' On Hold 
        // delivery_status == '4' Cancelled

        $shipment = DB::table('shipments')
        ->join('drivers','drivers.id',"=",'shipments.driver')
        ->join('customers','customers.id',"=",'shipments.customer')
        // ->join('driver_timelines', 'driver_timelines.shipment_id', "=", 'shipments.id')
        ->where('shipments.driver',"=",$id)
        ->where('shipments.delivery_status',"=", $status)
        ->select('shipments.*','drivers.name','customers.last_name','customers.address as c_address','drivers.address as d_address','customers.phone_no','drivers.phone_num','drivers.device_id')
        ->orderBy('shipments.created_at', 'desc')
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
        
        $customer =  DB::table('shipments')
        ->where('id',"=",$id)
        ->pluck('customer')
        ->first();
        
        $deviceToken = DB::table('customers')
        ->where('id',"=", $customer)
        ->pluck('device_token')
        ->first();

        $orderID = DB::table('shipments')
        ->where('id',"=",$id)
        ->pluck('item_code')
        ->first();

        $this->deliverOrder($deviceToken, $orderID, $customer);
        }elseif($status == '2'){
            DB::table('shipments')
            ->where('id',"=",$id)
            ->update([
                'delivery_status' => $status,
                'delivered_time' => now(),
                'updated_at' => now()
            ]);

            $customer =  DB::table('shipments')
            ->where('id',"=",$id)
            ->pluck('customer')
            ->first();
            
            $deviceToken = DB::table('customers')
            ->where('id',"=", $customer)
            ->pluck('device_token')
            ->first();
    
            $orderID = DB::table('shipments')
            ->where('id',"=",$id)
            ->pluck('item_code')
            ->first();
    
            $this->orderDelivered($deviceToken, $orderID, $customer);
        }else{
            DB::table('shipments')
            ->where('id',"=",$id)
            ->update([
                'delivery_status' => $status,
                'updated_at' => now()
            ]);
        }

        // delivery_status == '0' To Be Delivered    
        // delivery_status == '1' Delivering
        // delivery_status == '2' Delivered
        // delivery_status == '3' On Hold 
        // delivery_status == '4' Cancelled
        if ($status == 0) {
            $status = 'To Be Delivered';
        } elseif ($status == 1) {
            $status = 'Delivering';
        } elseif ($status == 2) {
            $status = 'Delivered';
        } elseif ($status == 3) {
            $status = 'On Hold';
        } elseif ($status == 4) {
            $status = 'Cancelled';
        } 
        $message = 'Order ' . $orderID . ' has updated delivery status to ' . $status;
        $redirectPath = 'https://yestrackerapps.witdevdemo.com/shipment_detail/' . $id;

        $this->saveNotification($message, 1, $redirectPath);

        broadcast(new Notification(1, $message, $redirectPath))->toOthers();

        return response()->json([
            'success' => true,
        ]);
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

    public function getDeviceID($id) {
        $device_id = DB::table('drivers')
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

        DB::table('drivers')
        ->where('id',"=",$id)
        ->update([
            'status' => $status
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function termsAndCondition(){
        return view('Driver.terms');
    }

    public function privacyPolicy(){
        return view('Driver.privacyPolicy');
    }

    public function uploadDeliveryDetails(Request $request) {
        $description = $request->input('description');
        $location = $request->input('location');
        $shipmentId = $request->input('shipmentId');
        $driverId = $request->input('driverId');
        $status = $request->input('status');
    
        // Generate a unique ID
        $id = Str::random(30);
    
        $media = $request->file('media');

        if ($media) {
            $mediaName = $id . '.' . $media->getClientOriginalExtension();
            $media->storeAs('media', $mediaName, 'public');
        } else {
            // Handle the case where media file is not present or not uploaded.
            return response()->json(['error' => 'Media file is missing.']);
        }

        if (is_null($description)) {
            $description = ''; // Set it to an empty string
        }
    
    
        // Insert data into the driver_timelines table
        DB::table('driver_timelines')
            ->insert([
                'id' => $id,
                'shipment_id' => $shipmentId,
                'driver_id' => $driverId,
                'media' => $mediaName, // Use the generated image name
                'description' => $description,
                'location' => $location,
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
        return response()->json(['success' => true]);
    }
      
    public function getDriverTimelines($shipmentId)
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

    public function getDeliveryDetails($timelineId)
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

    public function totalRating($id)
    {
        $totalRating = DB::table('shipments')
        ->where('driver', $id)
        ->where('rating', '!=', 0)
        ->avg('rating');
    
        // Format the rating to have two decimal places
        $formattedRating = number_format($totalRating, 1);
    
        return response()->json([
            'success' => true,
            'rating' => $formattedRating
        ]);
    }

    // public function registerFCMToken(Request $request)
    // {
    //     $id = "2jGXiwoIC7RiYYevdOfF2Hb3AYa7q0";
    //     $deviceToken = $request->input('device_token');

    //     DB::table('drivers')
    //     ->where('id',"=",$id)
    //     ->update([
    //         'device_token' => $deviceToken
    //     ]);

    //     $optionBuilder = new OptionsBuilder();
    //     $optionBuilder->setTimeToLive(60 * 60);
    //     $options = $optionBuilder->build();  // Build the Options object
        
    //     $notificationBuilder = new PayloadNotificationBuilder('Test Notification');
    //     $notificationBuilder->setBody('This is a test notification');
    //     $notification = $notificationBuilder->build();
        
    //     $dataBuilder = new PayloadDataBuilder();
    //     $dataBuilder->addData(['key' => 'value']);
    //     $data = $dataBuilder->build();
        
    //     FCM::sendTo($deviceToken, $options, $notification, $data);
        
    //     return response()->json(['message' => 'FCM Token registered successfully']);
    // }

    public function getNotification($id){

        $notifications = DB::table('driver_notifications')
        ->where('driver',"=",$id)
        ->select('*')
        ->orderBy('created_at','desc')
        ->get();

        return response()->json($notifications);
    }

    public function deliverOrder($deviceToken, $order_id, $customer)
    {
        $API_ACCESS_KEY = 'AAAAjKuqXlU:APA91bFPjuEQGTR2MNX8DMhyMJCCg9N8u3utIL8Xxfd2Ol3DOCAeNpG44YP-jXgKJJvVGh3TELkVEUeHAtgBTGbCA_OioDT5xvjx9CmT13PjTx6X4XjyY-BJ8wI12kFnzD9RSC2vkTtC';
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        

        $notification = [
            'title' => 'Yes Sir',
            'body' => 'Your Order #'.$order_id.' Is Delivering',
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

        DB::table('customer_notifications')
        ->insert([
            'id' => Str::random(30),
            'customer' => $customer,
            'notification_text' => 'Your Order #'.$order_id.' Is Delivering',
            'created_at' => now()
        ]);

        return response()->json(['message' => 'FCM message sent', 'response' => $result]);
    }

    public function orderDelivered($deviceToken, $order_id, $customer)
    {
        $API_ACCESS_KEY = 'AAAAjKuqXlU:APA91bFPjuEQGTR2MNX8DMhyMJCCg9N8u3utIL8Xxfd2Ol3DOCAeNpG44YP-jXgKJJvVGh3TELkVEUeHAtgBTGbCA_OioDT5xvjx9CmT13PjTx6X4XjyY-BJ8wI12kFnzD9RSC2vkTtC';
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        

        $notification = [
            'title' => 'Yes Sir',
            'body' => 'Your Order #'.$order_id.' Has Been Delivered',
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

        DB::table('customer_notifications')
        ->insert([
            'id' => Str::random(30),
            'customer' => $customer,
            'notification_text' => 'Your Order #'.$order_id.' Has Been Delivered',
            'created_at' => now()
        ]);

        return response()->json(['message' => 'FCM message sent', 'response' => $result]);
    }

    public function driverMessage(Request $request)
    {
        // Generate a unique ID
        $id = Str::random(30);

        $message = $request->input('message');
        $userId = $request->input('userId');
    
        // $media = $request->file('media');

        // if ($media) {
        //     $mediaName = $id . '.' . $media->getClientOriginalExtension();
        //     $media->storeAs('media', $mediaName, 'public');
        // } else {
        //     // Handle the case where media file is not present or not uploaded.
        //     return response()->json(['error' => 'Media file is missing.']);
        // }

        // if (is_null($description)) {
        //     $description = ''; // Set it to an empty string
        // }
    
    
        // Insert data into the driver_timelines table
        DB::table('driver_reports')
            ->insert([
                'id' => $id,
                'user_id' => $userId,
                'message' => $message,
                'media' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
        return response()->json(['success' => true]);
    }

    // public function driverMessageMedia(Request $request)
    // {
    //     try {
    //         // Generate a unique ID
    //         $id = Str::random(30);
    
    //         // Retrieve the message from the request, allowing it to be nullable
    //         $message = $request->input('message', null);
    //         $userId = $request->input('userId');
    
    //         // Check if media files are included in the request
    //         if ($request->hasFile('media')) {
    //             // Get the uploaded media files
    //             $mediaFiles = $request->file('media');
                
    //             // Initialize an empty array to store media filenames
    //             $mediaNames = [];
    
    //             // Initialize a counter for appending sequential numbers to file names
    //             $counter = 1;
    
    //             // Loop through each uploaded media file
    //             foreach ($mediaFiles as $media) {
    //                 // Generate a unique filename for the media file
    //                 $mediaName = $id . '_' . $counter . '.jpg';
                    
    //                 // Move the media file to the desired directory using Storage facade
    //                 $filePath = Storage::putFileAs('media', $media, $mediaName);
    
    //                 // Log the file name and file path
    //                 \Log::info("Uploaded file: $mediaName, Path: $filePath");
    
    //                 // Add the media filename to the array
    //                 $mediaNames[] = $mediaName;
    
    //                 // Increment the counter
    //                 $counter++;
    //             }
    
    //             // Save the media filenames as a comma-separated string
    //             $mediaString = implode('==', $mediaNames);
    
    //             // Insert data into the driver_reports table
    //             DB::table('driver_reports')->insert([
    //                 'id' => $id,
    //                 'user_id' => $userId,
    //                 'message' => $message, // Save the message (nullable)
    //                 'media' => $mediaString, // Save the media filenames
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ]);
    
    //             return response()->json(['success' => true]);
    //         } else {
    //             // If no media files are uploaded, return an error response
    //             return response()->json(['error' => 'Media files are missing.'], 400);
    //         }
    //     } catch (\Exception $e) {
    //         \Log::error('Error processing media files: ' . $e->getMessage());
    //         return response()->json(['error' => 'Internal server error.'], 500);
    //     }
    // }

    public function driverMessageMedia(Request $request)
    {
        $id = Str::random(30);
        $counter = 0;
        // Store uploaded media files
        $uploadedMedia = [];
    
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $media) {
                $counter++;
                // Determine the file extension
                $extension = $media->getClientOriginalExtension();
                // Generate a unique filename
                $fileName = $id . '_' . $counter . '.' . ($extension === 'mp4' ? $extension : 'jpg');
                // Move the uploaded file to the media directory
                if ($extension === 'mp4') {
                    $media->move(public_path('media'), $fileName);
                } else {
                    // Convert non-MP4 media to JPG format using Intervention Image
                    $image = Image::make($media);
                    $image->encode('jpg', 75); // Convert to JPG format with 75% quality
                    $image->save(public_path('media') . '/' . $fileName);
                }
                // Add the filename to the list of uploaded media
                $uploadedMedia[] = $fileName;
            }
        }
    
        // Convert the uploaded media filenames to JSON format
        $mediaJson = json_encode($uploadedMedia);
    
        // Additional data
        $message = $request->input('message', null);
        $userId = $request->input('userId');
    
        // Insert the data into the database
        DB::table('driver_reports')->insert([
            'id' => $id,
            'user_id' => $userId,
            'message' => $message, // Save the message (nullable)
            'media' => $mediaJson, // Save the media filenames
            'created_at' => now(),
        ]);
    
        return response()->json(['message' => 'Media uploaded successfully', 'media' => $uploadedMedia]);
    }

    public function driverMessageRecording(Request $request)
    {
        // Validate the incoming request if needed
        $request->validate([
            'audio' => 'required|file|mimes:audio/mpeg,mpga,mp3,wav,aac',
            'userId' => 'required', // Assuming userId is required and numeric
        ]);

        // Store the audio file
        if ($request->file('audio')->isValid()) {
            $path = $request->file('audio')->store('audio', 'public');

            // Extract the file name from the path
            $fileName = basename($path);

            // You can also save other information like user ID
            $userId = $request->input('userId');

            // Insert data into the driver_reports table
            DB::table('driver_reports')->insert([
                'id' => Str::random(30),
                'user_id' => $userId,
                'media' => $fileName, // Save only the file name
                'created_at' => now(),
            ]);

            // Return the path or any response you want
            return response()->json(['path' => $fileName]);
        }

        // If the file is not valid or cannot be stored
        return response()->json(['error' => 'Failed to store audio'], 500);
    }


    public function getDriverReports(Request $request, $id)
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

        $isDelivering = DB::table('shipments')
            ->where('driver', $id)
            ->where('delivery_status', 1)
            ->exists();

        $isDelivering = $isDelivering ? true : false;
    
        return response()->json([
            'success' => true,
            'isDelivering' => $isDelivering,
            'driverReports' => $driverReports
        ]);
    }


}

