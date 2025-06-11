<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function store(Request $request, Location $location)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error_review', 'Будь ласка, виправте помилки у формі відгуку.');
        }

        $review = new Review();
        $review->location_id = $location->id;
        $review->rating = $request->input('rating');
        $review->review_text = $request->input('review_text');

        // user_id встановлюється тільки для авторизованих користувачів
        // Middleware 'auth' на маршруті вже гарантує, що користувач авторизований
        $review->user_id = Auth::id();
        // $review->name = Auth::user()->first_name . ' ' . Auth::user()->second_name; // Видалено

        $review->save();

        return redirect()->back()->with('success_review', 'Ваш відгук було успішно додано!');
    }
}
