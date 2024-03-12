<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Driver\DriverController;
use App\Http\Controllers\Shipment\ShipmentController;
use App\Http\Controllers\Vehicle\VehicleController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Controllers\Notification\NotificationController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route for displaying the home page
Route::get('/', [HomeController::class, 'index']);
Route::get('/getChartData', [HomeController::class, 'getChartData']);
Route::post('/delete-noti/{id}', [HomeController::class, 'deleteNotification']);

// Auth Routes
Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AdminLoginController::class, 'login']);
Route::get('/logout', [AdminLoginController::class, 'logout'])->name('logout');

//Admin Routes
Route::get('/admins', [AdminController::class, 'index']);
Route::post('/admins/storeAdmin', [AdminController::class, 'storeAdmin']);
Route::post('/admins/storeSubAdmin', [AdminController::class, 'storeSubAdmin']);
Route::get('/admins/{admin}', [AdminController::class, 'show']);
Route::get('/admins/{admin}/edit', [AdminController::class, 'edit']);
Route::patch('/admins/{admin}', [AdminController::class, 'update']);
Route::get('/admins/{admin}/{status}', [AdminController::class, 'updateStatus'])->name('admin.status.update');

//Customer Routes
Route::get('/customers', [CustomerController::class, 'index']);
Route::get('/customerList', [CustomerController::class, 'customerList']);
Route::post('/customers', [CustomerController::class, 'store']);
Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customer.show');
Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit']);
Route::patch('/customers/{customer}', [CustomerController::class, 'update']);
Route::get('/customers/{id}/{status_code}', [CustomerController::class, 'updateStatus'])->name('customer.status.update');
Route::get('/customers-checkShipment/{id}', [CustomerController::class, 'checkShipment'])->name('checkShipment');


//Driver Routes
Route::get('/driverlists', [DriverController::class, 'index']);
Route::post('/addDrivers', [DriverController::class, 'addDriver']);
Route::get('/driver_detail/{id}', [DriverController::class, 'driverDetail']);
Route::get('/driver_detail/{id}/{status_code}', [DriverController::class, 'updateStatus'])->name('driver.status.update');
Route::post('/driverUpdate/{id}', [DriverController::class, 'updateDriverDetails']);


//Shipment Routes
Route::get('/shipments', [ShipmentController::class, 'index']);
Route::get('/createShipment', [ShipmentController::class, 'newShipmentPage']);
Route::post('/addNewShipment', [ShipmentController::class, 'createNewShipment']);
Route::get('/shipment_detail/{id}', [ShipmentController::class, 'shipmentDetail']);
Route::post('/shipmentUpdate/{id}', [ShipmentController::class, 'updateShipmentDetails']);
Route::get('/get-gps-data/{imei}', [ShipmentController::class, 'getGPSData']);
Route::get('/get-gps-data-json/{imei}', [ShipmentController::class, 'getBackGPSData']);
Route::get('/getTrackingData/{imei}/{startTime}/{endTime}', [ShipmentController::class, 'getTrackingData']);
Route::get('/get-location-data/{imei}', [ShipmentController::class, 'getLocationData'])->name('get-location-data');
Route::post('/save-geofence-data/{imei}', [ShipmentController::class, 'saveGeofenceData']);
Route::get('/delete-geofence-data/{imei}/{fenceId}', [ShipmentController::class, 'deleteGeofenceData']);

//Vehicle Routes
Route::get('/vehicles', [VehicleController::class, 'index']);
Route::get('/createVehicle', [VehicleController::class, 'newVehiclePage']);
Route::post('/addNewVehicle', [VehicleController::class, 'createNewVehicle']);
Route::get('/vehicle_detail/{id}', [VehicleController::class, 'vehicleDetail']);
Route::post('/vehicleUpdate/{id}', [VehicleController::class, 'updateVehicleDetails']);
Route::get('/vehicle_detail/{imei}', [VehicleController::class, 'vehicleDetail']);
Route::get('/get-engine-data/{imei}', [VehicleController::class, 'getEngineData']);
Route::get('/updateEngineStatus/{imei}', [VehicleController::class, 'updateEngineStatus'])->name('updateEngineStatus');
Route::get('/getEngineResponse/{uuid}', [VehicleController::class, 'getEngineResponse'])->name('getEngineResponse');
Route::get('/get-vehicle-data/{imei}', [VehicleController::class, 'getGPSData']);
Route::get('/get-vehicle-dashcam/{id}', [VehicleController::class, 'getDashcam']);
Route::get('/updateMileage', [VehicleController::class, 'saveMileage']);

//Report Routes
Route::get('/reports', [ReportController::class, 'index']);
Route::get('/newReport', [ReportController::class, 'addReport']);
Route::post('/addNewReport', [ReportController::class, 'createReport']);

// Settings Routes
Route::get('/settings', [SettingsController::class, 'settings'])->name('settings');
Route::patch('/settings/{id}/save', [SettingsController::class, 'save'])->name('settings.save');
// Route::patch('/settings/{setting}', [SettingsController::class, 'update']);

// Notification Routes
Route::get('/geofence-alert', [NotificationController::class, 'geofenceAlert'])->withoutMiddleware(['auth']);
Route::get('/speed-alert', [NotificationController::class, 'speedAlert'])->withoutMiddleware(['auth']);
// Route::post('/save-notification', [NotificationController::class, 'saveNotification'])->withoutMiddleware(['auth']);
Route::get('/get-notifications', [NotificationController::class, 'getNotifications'])->withoutMiddleware(['auth']);
// Add this route in your routes file
Route::post('/mark-notifications-as-viewed', [NotificationController::class, 'markAsViewed']);







