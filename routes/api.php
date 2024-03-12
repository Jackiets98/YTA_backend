<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Flutter\AppDriverController;
use App\Http\Controllers\Flutter\AppUserController;
use App\Http\Controllers\Shipment\ShipmentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Drivers Route
Route::post('/createDriver', [AppDriverController::class,'register']);
Route::post('/driverLogin', [AppDriverController::class,'login']);
Route::get('/driverDetail/{id}', [AppDriverController::class,'profilePage']);
Route::get('/getPassword/{id}', [AppDriverController::class,'getPassword']);
Route::post('/updateDriver/{id}', [AppDriverController::class,'updateProfile']);
Route::post('/updatePassword/{id}', [AppDriverController::class,'updatePassword']);
Route::get('/shipmentList/{id}/{status}', [AppDriverController::class,'getShipmentList']); 
Route::post('/updateStatusForDelivery/{id}', [AppDriverController::class,'updateStatusForDelivery']);
Route::get('/getDeviceID/{id}', [AppDriverController::class,'getDeviceID']); 
Route::post('/disableAccount/{id}', [AppDriverController::class,'disableAccount']); 
Route::get('/PrivacyPolicy', [AppDriverController::class,'privacyPolicy']); 
Route::get('/TermsAndCondition', [AppDriverController::class,'termsAndCondition']);
Route::post('/uploadDeliveryDetails', [AppDriverController::class,'uploadDeliveryDetails']);
Route::get('/driverTimelines/{shipmentId}', [AppDriverController::class,'getDriverTimelines']);
Route::get('/viewDeliveryDetails/{timelineId}', [AppDriverController::class,'getDeliveryDetails']);
Route::get('/totalRating/{id}', [AppDriverController::class, 'totalRating']);
Route::get('/getNotification/{id}', [AppDriverController::class, 'getNotification']);
Route::post('/driverMessage', [AppDriverController::class, 'driverMessage']);
Route::post('/driverMessageMedia', [AppDriverController::class, 'driverMessageMedia']);
Route::post('/driverMessageRecording', [AppDriverController::class, 'driverMessageRecording']);
Route::get('/getDriverReports/{id}', [AppDriverController::class, 'getDriverReports']);

// Route::post('/test/{deviceToken}/{orderID}/{Driver}', [AppDriverController::class, 'deliverOrder']);

//Users Route
Route::post('/userLogin', [AppUserController::class,'login']);
Route::get('/userDetail/{id}', [AppUserController::class,'profilePage']);
Route::get('/getPassword/{id}', [AppUserController::class,'getPassword']);
Route::post('/updateUser/{id}', [AppUserController::class,'updateProfile']);
Route::post('/updatePassword/{id}', [AppUserController::class,'updatePassword']);
Route::get('/getUserDeviceID/{id}', [AppUserController::class,'getDeviceID']);
Route::post('/disableUserAccount/{id}', [AppUserController::class,'disableAccount']);  
Route::get('/CPrivacyPolicy', [AppUserController::class,'privacyPolicy']); 
Route::get('/CTermsAndCondition', [AppUserController::class,'termsAndCondition']); 
Route::get('/orderTimelines/{shipmentId}', [AppUserController::class,'getOrderTimelines']);
Route::get('/viewOrderDetails/{timelineId}', [AppUserController::class,'getOrderDetails']);
Route::get('/shipmentCustList/{id}', [AppUserController::class,'getShipmentList']); 
Route::get('/vehicleCustDetails/{id}', [AppUserController::class,'getVehicleDetails']); 
Route::get('/shipmentCustDetails/{id}', [AppUserController::class,'getShipmentDetails']); 
Route::get('/CAdminReports', [AppUserController::class,'getAdminReports']);
Route::get('/CAdminReports/more', [AppUserController::class, 'loadMoreAdminReports']);
Route::get('/get-gps-data-json/{imei}', [AppUserController::class, 'getBackGPSData']);
Route::post('/store-rating/{id}', [AppUserController::class, 'storeRating']);
Route::get('/get-rating/{id}', [AppUserController::class, 'getUserRating']);
Route::post('/register-fcm-token', [AppUserController::class, 'registerFCMToken']);
Route::get('/getCustNotification/{id}', [AppUserController::class, 'getNotification']);
Route::get('/getDeviceList', [AppUserController::class, 'deviceList']);
Route::post('/addAppOnlineStatus/{id}', [AppUserController::class, 'addAppOnlineStatus']);
Route::get('/user/getDriverReports', [AppUserController::class, 'getDriverReports']);
