<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function index()
    {
        $term = Term::latest()->first();

    return view('terms.index', compact('term'));
    }

}
