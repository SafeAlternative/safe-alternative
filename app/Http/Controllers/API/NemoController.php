<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NemoController extends Controller
{
    public function index() {
        return 'Hello from FanController';
    }

    ////////////////////////////////////////////////////////////
    // LOGIN  //////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////
    public function validateAuth(Request $request){

        $api_url = 'https://app.nemoexpress.ro/nemo/API/list_services';
        $api_key = $request->api_key;
        
    
        $response = Http::get($api_url, [
            
            'api_key'  => $api_key,
        ]);


        if($response->getStatusCode()!=200) {
            return response([
                'success'   => 0,
                'message'   => 'Eroare la logare'
                
                
            ], 200);

        }

        else {

            $response = json_decode($response);
            if (isset($response->error)){

                return response([
                    'success'   => 0,
                    'message'   => 'Eroare la logare'
                    
                    
                ], 200);

            }
            return response([
                'success'   => 1,
                'message'   => 'Autentificare reusita'
                
            ], 200);
        }
        
    }

    ////////////////////////////////////////////////////////////
    // GENERARE AWB ////////////////////////////////////////////
    ////////////////////////////////////////////////////////////
    public function generateAwb(Request $request) {
        
        $api_key = $request->api_key;
        $api_url = 'https://app.nemoexpress.ro/nemo/API/create_awb?api_key='.$api_key;

        $awb['type']            = $request->type;
        $awb['service_type']    = $request->service_type;
        $awb['cnt']             = $request->cnt;
        $awb['retur']           = $request->retur;
        $awb['retur_type']      = $request->retur_type;
        $awb['ramburs']         = $request->ramburs;
        $awb['ramburs_type']    = $request->ramburs_type;
        $awb['service_1']       = $request->service_1;
        $awb['service_2']       = $request->service_2;
        $awb['service_3']       = $request->service_3;
        $awb['insurance']       = $request->insurance;
        $awb['weight']          = $request->weight;
        $awb['content']         = $request->content;
        $awb['fragile']         = $request->fragile;
        $awb['payer']           = $request->payer;
        $awb['from_name']       = $request->from_name;
        $awb['from_contact']    = $request->from_contact;
        $awb['from_phone']      = $request->from_phone;
        $awb['from_email']      = $request->from_email;
        $awb['from_county']     = $request->from_county;
        $awb['from_city']       = $request->from_city;
        $awb['from_address']    = $request->from_address;
        $awb['from_zipcode']    = $request->from_zipcode;
        $awb['to_name']         = $request->to_name;
        $awb['to_contact']      = $request->to_contact;
        $awb['to_phone']        = $request->to_phone;
        $awb['to_email']        = $request->to_email;
        $awb['to_county']       = $request->to_county;
        $awb['to_city']         = $request->to_city;
        $awb['to_address']      = $request->to_address;
        $awb['to_zipcode']      = $request->to_zipcode;
        $awb['comments']        = $request->comments;



        $response = Http::post($api_url, $awb);

        if($response->status()==200) {

            $mesajRaspuns = json_decode($response, true);
            $awb = $mesajRaspuns['data']['no'];
            $error = $mesajRaspuns['data']['errors'];
            $status= $mesajRaspuns['data']['status'];
            $mesaj= $mesajRaspuns['message']; 
            if (is_numeric($awb)) {
                return response([
                    'success'   => 1,
                    'message'   => $awb ,
                ], 200);

            }

            else {
                return response([
                    'success'   => 0,
                    'message'   => 'Eroare la generare' .$error.$status.$mesaj,          
                ], 200);
            }
        }
        return response([
            'success'   => 0,
            'message'   => 'Eroare la generare' .$response,          
        ], 200);
    
    }

    ////////////////////////////////////////////////////////////
    // PRINTARE AWB ////////////////////////////////////////////
    ////////////////////////////////////////////////////////////
    public function printAwb(Request $request) {

        $subscriptionKey = $request->subscriptionKey;
        $token =  'Bearer '.$request->token_cargus;

        $queryString['barCodes'] = $request->barCodes;
        $queryString['type'] = $request->type;
        if ($request->format) $queryString['format'] = $request->format;
        if ($request->printMainOnce) $queryString['printMainOnce'] = $request->printMainOnce;
        if ($request->printOneAwbPerPage) $queryString['printOneAwbPerPage'] = $request->printOneAwbPerPage;
        if ($request->senderName) $queryString['senderName'] = $request->senderName;

        $api_url = "https://urgentcargus.azure-api.net/api/AwbDocuments"; 

        $response = Http::withHeaders([
            'Ocp-Apim-Subscription-Key' => $subscriptionKey,
            'Authorization' => $token
        ])->get($api_url, $queryString);

        return trim($response,'"');
    }

    ////////////////////////////////////////////////////////////
    // STERGERE AWB ////////////////////////////////////////////
    ////////////////////////////////////////////////////////////
    public function deleteAwb(Request $request) {

        $barCode = $request->barCode;
        $subscriptionKey = $request->subscriptionKey;
        $token =  'Bearer '.$request->token_cargus;
        $api_url = 'https://urgentcargus.azure-api.net/api/Awbs?barCode='.$barCode;
        
        $response = Http::withHeaders([
            'Ocp-Apim-Subscription-Key' => $subscriptionKey,
            'Authorization' => $token
        ])->delete($api_url);

        return $response;
    }

    ////////////////////////////////////////////////////////////
    // PICKUP LOCATIONS ////////////////////////////////////////
    ////////////////////////////////////////////////////////////
    public function pickupLocations(Request $request) {

        $api_url = 'https://urgentcargus.azure-api.net/api/PickupLocations';
        $subscriptionKey = $request->subscriptionKey;
        $token =  'Bearer '.$request->token_cargus;

        $response = Http::withHeaders([
            'Ocp-Apim-Subscription-Key' => $subscriptionKey,
            'Authorization' => $token
        ])->get($api_url);

        return $response;
    }
}
