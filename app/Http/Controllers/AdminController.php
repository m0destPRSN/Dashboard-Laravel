<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
