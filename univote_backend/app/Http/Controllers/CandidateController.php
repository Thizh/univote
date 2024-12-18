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

        return [true, 'ref' => $candidate->id];
    }

    public function getData() {

        try {
            $candidate = DB::table('candidates')
                ->join('voters', 'voters.id', '=', 'candidates.user_id')
                ->where('candidates.eligible', true)
                ->select('candidates.id as canid', 'voters.name as candidate_name')
                ->get();
    
            return [true, 'candidates' => $candidate];
        } catch (Exception $e) {
            return [false, 'error' => $e];
        }
    }

    public function eligiblityChecked(Request $req)
    {
        try {
        $checked = $req->input('checked', false);
        $can_id = $req->input('can_id');
    
        $updateCount = DB::table('candidates')
            ->where('id', $can_id)
            ->update(['eligible' => $checked]);
    
        if ($updateCount) {
            return response()->json(['message' => 'Voter updated successfully'], 200);
        } else {
            return response()->json(['message' => 'Voter not found or update failed', 'id' => $checked], 404);
        }
    } catch (Exception $e) {
        return response()->json(['message' => $e], 404);
    }
    }
}
