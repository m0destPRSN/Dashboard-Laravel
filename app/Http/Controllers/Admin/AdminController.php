<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // Ensure this model exists

class AdminController extends Controller
{
    public function index()
    {
        $users = User::all(); // Fetch all users
        return view('admin.dashboard', compact('users')); // Pass users to the view
    }
}
