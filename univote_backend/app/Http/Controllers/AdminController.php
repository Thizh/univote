<?php

namespace App\Http\Controllers;

use App\Events\ScreenUpdated;
use App\Models\Admin;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\Vote;
use App\Models\Voter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use phpseclib3\Crypt\AES;

class AdminController extends Controller
{

    public function updateVoteStat(Request $req)
    {
        $vote_id = $req->vote_id;
        $action = $req->action;

        $vote = Vote::find($vote_id);
        if (!$vote) {
            return response()->json(['message' => 'Vote not found'], 404);
        }

        if ($action === 'accepted') {
            // $vote->lastSeen = false; commmented this line because we can track already scanned QRs
            $vote->isAccepted = true;
        } else {
            $vote->rejected = true;
        }

        $vote->save();

        return response()->json(['message' => "Vote has been $action successfully"]);
    }

    public function handleLogin(Request $request)
    {
        // Default credentials
        // $defaultUsername = 'admin';
        // $defaultPassword = 'password123';
        $user = Admin::where('username', $request->input('username'))->first();

        if ($user) {
            if (Hash::check($request->input('password'), $user->password)) {
                switch ($user->user_type) {
                    case 'admin':
                        Session::put('admin_logged_in', true);
                        return redirect('/dashboard');
                    case 'staff':
                        Session::put('admin_logged_in', true);
                        Session::put('staff_logged_in', true);
                        return redirect('/dashboard');
                }
            }
        }

        return redirect()->route('login')->with('error', 'Invalid username or password!');
    }

    public function mobileLogin(Request $request)
    {
        try {
            $user = Admin::where('username', $request->input('username'))->first();

            if (Hash::check($request->input('password'), $user->password)) {
                $user->isLoggedIn = true;
                return [true];
            }

            return [false, 'Invalid username or password'];
        } catch (Exception $e) {
            return ['error' => $e];
        }
    }

