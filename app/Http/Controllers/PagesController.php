<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    //
    public function index(){
        $title = 'Welcome to PatrickGodbey';
        // return view('pages.index', compact('title'));
        return view('pages.index')->with('title', $title);

    }
    public function about(){
        $title = 'About';
        return view('pages.about')->with('title', $title);

    }
    public function services(){
        $title = 'Servicesh';
        return view('pages.services')->with('title', $title);

    }
}
