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

    ////////////////////////////////////////////////////////////
    // LOGIN  //////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////
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

    ////////////////////////////////////////////////////////////
    // GENERARE AWB ////////////////////////////////////////////
    ////////////////////////////////////////////////////////////
    public function generateAwb(Request $request) {
        
        $api_url = 'https://urgentcargus.azure-api.net/api/Awbs';
        $subscriptionKey = $request->subscriptionKey;
        $token =  'Bearer '.$request->token;

        $awb['Sender']['LocationId'] = $request->Sender['LocationId'];
        $awb['Recipient']['Name'] = $request->Recipient['Name'];
        $awb['Recipient']['CountyName'] = $request->Recipient['CountyName'];
        $awb['Recipient']['LocalityName'] = $request->Recipient['LocalityName'];
        $awb['Recipient']['StreetName'] = $request->Recipient['StreetName'];
        $awb['Recipient']['AddressText'] = $request->Recipient['AddressText'];
        $awb['Recipient']['ContactPerson'] = $request->Recipient['ContactPerson'];
        $awb['Recipient']['PhoneNumber'] = $request->Recipient['PhoneNumber'];
        $awb['Recipient']['Email'] = $request->Recipient['Email'];
        $awb['Recipient']['CodPostal'] = $request->Recipient['CodPostal'];
        $awb['Parcels'] = $request->Parcels;
        $awb['Envelopes'] = $request->Envelopes;
        $awb['TotalWeight'] = $request->TotalWeight;
        $awb['DeclaredValue'] = $request->DeclaredValue;
        $awb['CashRepayment'] = $request->CashRepayment;
        $awb['BankRepayment'] = $request->BankRepayment;
        $awb['OtherRepayment'] = $request->OtherRepayment;
        $awb['PriceTableId'] = $request->PriceTableId;
        $awb['ShipmentPayer'] = $request->ShipmentPayer;
        $awb['ShippingRepayment'] = $request->ShippingRepayment;
        $awb['OpenPackage'] = $request->OpenPackage;
        $awb['SaturdayDelivery'] = $request->SaturdayDelivery;
        $awb['MorningDelivery'] = $request->MorningDelivery;
        $awb['Observations'] = $request->Observations;
        $awb['PackageContent'] = $request->PackageContent;
        $awb['CustomString'] = $request->CustomString;
        $awb['SenderReference1'] = $request->SenderReference1;
        $awb['RecipientReference1'] = $request->RecipientReference1;
        $awb['RecipientReference2'] = $request->RecipientReference2;
        $awb['InvoiceReference'] = $request->InvoiceReference;
        
        if($request->ParcelCodes) 
            $awb['ParcelCodes'] = $request->ParcelCodes;

        $response = Http::withHeaders([
            'Ocp-Apim-Subscription-Key' => $subscriptionKey,
            'Authorization' => $token
        ])->post($api_url, $awb);

        return $response;
    }

    ////////////////////////////////////////////////////////////
    // PRINTARE AWB ////////////////////////////////////////////
    ////////////////////////////////////////////////////////////
    public function printAwb(Request $request) {

        $subscriptionKey = $request->subscriptionKey;
        $token =  'Bearer '.$request->token;

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
        $token =  'Bearer '.$request->token;
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
        $token =  'Bearer '.$request->token;

        $response = Http::withHeaders([
            'Ocp-Apim-Subscription-Key' => $subscriptionKey,
            'Authorization' => $token
        ])->get($api_url);

        return $response;
    }
}
