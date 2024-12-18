<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use Exception;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Storage;

class VoterController extends Controller
{
    public function vote(Request $req) {
        try {
            $vote = new Vote();
            $vote->vot_id = $req->input('voter');
            $vote->can_id = $req->input('candidate');
            $vote->save();

            return [true];
        } catch (Exception $e) {
            return [false, 'err' => $e];
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

    public function eligiblityChecked(Request $req)
    {
        $checked = $req->input('checked', 0);
        $voter_id = $req->input('voter_id');
    
        $updateCount = DB::table('voters')
            ->where('id', $voter_id)
            ->update(['eligible' => $checked]);
    
        if ($updateCount) {
            return response()->json(['message' => 'Voter updated successfully'], 200);
        } else {
            return response()->json(['message' => 'Voter not found or update failed'], 404);
        }
    }

    public function isVoted($id) {
        $voter = DB::table('votes')->where('vot_id', $id)->exists();

        if (!$voter) {
            return [false];
        }
        
        return [true];

    }


    public function isApplied($id) {
        $candidate = DB::table('candidates')->where('user_id', $id)->first();

        if (!$candidate) {
            return [false];
        }
        
        return [true, 'ref' => $candidate->id];

    }

    public function saveQR(Request $req) {
        $uid = $req->input('voter');

        $fileData = $req->input('code');
        // $decodedFile = base64_decode(preg_replace('#^data:image/svg+xml;base64,#', '', $fileData));

        try {
            
            $fileName = $uid . ".svg";
            $filePath = 'qr_codes/' . $fileName;

            Storage::put($filePath, $fileData);

            return [true, "file" => $fileName];

        } catch (Exception $e) {
            return [false];
        }

    }

    public function downloadSvg($user_id)
    {
        $filePath = storage_path("qr_codes/{$user_id}.svg");

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->download($filePath, "{$user_id}.svg", [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => 'attachment; filename="' . "{$user_id}.svg" . '"',
        ]);
    }

}
