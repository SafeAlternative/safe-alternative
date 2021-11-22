<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CurieriController extends Controller
{
    public function index()
    {
        return view('curieri');
    }

    public function fan()
    {
        return view('curieri.fan');
    }

    public function cargus()
    {
        return view('curieri.cargus');
    }

    public function nemo()
    {
        return view('curieri.nemo');
    }
}
