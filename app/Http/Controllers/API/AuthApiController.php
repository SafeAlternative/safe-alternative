<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;



class AuthApiController extends Controller
{
    // 

    function getToken(Request $request)
    {
        $user= User::where('username', $request->username)->first();
             
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response([
                    'success'    => 0,
                    'message'   => 'Eroare la logare',
                    
                ], 201);
            }
        
             $token = $user->createToken('my-app-token')->plainTextToken;
        
            $response = [
                'success'    => 1,
                'message'   => 'Valid', 
                'user'      => $user,
                'token'     => $token
            ];
        
            return response($response, 201);
    }
}