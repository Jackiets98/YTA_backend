<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class AdminLoginController extends Controller
{

    public function showLoginForm()
    {
        return view('auth.login'); 
    }

    public function login(Request $request)
    {
        // Validate the login request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Determine whether to remember the user or not
        $remember = $request->has('remember') ? true : false;

        // Attempt to authenticate the user using the 'web' guard
        $credentials = ['email' => $request->email, 'password' => $request->password];

        // Check if the admin has a status of 1
        $admin = Auth::guard('web')->getProvider()->retrieveByCredentials($credentials);

        // Attempt to authenticate the user using the 'web' guard
        if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
            if($admin && $admin->status == 1) {
                // Authentication successful, redirect to the root URL
                return redirect('/')->with('info', 'Welcome Back! ');
            } else{
                return back()->withErrors(['email' => 'This account has been deactivated. Contact admin for assistance.'])->withInput($request->only('email', 'remember'));  
            }
        } else {
            // Authentication failed, redirect back to login with errors
            return back()->withErrors(['email' => 'Invalid email or password.'])->withInput($request->only('email', 'remember'));
        }
    }
public function logout()
    {
        Auth::guard('web')->logout(); // Log the user out
        return redirect('/'); // Redirect to the desired page after logout
    }
    // function login(){
    //     return view('Auth/login');
    // }

    // function loginPost(Request $request){
    //     $request->validate([
    //         'email' => 'required',
    //         'password' => 'required'
    //     ]);

    //     $credentials = $request->only('email', 'password');
    //     if(Auth::attempt($credentials)){
    //         return redirect()->intended(route('home'));
    //     }
    //     return redirect(route('login'))->with("error", "Login details are not valid");
    // }

    // function logout(){
    //     Session::flush();
    //     Auth::logout();
    //     return redirect(route('login'));
    // }
    // public function __construct()
    // {
    //     $this->middleware('guest:admin')->except('logout');
    // }

    // // Show login page
    // public function showLoginForm(Request $request)
    // {
    //     return view('auth.login');
    // }

    // // Login function validation
    // public function login(Request $request)
    // {
    //     // validate the email and password
    //     $this->validate($request, [
    //         'email'   => 'required|email',
    //         'password' => 'required|min:8'
    //     ]);

    //     // check if member exist
    //     $admin = DB::connection('mysql_main')
    //     ->table('admins')
    //     ->select('admins.*')
    //     ->where('admins.email', $request->email)
    //     ->first();

    //     if($admin){
    //         if (Auth::guard('admin')->attempt($request->only(['email','password']), $request->get('remember'))){
    //             $request->session()->regenerate();
    //             return redirect()->intended('/');
    //         }
    //         else {
    //             // if password is wrong, redirect back to login page & display an error message
    //             return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors(['password' => ['These credentials do not match our records.']]);
    //         }
    //     }
    //     else {
    //         // if member doesnt exist
    //         return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors(['email' => ['This account does not exist.']]);
    //     }
    // }

    // // Set user to logout and route back to homepage
    // public function logout(Request $request)
    // {
    //     // Auth::logout();
    //     Auth::guard('admin')->logout();
    //     return redirect()->route('home');
    // }
}
