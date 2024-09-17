<?php

namespace App\Http\Controllers;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use PHPHtmlParser\Dom;
use Symfony\Component\DomCrawler\Crawler;

class SignUpController extends Controller
{
    public function getStudent(Request $req) {
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
        
            return [true, "name" => $name, "reg_no" => $reg_no];
        
        } catch (\Exception $e) {
            return [false, "An error occurred: " . $e->getMessage()];
        }        
    }
}
