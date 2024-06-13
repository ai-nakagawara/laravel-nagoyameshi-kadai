<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        if ($keyword !== null) {
            $users = User::where('name', 'like', "%{$keyword}%")->orwhere('kana', 'like', "%{$keyword}%")->paginate(15);
            $total = User::where('name', 'like', "%{$keyword}%")->orwhere('kana', 'like', "%{$keyword}%")->count();

        } else {
            $users = User::paginate(15);
            $total = User::all()->count();
        }

        return view('admin.users.index', compact('keyword','users', 'total'));
    }

    public function show($id) {
        $user = User::find($id);
        $params = [
            'user' => $user
        ];

        return view('admin.users.show',$params , compact('user'));
    }
}
