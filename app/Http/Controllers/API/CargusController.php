<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CargusController extends Controller
{
    public function index() {
        return 'Hello from FanController';
    }

    public function generateAwb() {
        return ['error' => '', 'awb'=> 'awb cargus'];
    }

    public function printAwb() {
        return ['error' => '', 'awb'=> ''];
    }

}
