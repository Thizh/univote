<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function handleLogin(Request $request)
    {
        // Default credentials
        $defaultUsername = 'admin';
        $defaultPassword = 'password123';

        if ($request->username === $defaultUsername && $request->password === $defaultPassword) {
            Session::put('admin_logged_in', true); // Set session to indicate login
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('adminlogin')->with('error', 'Invalid username or password!');
    }

    public function dashboard()
    {
        if (!Session::get('admin_logged_in')) {
            return redirect()->route('adminlogin')->with('error', 'Please log in first.');
        }
        return view('dashboard');
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function logout()
    {
        Session::flush(); // Clear all sessions
        return redirect()->route('adminlogin')->with('message', 'Logged out successfully!');
    }

    public function candidates()
    {
    return view('candidates'); // Ensure this view exists
    }
    
    public function voters()
{
    return view('voters'); // Ensure this view exists
}

public function polling()
{
    return view('polling'); // Ensure this view exists
}

public function results()
{
    return view('results'); // Ensure this view exists
}

}
