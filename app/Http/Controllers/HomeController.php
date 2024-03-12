<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HomeController extends Controller
{

    // Add a constructor to apply the 'auth' middleware
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $userId = Auth::id();

        $userName = DB::table('admins')
            ->where('id', $userId)
            ->select(DB::raw('CONCAT(first_name, " ", last_name) as full_name'))
            ->value('full_name');
        
        $customers = DB::table('customers')
            ->join('shipments', 'customers.id', '=', 'shipments.customer')
            ->select('customers.id', 'customers.first_name', 'customers.last_name', 'customers.user_image', DB::raw('COUNT(shipments.id) as total_shipments'))
            ->where('customers.status', 1)
            ->groupBy('customers.id', 'customers.first_name', 'customers.last_name', 'customers.user_image')
            ->orderByDesc('total_shipments')
            ->take(3) // Take only 3 records
            ->get();

        $drivers = DB::table('drivers')
            ->join('shipments', 'drivers.id', '=', 'shipments.driver')
            ->select('drivers.id', 'drivers.name', 'drivers.surname', 'drivers.user_image', DB::raw('COUNT(shipments.id) as total_shipments'))
            ->where('drivers.status', 1)
            ->where('shipments.delivery_status', 2)
            ->groupBy('drivers.id', 'drivers.name', 'drivers.surname', 'drivers.user_image')
            ->orderByDesc('total_shipments')
            ->take(3) // Take only 3 records
            ->get();


        $vehicles = DB::table('vehicles')
            ->select('*')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $totalPendingShipments = DB::table('shipments')
            ->where('delivery_status', 0)
            ->count();

        $totalDeliveringShipments = DB::table('shipments')
            ->where('delivery_status', 1)
            ->count();

        $totalDeliveredShipments = DB::table('shipments')
            ->where('delivery_status', 2)
            ->count();

        // Fetch all unique years from the shipments table, ordered in descending order
        $uniqueYears = DB::table('shipments')
            ->select(DB::raw('YEAR(created_at) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        // Add "Select Year" as the default option
        $uniqueYears = array_merge(['Select Year'], $uniqueYears);

        // Add the current year if it's not already in the list
        $currentYear = now()->year;
        if (!in_array($currentYear, $uniqueYears)) {
            array_unshift($uniqueYears, $currentYear);
        }

        // Fetch monthly and yearly shipment counts for the current year
        $monthlyCounts = DB::table('shipments')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as count')
            )
            ->whereYear('created_at', '=', $currentYear)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        // Prepare data for the bar chart
        $labels = [];
        $data = [];

        foreach ($monthlyCounts as $count) {
            // Convert numeric month to 3-character text format
            $monthText = date('M', mktime(0, 0, 0, $count->month, 1, 2000));

            $labels[] = $monthText;
            $data[] = $count->count;
        }

        $notifications = DB::table('admin_notifications')
        ->select('*')
        ->get();

        return view('home', compact('userName', 'totalPendingShipments', 'totalDeliveringShipments', 'totalDeliveredShipments', 'labels', 'data', 'uniqueYears', 'customers', 'drivers', 'vehicles', 'notifications'));
    }

    public function getChartData(Request $request)
    {
        $selectedYear = $request->input('year');

        // Fetch monthly and yearly shipment counts for the selected year
        $monthlyCounts = DB::table('shipments')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as count')
            )
            ->whereYear('created_at', '=', $selectedYear)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        // Prepare data for the chart
        $labels = [];
        $data = [];

        foreach ($monthlyCounts as $count) {
            // Convert numeric month to 3-character text format
            $monthText = date('M', mktime(0, 0, 0, $count->month, 1, 2000));

            $labels[] = $monthText;
            $data[] = $count->count;
        }

        // Return the data as JSON
        return response()->json(['labels' => $labels, 'data' => $data]);
    }

    // public function deleteNotification($id)
    // {
    //     try {
    //         // Assuming 'admin_notifications' is the table name
    //         DB::table('admin_notifications')->where('id', $id)->delete();

    //         return redirect()->back()->with('success', 'Notification deleted successfully');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Error deleting notification');
    //     }
    // }

}

