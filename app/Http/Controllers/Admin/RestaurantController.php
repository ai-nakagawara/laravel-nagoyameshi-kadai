<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\RegularHoliday;
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
        $categories = Category::all();
        $regular_holidays = RegularHoliday::all();

        return view('admin.restaurants.create', compact('restaurant','categories', 'regular_holidays'));
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
            'lowest_price' => 'required|numeric|min:0|lte:highest_price',
            'highest_price' => 'required|numeric|min:0|gte:lowest_price',
            'postal_code' => 'required|digits:7',
            'address' => 'required',
            'opening_time' => 'required|before:closing_time',
            'closing_time' => 'required|after:opening_time',
            'seating_capacity' => 'required|numeric|min:0'
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

        $category_ids = array_filter($request->input('category_ids'));
        $restaurant->categories()->sync($category_ids);

        if ($request !== null) {
            $regular_holiday_ids = $request->input('regular_holiday_ids');
        } else {
            $regular_holiday_ids = array_filter($request->input('regular_holiday_ids'));
        }
        $restaurant->regular_holidays()->sync($regular_holiday_ids);

        return redirect()->route('admin.restaurants.index', $restaurant)->with('flash_message','店舗を登録しました。');
    }

    public function edit($id) {
        $restaurant = Restaurant::find($id);
        $params = [
            'resraurant' => $restaurant
        ];

        $categories = Category::all();
        $category_ids = $restaurant->categories->pluck('id')->toArray();

        $regular_holidays = RegularHoliday::all();
        $regular_holiday_ids = $restaurant->regular_holidays->pluck('id')->toArray();

        return view('admin.restaurants.edit',$params,
            compact('restaurant','categories', 'category_ids', 'regular_holidays', 'regular_holiday_ids'));
    }

    public function update(Request $request, Restaurant $restaurant) {
        $request->validate([
            'name' => 'required',
            'image' => 'image|max:2048',
            'description' => 'required',
            'lowest_price' => 'required|numeric|min:0|lt:highest_price',
            'highest_price' => 'required|numeric|min:0|gte:lowest_price',
            'postal_code' => 'required|digits:7',
            'address' => 'required',
            'opening_time' => 'required|before:closing_time',
            'closing_time' => 'required|after:opening_time',
            'seating_capacity' => 'required|numeric|min:0',
        ]);

        $restaurant->name = $request->input('name');
        $restaurant->description = $request->input('description');
        $restaurant->lowest_price = $request->input('lowest_price');
        $restaurant->highest_price = $request->input('highest_price');
        $restaurant->postal_code = $request->input('postal_code');
        $restaurant->address = $request->input('address');
        $restaurant->opening_time = $request->input('opening_time');
        $restaurant->closing_time = $request->input('closing_time');

        if ($request !== null) {
            $regular_holiday_ids = $request->input('regular_holiday_ids');
        } else {
            $regular_holiday_ids = array_filter($request->input('regular_holiday_ids'));
        }

        $restaurant->regular_holidays()->sync($regular_holiday_ids);

        $restaurant->seating_capacity = $request->input('seating_capacity');
        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('public/restaurants');
            $restaurant->image = basename($image_path);
        } else {
            $restaurant->image = basename('');
        }
        $restaurant->update();

        $category_ids = array_filter($request->input('category_ids'));
        $restaurant->categories()->sync($category_ids);

        return redirect()->route('admin.restaurants.index', $restaurant)->with('flash_message','店舗を編集しました。');
    }

    public function destroy($id) {
        $restaurant = Restaurant::find($id);

        $restaurant->delete();

        return redirect()->route('admin.restaurants.index')->with('flash_message', '店舗を削除しました。');
    }
}
