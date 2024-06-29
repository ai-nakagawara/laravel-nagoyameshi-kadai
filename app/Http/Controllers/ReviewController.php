<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use App\Models\Restaurant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;


class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Restaurant $restaurant, Request $request)
    {
        $sorts = [
            '掲載日が新しい順' => 'created_at desc',
        ];
        $sort_query = [];
        $sorted = "created_at desc";

        if ($request->has('select_sort')) {
            $slices = explode(' ', $request->input('select_sort'));
            $sort_query[$slices[0]] = $slices[1];
            $sorted = $request->input('select_sort');
        }

        if  (! $request->user()?->subscribed('premium_plan')) {
            $reviews = Review::whereHas('restaurant', function($query) use ($restaurant){
                $query->where('restaurants.id', $restaurant->id);
                })->sortable($sort_query)->orderBy('created_at', 'desc')->limit(3)->get();
        } else {
            $reviews = Review::whereHas('restaurant', function($query) use ($restaurant){
                $query->where('restaurants.id', $restaurant->id);
                })->sortable($sort_query)->orderBy('created_at', 'desc')->paginate(5);
        }

        return view('reviews.index',$restaurant, compact('restaurant', 'reviews'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Restaurant $restaurant)
    {
        return view('reviews.create', compact('restaurant'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'score' => ['required', 'integer', 'in:1,2,3,4,5'],
            'content' => ['required'],
        ]);

        $reviews = new Review();
        $reviews->score = $request->input('score');
        $reviews->content = $request->input('content');
        $reviews->restaurant_id = $restaurant->id;
        $reviews->user_id = Auth::user()->id;
        $reviews->save();

        return redirect()->route('restaurants.reviews.index', $restaurant)->with('flash_message', 'レビューを投稿しました。');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Restaurant $restaurant, Review $review)
    {
        // $user=Auth::user();

        if ($review->user_id !== Auth::id()) {
            return redirect()->route('restaurans.reviews.index', $restaurant)->with('error_message', '不正なアクセスです。');
        } else {
            return view('reviews.edit',[$restaurant, $review], compact('restaurant', 'review'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Restaurant $restaurant, Review $review)
    {
        $request->validate([
            'score' => ['required', 'integer', 'in:1,2,3,4,5'],
            'content' => ['required'],
        ]);

        if ($review->user_id !== Auth::id()) {
            return redirect()->route('restaurants.reviews.index', $restaurant)->with('error_message', '不正なアクセスです。');
        } else {

            $review->score = $request->input('score');
            $review->content = $request->input('content');
            $review->restaurant_id = $restaurant->id;
            $review->user_id = Auth::user()->id;
            $review->save();

            return redirect()->route('restaurants.reviews.index', $restaurant)->with('flash_message', 'レビューを編集しました。');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Restaurant $restaurant, Review $review )
    {
        if ($review->user_id !== Auth::id()) {
            return redirect()->route('restaurants.reviews.index', $restaurant)->with('error_message', '不正なアクセスです。');
        } else {
            $review->delete();

            return redirect()->route('restaurants.reviews.index', $restaurant)->with('flash_message', 'レビューを削除しました。');
        }
    }
}
