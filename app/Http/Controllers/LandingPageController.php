<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        return view('landing.index');
    }

    public function about()
    {
        return view('landing.about');
    }

    public function features()
    {
        return view('landing.features');
    }

    public function contact()
    {
        return view('landing.contact');
    }
}