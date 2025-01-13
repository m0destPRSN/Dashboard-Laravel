<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::whereNotNull('phone_verified_at')
            ->whereNotNull('first_name')
            ->whereNotNull('second_name')
            ->where('role', 'user')
            ->get();
        return view('admin.dashboard', compact('users'));
    }
}
