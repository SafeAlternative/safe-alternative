<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CargusController extends Controller
{
    public function index() {
        return 'Hello from FanController';
    }

    public function login(Request $request){

        $api_url = 'https://urgentcargus.azure-api.net/api/LoginUser';
        $subscriptionKey = $request->subscriptionKey;
        $username  = $request->username;
        $password = $request->password;
    
        $response = Http::withHeaders([
            'Ocp-Apim-Subscription-Key' => $subscriptionKey,
        ])->post($api_url, [
            
            'UserName'  => $username,
            'Password' => $password
        ]);

        return trim($response,'"');
    }

    public function generateAwb() {
        return ['error' => '', 'awb'=> 'awb cargus'];
    }

    public function printAwb() {
        return ['error' => '', 'awb'=> ''];
    }

    public function deleteAwb(Request $request) {

        $barCode = $request->barCode;
        $subscriptionKey = $request->subscriptionKey;
        $token =  'Bearer '.$request->token;
        $api_url = 'https://urgentcargus.azure-api.net/api/Awbs?barCode='.$barCode;
        
        $response = Http::withHeaders([
            'Ocp-Apim-Subscription-Key' => $subscriptionKey,
            'Authorization' => $token
        ])->delete($api_url, [
            'barCode' => $barCode
        ]);

        return $response;
    }


}
