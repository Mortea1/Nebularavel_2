<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function help()
    {
        return view('help');
    }

    public function register()
    {
        return view('auth.register');
    }
}
