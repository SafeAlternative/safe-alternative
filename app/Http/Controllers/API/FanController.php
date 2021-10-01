<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FanController extends Controller
{
    public function index() {
        return 'Hello from FanController';
    }

    public function generateAwb() {
        return ['error' => '', 'awb'=> ''];
    }

    public function printAwb() {
        return ['error' => '', 'awb'=> ''];
    }


}
