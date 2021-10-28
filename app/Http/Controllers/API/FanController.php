<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class FanController extends Controller
{

    
    ////////////////////////////////////////////////////////////
    // VALIDATE AUTH //////////////////////////////////////////
    ////////////////////////////////////////////////////////////
    public function validateAuth(Request $request) {

        $api_url = 'https://www.selfawb.ro/get_account_clients_integrat.php';

        $username  = $request->username;
        $user_pass = $request->user_pass;
    
        $response = Http::asForm()->post($api_url, [
            'username'  => $username,
            'user_pass' => $user_pass
        ]);

        
        $response_j = json_decode($response);

        //var_dump(response($response_j->error));
        if(isset($response_j->error)) {
            return response([
                'success'    => 0,
                'message'   => 'Eroare la logare',
                
            ], 200);

        }
        return response([
            'success'    => 1,
            'message'   => 'Autentificare reusita',
            
        ], 200);
    }

    ////////////////////////////////////////////////////////////
    // ACCOUNT CLIENTS /////////////////////////////////////////
    ////////////////////////////////////////////////////////////
    public function getAccount(Request $request) {

        $api_url = 'https://www.selfawb.ro/get_account_clients_integrat.php';

        $username  = $request->username;
        $user_pass = $request->user_pass;
    
        $response = Http::asForm()->post($api_url, [
            'username'  => $username,
            'user_pass' => $user_pass
        ]);

        return $response;
    }

    ////////////////////////////////////////////////////////////
    // SERVICES ////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////
    public function getServices(Request $request) {

        $api_url = 'https://www.selfawb.ro/export_servicii_integrat.php';

        $username  = $request->username;
        $client_id = $request->client_id;
        $user_pass = $request->user_pass;
    
        $response_services_list = Http::asForm()->post($api_url, [
            'username'  => $username,
            'client_id' => $client_id,
            'user_pass' => $user_pass
        ]);

        Storage::disk('local')->put('export_servicii_integrat.csv', $response_services_list);

		$rows = array_map('str_getcsv', file(Storage::path('export_servicii_integrat.csv')));
		$header = array_shift($rows);
		$csv = array();
		foreach ($rows as $row) {
			$csv[] = array_combine($header, $row);
		}
		$return = [];
		foreach($csv as $val) {
		    $return[$val["Servicii FAN Courier"]]=$val["Servicii FAN Courier"];
		}

        return $return;
    }


    ////////////////////////////////////////////////////////////
    // GENERARE AWB ////////////////////////////////////////////
    ////////////////////////////////////////////////////////////
    public function generateAwb(Request $request) {

        $api_url = 'https://www.selfawb.ro/import_awb_integrat.php';

        $username  = $request->username;
        $client_id = $request->client_id;
        $user_pass = $request->user_pass;

        $tip_serviciu       = $request->tip_serviciu;
        $nr_colete          = $request->nr_colete;
        $nr_plicuri         = $request->nr_plicuri;
        $greutate           = $request->greutate;
        $plata_expeditie    = $request->plata_expeditie;
        $ramburs            = $request->ramburs;
        $plata_ramburs_la   = $request->plata_ramburs_la;
        $valoare_declarata  = $request->valoare_declarata;
        $persoana_contact_expeditor = $request->persoana_contact_expeditor;
        $observatii         = $request->observatii;
        $continut           = $request->continut;
        $nume_destinatar    = $request->nume_destinatar;
        $persoana_contact   = $request->persoana_contact;
        $telefon            = $request->telefon;
        $fax                = $request->fax;
        $email              = $request->email;
        $judet              = $request->judet;
        $localitate         = $request->localitate;
        $strada             = $request->strada;
        $nr                 = $request->nr;
        $cod_postal         = $request->cod_postal;
        $bloc               = $request->bloc;
        $scara              = $request->scara;
        $etaj               = $request->nr_etajcolete;
        $apartament         = $request->apartament;
        $inaltime_pachet    = $request->inaltime_pachet;
        $latime_pachet      = $request->latime_pachet;
        $lungime_pachet     = $request->lungime_pachet;
        $restituire         = $request->restituire;
        $centru_cost        = $request->centru_cost;
        $optiuni            = $request->optiuni;
        $packing            = $request->packing;
        $date_personale     = $request->date_personale;

        $header = "Tip serviciu,Banca,IBAN,Nr. Plicuri,Nr. Colete,Greutate,Plata expeditie,Ramburs(bani),Plata ramburs la,Valoare declarata,Persoana contact expeditor,Observatii,Continut,Nume destinatar,Persoana contact,Telefon,Fax,Email,Judet,Localitatea,Strada,Nr,Cod postal,Bloc,Scara,Etaj,Apartament,Inaltime pachet,Latime pachet,Lungime pachet,Restituire,Centru Cost,Optiuni,Packing,Date personale";
        $msgData = "$tip_serviciu,,,$nr_plicuri,$nr_colete,$greutate,$plata_expeditie,$ramburs,$plata_ramburs_la,$valoare_declarata,$persoana_contact_expeditor,$observatii,$continut,$nume_destinatar,$persoana_contact,$telefon,$fax,$email,$judet,$localitate,$strada,$nr,$cod_postal,$bloc,$scara,$etaj,$apartament,$inaltime_pachet,$latime_pachet,$lungime_pachet,$restituire,$centru_cost,$optiuni,$packing,$date_personale";

        Storage::disk('local')->put('fan_courier_request.csv', $header . PHP_EOL . $msgData);
        $fisier = fopen(Storage::path('fan_courier_request.csv'), 'r');

        $response = Http::asMultipart()->post($api_url, [
            'username'  => $username,
            'client_id' => $client_id,
            'user_pass' => $user_pass,
            'fisier'    => $fisier
        ]);

        $response_list = explode(',', $response);
     
        if(is_numeric($response_list[2])) {
            return response([
                'success'   => 1,
                'message'   => $response_list[2] ,
            ], 200);
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

        $html_pdf   = $request->html_pdf;
        $api_url = 'https://www.selfawb.ro/view_awb_integrat_pdf.php';
        if ($html_pdf == 'html') {
            $api_url = 'https://www.selfawb.ro/view_awb_integrat.php';
        }

        $username  = $request->username;
        $client_id = $request->client_id;
        $user_pass = $request->user_pass;

        $nr     = $request->nr;
        $type   = $request->type;
        $page   = $request->page;
        $label  = $request->label;

        $response = Http::asForm()->post($api_url, [
            'username'  => $username,
            'client_id' => $client_id,
            'user_pass' => $user_pass,
            'nr'        => $nr,
            'type'      => $type,
            'page'      => $page,
            'label'     => $label
        ]);
        
        return $response;      
        
    }

    ////////////////////////////////////////////////////////////
    // STERGERE AWB ////////////////////////////////////////////
    ////////////////////////////////////////////////////////////
    public function deleteAwb(Request $request) {

        $api_url = 'https://www.selfawb.ro/delete_awb_integrat.php';

        $username  = $request->username;
        $client_id = $request->client_id;
        $user_pass = $request->user_pass;

        $AWB     = $request->AWB;
        
        $response = Http::asForm()->post($api_url, [
            'username'  => $username,
            'client_id' => $client_id,
            'user_pass' => $user_pass,
            'AWB'       => $AWB
        ]);

        return $response;
    }
}
