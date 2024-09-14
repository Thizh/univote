<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class SignUpController extends Controller
{
    public function getStudentDetails(Request $request)
    {
        $nic = $request->input('nic');
        
        $client = new Client();
        
        $flaskUrl = 'http://127.0.0.1:5000/scrape';

        $response = $client->post($flaskUrl, [
            'json' => ['nic' => $nic], 
            'headers' => [
                'Content-Type' => 'application/json' 
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        
        if (isset($data['error'])) {
            return response()->json(['error' => $data['error']], 500);
        }

        return response()->json([
            'name' => $data['name'],
            'reg_no' => $data['reg_no']
        ]);
    }
}
