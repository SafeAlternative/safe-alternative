<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class FanController extends Controller
{
    public function index() {
        return 'Hello from FanController';
    }

    public function generateAwb(Request $request) {


        $tip_serviciu = 'Standard';
        $nr_colete_sau_plicuri = 1;
        $greutate = 1;
        $plata_expeditie = 1;
        $ramburs = 1;
        $plata_ramburs= 1;
        $val_decl = 1;
        $observatii = 1;
        $continut = 1;
        $nume_destinatar = 1;
        $telefon = '0725518855';
        $judet = 'Bucuresti';
        $localitate = 'Bucuresti';
        $adresa = 1;
        $cod_postal = 1;




        $header = "Tip serviciu,Banca,IBAN,Nr. Plicuri,Nr. Colete,Greutate,Plata expeditie,Ramburs(bani),Plata ramburs la,Valoare declarata,Persoana contact expeditor,Observatii,Continut,Nume destinatar,Persoana contact,Telefon,Fax,Email,Judet,Localitatea,Strada,Nr,Cod postal,Bloc,Scara,Etaj,Apartament,Inaltime pachet,Latime pachet,Lungime pachet,Restituire,Centru Cost,Optiuni,Packing,Date personale";
        $msgData = "$tip_serviciu,,,,$nr_colete_sau_plicuri,$greutate,$plata_expeditie,$ramburs,$plata_ramburs,$val_decl,,$observatii,$continut,$nume_destinatar,,$telefon,,,$judet,$localitate,$adresa,,$cod_postal,,,,,,,,,,,,";


        Storage::disk('local')->put('fan_courier_request.csv', $header . PHP_EOL . $msgData);
        //file_put_contents('fan_courier_request.csv', $header . PHP_EOL . $msgData);
        
        //$fisier = Storage::disk('local')->get('fan_courier_request.csv');
        $fisier = fopen(Storage::path('fan_courier_request.csv'), 'r');



        $response = Http::asMultipart()->post('https://www.selfawb.ro/import_awb_integrat.php', [
            'username'  => $request->username,
            'client_id' => $request->client_id,
            'user_pass' => $request->user_pass,
            'fisier'    => $fisier
        ]);

        return $response;
        return response([$request->username, $request->client_id, $request->user_pass]);


    }

    public function printAwb() {
        return ['error' => '', 'awb'=> ''];
    }


}
