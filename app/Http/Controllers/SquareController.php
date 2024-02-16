<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SquareController extends Controller
{
    public function index(Request $request)
    {
        $total = $request->query('total');

        // Your controller logic here

        return view('square.index', compact('total'));
    }
}