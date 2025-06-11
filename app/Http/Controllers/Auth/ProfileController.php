<?php
namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function showCompleteForm()
    {
        return view('auth.complete-profile');
    }

    public function complete(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'second_name' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $user->first_name = $request->first_name;
        $user->second_name = $request->second_name;
        $user->save();

        return redirect()->route('map');
    }
}
