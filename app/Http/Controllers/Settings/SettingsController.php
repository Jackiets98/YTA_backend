<?php

namespace App\Http\Controllers\Settings;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller {

    // Add a constructor to apply the 'auth' middleware
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function settings()
    {
        $setting = DB::table('settings')
            ->select('settings.*')
            ->where('settings.id', '1')
            ->first();

        return view('Settings.settings', compact('setting'));
    }

    // public function update(Request $request, $setting)
    // {
    //     $data = request()->validate([
    //         'geo_distance' => 'required|regex:/^[0-9]+$/',
    //         'mileage_limit' => 'required|regex:/^[0-9]+$/',
    //         'speed_limit' => 'required|regex:/^[0-9]+$/',
    //         'battery' => 'required|regex:/^[0-9]+$/',
    //     ]);

    //     DB::table('settings')
    //         ->where('id', $setting)
    //         ->update([
    //             'geo_distance' => $data['geo_distance'],
    //             'mileage_limit' => $data['mileage_limit'],
    //             'speed_limit' => $data['speed_limit'],
    //             'battery' => $data['battery'],
    //             'updated_at' => now()
    //         ]);

    //     return redirect(route('settings'))->with('success', 'Settings updated successfully!');
    // }

    public function update(Request $request, $setting)
    {
        // Validate and update data
        $data = $request->validate([
            'speed_limit' => 'required|numeric',
            'battery' => 'required|numeric',
        ]);

        DB::table('settings')
            ->where('id', $setting)
            ->update($data);

        return response()->json(['message' => 'Data saved successfully'], 200);
    }

    public function save(Request $request, $id)
    {
        // Validate the form data
        $data = $request->validate([
            'speed_limit' => 'required|numeric',
            'battery' => 'required|numeric',
        ]);

        // Update the settings in the database
        $setting = DB::table('settings')
            ->where('id', $id)
            ->update([
                'speed_limit' => $data['speed_limit'],
                'battery' => $data['battery'],
                'updated_at' => now(),
            ]);

        // Redirect back to the settings page with a success message
        return redirect(route('settings'))->with('success', 'Settings updated successfully!');
    }

}
