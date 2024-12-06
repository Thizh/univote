<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use Exception;
use Illuminate\Http\Request;
use DB;

class VoterController extends Controller
{
    public function vote(Request $req) {
        try {
            $vote = new Vote();
            $vote->vot_id = $req->input('voter');
            $vote->can_id = $req->input('candidate');
            $vote->save();

            return [true, 'voted' => $vote->id];
        } catch (Exception $e) {
            return [false];
        }
    }

    public function getStats() {
        try {
            $vote = DB::table('votes')
            ->join('candidates', 'votes.can_id', '=', 'candidates.id')
            ->join('voters', 'candidates.user_id', '=', 'voters.id')
            ->select(DB::raw('COUNT(votes.can_id) as can_count'), 'voters.name as can_name')
            ->groupBy('candidates.id', 'voters.name')
            ->where('isAccepted', '=', true)
            ->get();

            return [true, 'stats' => $vote];
        } catch (Exception $e) {
            return [false, 'error' => $e];
        }
    }
}