    public function dashboard()
    {
        $votersCount = DB::table('voters')->count();

        $candidatesCount = DB::table('candidates')->count();

        $eligibleCandidatesCount = DB::table('candidates')->where('eligible', 1)->count();

        $election = DB::table('elections')->first();

        if ($election->isStarted) {
            Session::put('election_started', true);
        }

        if (!Session::get('admin_logged_in')) {
            return redirect()->route('adminlogin')->with('error', 'Please log in first.');
        }
        return view('dashboard', ['voters' => $votersCount, 'candidates' => $candidatesCount, 'eligibleCandidates' => $eligibleCandidatesCount, 'election' => $election]);
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function logout()
    {
        Session::flush(); // Clear all sessions
        return redirect('login')->with('message', 'Logged out successfully!');
    }

    public function candidates()
    {
        $candidates = DB::table('candidates')
            ->join('voters', 'voters.id', '=', 'candidates.user_id')
            ->select('candidates.id', 'voters.name', 'voters.nic', 'voters.email', 'voters.faculty', 'voters.reg_no', 'voters.level', 'candidates.eligible')
            ->get();

        return view('candidates', ['candidates' => $candidates]);
    }

    public function voters()
    {
        $voters = DB::table('voters')
            ->select('id', 'name', 'nic', 'email', 'reg_no', 'faculty', 'level', 'status')
            ->get();

        return view('voters', ['voters' => $voters]);
    }

    public function polling()
    {
        return view('polling'); // Ensure this view exists
    }

    public function acceptVote()
    {
        $fiveMinutesAgo = Carbon::now()->subMinutes(5);

        $voteDetails = DB::table('votes')
            ->join('voters', 'votes.vot_id', '=', 'voters.id')
            ->where('votes.isAccepted', false)
            ->where('votes.lastSeen', true)
            ->where('votes.rejected', false)
            ->orderBy('votes.created_at', 'desc')
            ->select('votes.id as vote_id', 'voters.nic', 'voters.name', 'voters.email', 'voters.reg_no', 'voters.faculty', 'voters.level')
            ->first();


        // DB::table('votes')->where('created_at', '<', $fiveMinutesAgo)->delete();

        // dd($voteDetails);

        // $check = DB::table('votes')
        //     ->count();

        // dd($check);

        return view('acceptvote', ['voter' => $voteDetails]);
    }


    function decryptData($encryptedData)
    {
        // Decode the encrypted data (JSON object from React)
        $data = json_decode($encryptedData, true);

        if (!isset($data['ciphertext']) || !isset($data['iv'])) {
            return ['Invalid encrypted data format.'];
        }

        $ciphertext = base64_decode($data['ciphertext']); // Decode ciphertext
        $iv = base64_decode($data['iv']); // Decode IV
        $secretKey = env('SECRET_KEY'); // Retrieve secret key from .env

        if (!$secretKey) {
            return ['Secret key not set in environment variables.'];
        }

        // Initialize AES for decryption
        $aes = new AES('cbc'); // Use CBC mode
        $aes->setKey($secretKey);
        $aes->setIV($iv); // Set the IV

        $decrypted = $aes->decrypt($ciphertext);

        return $decrypted;
    }

    //accept vote after reading
    public function acceptVoter(Request $req)
    {
        $action = $req->input('action');

        $vote_id = $this->decryptData($req->input('vote_id'));

        $voteDetails = Vote::find($vote_id);

        $voteDetails->lastSeen = false;
        if ($action == 'accept') {
            $voteDetails->isAccepted = true;
            $voteDetails->save();
        } else {
            return ['error' => 'not valid'];
        }

        return response()->json(['success' => 'Action processed successfully']);
    }

    public function results()
    {
        $vote = DB::table('votes')
            ->join('candidates', 'votes.can_id', '=', 'candidates.id')
            ->join('voters', 'candidates.user_id', '=', 'voters.id')
            ->select(DB::raw('COUNT(votes.can_id) as can_count'), 'voters.name as can_name')
            ->groupBy('candidates.id', 'voters.name')
            ->where('isAccepted', '=', true)
            ->get();

        $max_vote = DB::table('votes')
            ->join('candidates', 'votes.can_id', '=', 'candidates.id')
            ->join('voters', 'candidates.user_id', '=', 'voters.id')
            ->select(DB::raw('COUNT(votes.can_id) as can_count'), 'voters.name as can_name')
            ->groupBy('candidates.id', 'voters.name')
            ->where('isAccepted', '=', true)
            ->orderByDesc('can_count')
            ->first();

        return view('results', ['stats' => $vote, 'lead_name' => $max_vote ? $max_vote->can_name : 'No votes yet']);
    }

    public function deleteCand($id)
    {
        $candidate = Candidate::find($id);

        if ($candidate) {
            $candidate->delete();
            return back()->with('success', 'Candidate deleted successfully.');
        } else {
            return back()->with('error', 'Candidate not found.');
        }
    }

    public function deleteUser($id)
    {
        $user = Admin::find($id);

        if ($user) {
            $user->delete();
            return back()->with('success', 'user deleted successfully.');
        } else {
            return back()->with('error', 'user not found.');
        }
    }


    public function addCand(Request $req)
    {
        $voter = DB::table('voters')->where('nic', '=', $req->input('nic'))->first();

        $candidate = new Candidate();
        $candidate->user_id = $voter->id;
        $candidate->contact_no = $req->input('contact');
        $candidate->save();

        return back();
    }

    public function addUser(Request $req)
    {

        $user = new Admin();
        $user->username = $req->input('username');
        $user->password = Hash::make($req->input('password'));
        $user->user_type = $req->input('utype');
        $user->save();

        return back();
    }

    public function startElection()
    {
        $getElection = DB::table('elections')->where('id', 1)->first();

        $election = Election::updateOrCreate(
            ['id' => 1],
            ['isStarted' => !$getElection->isStarted]
        );

        $electionStarted = $election->isStarted;

        Session::put('election_started', $electionStarted);

        return response()->json([
            'election_started' => $electionStarted,
            'message' => $electionStarted ? 'Election started successfully' : 'Election stopped successfully',
        ]);
    }

    public function qrScanned(Request $req)
    {
        $data = $this->decryptData($req->input('data'));

        try {
            // event(new ScreenUpdated(['update_key' => 'accept-vote']));
            // ScreenUpdated::dispatch(['update_key' => 'accept-vote']);
            DB::table('votes')
                ->where('id', $data)
                ->update(['lastSeen' => true]);

            return response()->json([true, 'id' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'success', 'error' => $e]);
        }
    }

    public function acceptv(Request $req)
    {
        $data = $req->input('data');

        try {
            $voteDetails = DB::table('votes')
                ->where('vot_id', $data)
                ->first();

            if ($voteDetails && !$voteDetails->isAccepted) {
                DB::table('votes')
                    ->where('vot_id', $data)
                    ->update(['isAccepted' => true]);

                return response()->json([true]);
            }

            return response()->json([false]);
        } catch (Exception $e) {
            return response()->json([false, 'error' => $e]);
        }
    }

    public function byLawPdf(Request $req)
    {
        $req->validate([
            'fileName' => 'required|string',
            'fileData' => 'required|string',
        ]);

        try {
            $fileData = $req->input('fileData');
            $decodedFile = base64_decode(preg_replace('#^data:application/pdf;base64,#', '', $fileData));

            $fileName = "Student_Union_By_Law.pdf";
            $filePath = 'pdfs/' . $fileName;

            // Check if the file exists and delete it
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }

            // Store the new file
            Storage::put($filePath, $decodedFile);

            return response()->json([
                'message' => 'File uploaded successfully!',
                'path' => $filePath,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function createStaff()
    {

        $users = Admin::all();

        return view('createstaff', ['users' => $users]);
    }


    public function toggleStatusVoter(Request $request)
    {
        $voter = Voter::findOrFail($request->id);
        $voter->status = $request->status;
        $voter->save();

        Vote::where('vot_id', $voter->id)->update([
            'rejected' => !$request->status,
            'isAccepted' => $request->status
        ]);

        return response()->json(['message' => 'Status updated successfully!']);
    }
}
