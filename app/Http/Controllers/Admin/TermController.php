<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
    /**
     * indexアクション
     * 会社概要ページ
     */

    public function index()
    {
        $term = Term::latest()->first();

        return view('admin.terms.index', compact('term'));
    }

    /**
     * editアクション
     * 会社概要編集ページ
     */
    public function edit()
    {
        $term = Term::latest()->first();

        return view('admin.terms.edit', compact('term'));
    }

    public function update(Request $request, Term $term) {
        $request->validate([
            'content' => 'required',
        ]);

        $term->content = $request->input('content');
        $term->update();

        return redirect()->route('admin.terms.index', $term)->with('flash_message','利用規約を編集しました。');
    }
}
