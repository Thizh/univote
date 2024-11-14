<?php

namespace App\Http\Controllers;

use App\Models\Voter;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPHtmlParser\Dom;
use Symfony\Component\DomCrawler\Crawler;

class SignUpController extends Controller
{
    public function getStudent(Request $req) {
        $voter = Voter::where('nic', $req->input('nic'))->first();

        if ($voter) {
            return [true, "name" => $voter->name, 'firstTime' => $voter->isFirstTime];
        }

        $client = new Client();

        try {
            $res = $client->request('POST', 'https://reginfo.ou.ac.lk/redirect.php', [
                'form_params' => [
                    'nic' => $req->input('nic')
                ]
            ]);
    

            $body = $res->getBody()->getContents();
        
            $crawler = new Crawler($body);
        
            //get student name
            $table1 = $crawler->filter('table#alternatecolor1');
            $name = $table1->filter('tr:nth-child(2) td:nth-child(2)')->count() > 0
                ? trim($table1->filter('tr:nth-child(2) td:nth-child(2)')->text())
                : null;
        
            //get student registration number
            $table2 = $crawler->filter('table#alternatecolor');
            $reg_no = $table2->filter('tr:nth-child(3) td:nth-child(1)')->count() > 0
                ? trim($table2->filter('tr:nth-child(3) td:nth-child(1)')->text())
                : null;
        
            if (empty($name) || empty($reg_no)) {
                return [false, "Student not found"];
            }

            $user = Voter::where('nic', $req->input('nic'))->first();

            if (!$user) {
                $voter = new Voter();
                $voter->nic = $req->input('nic');
                $voter->name = $name;
                $voter->password = Hash::make($reg_no);
                $voter->save();
            }
        
            return [true, "name" => $name, 'firstTime' => $user->isFirstTime];
        
        } catch (\Exception $e) {
            return [false, "An error occurred: " . $e->getMessage()];
        }        
    }

    public function check(Request $req) {
        $user = Voter::where('nic', $req->input('nic'))->first();
    
        if (Hash::check($req->input('reg_no'), $user->password)) {
            return [true, 'message' => 'Password matches', 'user' => $user->id];
        } else {
            return [false, 'message' => 'Password does not match'];
        }
    }

    public function isFirstTime(Request $req) {
        $user = Voter::where('id', $req->input('id'))->first();

        return [true, 'firstTime' => $user->isFirstTime];
    }

    public function saveUserData(Request $req) {
        $user = Voter::where('id', $req->input('id'))->first();

        try {
            $user->isFirstTime = false;
            $user->faculty = $req->input('faculty');
            $user->level = $req->input('level');
            $user->save();
        } catch (Exception $e) {
            return ['error' => $e];
        }

        return [true, 'firstTime' => $user->isFirstTime];
    }
}
