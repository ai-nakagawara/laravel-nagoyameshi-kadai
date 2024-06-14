<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;

class RestaurantController extends Controller
{
    /**
     * indexアクション
     * 店舗一覧ページ
     */

    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        if ($keyword !== null) {
            $restaurants = Restaurant::where('name', 'like', "%{$keyword}%")->paginate(15);
            $total = Restaurant::where('name', 'like', "%{$keyword}%")->count();

        } else {
            $restaurants = Restaurant::paginate(15);
            $total = Restaurant::all()->count();
        }

        return view('admin.restaurants.index', compact('keyword','restaurants', 'total'));
    }

    /**
     * showアクション
     * 店舗詳細ページ
     */

    public function show($id)
    {
        $restaurant = Restaurant::find($id);
        $params = [
            'resraurant' => $restaurant
        ];

        return view('admin.restaurants.show',$params , compact('restaurant'));
    }

    /**
     * createアクション
     * 店舗登録ページ
     */

    public function create()
    {
        $restaurant = Restaurant::all();

        return view('admin.restaurants.create', compact('restaurant'));
    }

    /**
     * storeアクション
     * 店舗登録機能
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'image|max:2048',
            'description' => 'required',
            'lowest_price' => 'required|integer|min:0|lt:highest_price',
            'highest_price' => 'required|integer|min:0|gte:lowest_price',
            'postal_code' => 'required|integer|digits:7',
            'address' => 'required',
            'opening_time' => 'required|before:closing_time',
            'closing_time' => 'required|after:opening_time',
            'seating_capacity' => 'required|integer|min:0',
        ]);

        $restaurant = new Restaurant();
        $restaurant->name = $request->input('name');
        $restaurant->description = $request->input('description');
        $restaurant->lowest_price = $request->input('lowest_price');
        $restaurant->highest_price = $request->input('highest_price');
        $restaurant->postal_code = $request->input('postal_code');
        $restaurant->address = $request->input('address');
        $restaurant->opening_time = $request->input('opening_time');
        $restaurant->closing_time = $request->input('closing_time');
        $restaurant->seating_capacity = $request->input('seating_capacity');
        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('public/restaurants');
            $restaurant->image = basename($image_path);
        } else {
            $restaurant->image = basename('');
        }
        $restaurant->save();

        return redirect()->route('admin.restaurants.index')->with('flash_message','店舗を登録しました。');
    }

    public function edit($id) {
        $restaurant = Restaurant::find($id);
        $params = [
            'resraurant' => $restaurant
        ];

        return view('admin.restaurants.edit',$params, compact('restaurant'));
    }

    public function update(Request $request, Restaurant $restaurant) {
        $request->validate([
            'name' => 'required',
            'image' => 'image|max:2048',
            'description' => 'required',
            'lowest_price' => 'required|integer|min:0|lt:highest_price',
            'highest_price' => 'required|integer|min:0|gte:lowest_price',
            'postal_code' => 'required|integer|digits:7',
            'address' => 'required',
            'opening_time' => 'required|before:closing_time',
            'closing_time' => 'required|after:opening_time',
            'seating_capacity' => 'required|integer|min:0',
        ]);

        $restaurant->name = $request->input('name');
        $restaurant->description = $request->input('description');
        $restaurant->lowest_price = $request->input('lowest_price');
        $restaurant->highest_price = $request->input('highest_price');
        $restaurant->postal_code = $request->input('postal_code');
        $restaurant->address = $request->input('address');
        $restaurant->opening_time = $request->input('opening_time');
        $restaurant->closing_time = $request->input('closing_time');
        $restaurant->seating_capacity = $request->input('seating_capacity');
        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('public/restaurants');
            $restaurant->image = basename($image_path);
        } else {
            $restaurant->image = basename('');
        }
        $restaurant->update();

        return redirect()->route('admin.restaurants.index', $restaurant)->with('flash_message','店舗を編集しました。');
    }

    public function destroy($id) {
        $restaurant = Restaurant::find($id);

        $restaurant->delete();

        return redirect()->route('admin.restaurants.index')->with('flash_message', '店舗を削除しました。');
    }
}
