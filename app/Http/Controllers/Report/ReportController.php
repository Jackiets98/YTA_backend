<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use PDF;
use GuzzleHttp\Client;

class ReportController extends Controller
{
    
    public function addReport() {
    
        return view('report.new_report');
    }
    
    public function createReport(Request $request) {
        $text = $request->input('reportDescription');
    
        // Handling Media Uploads
        $totalSize = 0; // Variable to store total size of uploaded files
    
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                // Calculate size of each file and add to total size
                $totalSize += $file->getSize();
    
                // Validate file extension
                $extension = $file->getClientOriginalExtension();
                if (!in_array($extension, ['jpeg', 'jpg', 'png', 'pdf', 'mp4'])) {
                    return Redirect::back()->with('error', 'Invalid file format. Only jpeg, jpg, png, pdf, and mp4 formats are allowed.')->withInput();
                }
            }
    
            // Check if total size exceeds the limit (10MB = 10 * 1024 * 1024 bytes)
            if ($totalSize > (10 * 1024 * 1024)) {
                return Redirect::back()->with('error', 'Total size of uploaded media files cannot exceed 10MB')->withInput();
            }
    
            $mediaPaths = [];
    
            foreach ($request->file('media') as $file) {
                $fileName = $file->getClientOriginalName();
                $filePath = $file->storeAs('media', $fileName, 'public');
                $mediaPaths[] = $filePath;
            }
        } else {
            $mediaPaths = null;
        }
    
        // Inserting into the Database
        DB::table('admin_reports')
            ->insert([
                'id' => Str::random(30),
                'text' => $text,
                'media' => json_encode($mediaPaths), // Convert media paths to JSON
                'created_at' => now()
            ]);
    
        return Redirect::back()->with('success', 'New Report Added Successfully');
    }
    

    public function index()
    {
        $reports = DB::table('admin_reports')
        ->select('*')
        ->get();

        return view('report.index',compact('reports'));
    }

}
