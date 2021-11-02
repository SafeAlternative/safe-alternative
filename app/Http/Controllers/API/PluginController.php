<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;

class PluginController extends Controller
{

    // 
    function getJson()
    {
        $response = [
            'name'      => 'Safe Alternative',
            'slug'      => 'safe-alternative-plugin', 
            'version'   => '2.15.5',
            'download_url'  => 'http://127.0.0.1:8000/api/plugin/download/safe-alternative-plugin',
            'homepage'  => 'http://127.0.0.1:8000',
            'requires'  => '4.9.9',
            'tested'    => '5.7.2',
            'author'    => 'Safe Alternative SRL',
            'author_homepage' => 'http://127.0.0.1:8000',
            'section'   => ['description' => 'Plugin-ul Safe Alternative All-in-one - Generare AWB si Metode de livrare',
                            'changelog'   => 'http://127.0.0.1:8000/plugin-changelog.txt'
                            ]
        ];
        
        return response($response, 200);
    }

    public function getPlugin() {
        
        $zip_file = 'safe-alternative-plugin.zip';
        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        
        $path = storage_path('plugins/v1_1');
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
        foreach ($files as $name => $file)
        {
            // We're skipping all subfolders
            if (!$file->isDir()) {
                $filePath     = $file->getRealPath();       
                // extracting filename with substr/strlen
                $relativePath = substr($filePath, strlen($path) + 1);
        
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();
        return response()->download($zip_file);   
    }
}