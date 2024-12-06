<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Exception;
use Illuminate\Http\Request;
use DB;

class CandidateController extends Controller
{
    public function candidateApply(Request $req) {
        $contact = $req->input('contact');
        $user = $req->input('user');

        $candidate = new Candidate();
        $candidate->user_id = $user;
        $candidate->contact_no = $contact;
        $candidate->save();

        return [true];
    }

    public function getData() {

        try {
            $candidate = DB::table('candidates')
            ->join('voters', 'voters.id', '=', 'candidates.user_id')
            ->select('candidates.id as canid', 'voters.name as candidate_name')
            ->get();
    
            return [true, 'candidates' => $candidate];
        } catch (Exception $e) {
            return [false, 'error' => $e];
        }


    }
}
