<?php

namespace App\Http\Controllers;

use App\Mail\OTPMail;
use App\Models\StudentId;
use App\Models\tempVoter;
use App\Models\Voter;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PHPHtmlParser\Dom;
use Symfony\Component\DomCrawler\Crawler;

class SignUpController extends Controller
{
    public function getStudent(Request $req) {
        $voter = Voter::where('nic', $req->input('nic'))->first();

        if ($voter) {
            return [true, "name" => $voter->name];
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

            $getSid = $this->getSid($reg_no);

            if (!$getSid) {
                return [true, "name" => $name, 'email' => false];
            }

            $voterEmail = $getSid . '@ousl.lk';

            //email mask
            list($sid, $domain) = explode('@', $voterEmail);
            if (strlen($sid) > 4) {
                $maskedSid = substr_replace($sid, '****', 2, -2);
            } else {
                $maskedSid = $sid;
            }
            $maskedEmail = $maskedSid . '@' . $domain;

            $voter = new tempVoter();
            $voter->nic = $req->input('nic');
            $voter->name = $name;
            $voter->password = Hash::make($reg_no);
            $voter->email = $voterEmail;
            $otp = mt_rand(100000, 999999);
            $voter->otp = $otp;
            $voter->save();
        
            // Mail::to($voterEmail)->send(new OTPMail($otp));
            Mail::to($voterEmail)->send(new OTPMail([
                'title' => 'Your OTP for Univote Login',
                'body' => $otp,
            ]));

            return [true, "name" => $name, 'email' => $maskedEmail];
        
        } catch (\Exception $e) {
            return [false, "An error occurred: " . $e->getMessage() . " Details: " . $e->getTrace()];
        }        
    }

    public function check(Request $req) {

        $user = null;

        if ($req->input('email') == null) {
            $user = Voter::where('nic', $req->input('nic'))->first();
        } else {
            $user = tempVoter::where('nic', $req->input('nic'))->first();
        }
    
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

    public function getSid($regNo) {

        $student = StudentId::where('reg_no', $regNo)->first();

        if ($student) {
            $stuId = $student->stu_id;
            return $stuId;
        } else {
            $mapping = [
                "21" => "s92",
                "43" => "07",
                "42" => "06",
                "64" => "08",
            ];

            $prefix = substr($regNo, 1, 2);
            $middle = substr($regNo, 3, 2);
            $suffix = substr($regNo, -4);
    
            if (isset($mapping[$prefix]) && isset($mapping[$middle])) {
                $sid = $mapping[$prefix] . $mapping[$middle] . $suffix;
                return $sid;
            }
        }

        return false;
    }

    public function checkOTP(Request $req)
    {
        $nic = $req->input('nic');
        $otp = $req->input('otp');
        $voter = tempVoter::where('nic', $nic)->first();

        if ($voter->otp == $otp) {
            $regVoter = new Voter();
            $regVoter->nic = $req->input('nic');
            $regVoter->name = $voter->name;
            $regVoter->password = $voter->password;
            $regVoter->email = $voter->email;
            $regVoter->save();
            return [true, 'voter' => $regVoter->id];
        } else {
            return [false];
        }

    }
}
