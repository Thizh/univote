<?php

namespace App\Http\Controllers;

use App\Events\ScreenUpdated;
use App\Models\Admin;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DB;
use Exception;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function handleLogin(Request $request)
    {
        // Default credentials
        // $defaultUsername = 'admin';
        // $defaultPassword = 'password123';
        $user = Admin::where('username', $request->input('username'))->first();

        if (Hash::check($request->input('password'), $user->password)) {
            Session::put('admin_logged_in', true);
            return redirect('/dashboard');
        }

        return redirect()->route('adminlogin')->with('error', 'Invalid username or password!');
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
        if (!Session::get('admin_logged_in')) {
            return redirect()->route('adminlogin')->with('error', 'Please log in first.');
        }
        return view('dashboard');
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
            ->select('voters.id', 'voters.name', 'voters.nic', 'voters.email', 'voters.faculty', 'voters.level')
            ->get();

        return view('candidates', ['candidates' => $candidates]);
    }

    public function voters()
    {
        $voters = DB::table('voters')
            ->select('id', 'name', 'nic', 'email', 'faculty', 'level', 'eligible')
            ->get();

        return view('voters', ['voters' => $voters]);
    }

    public function polling()
    {
        return view('polling'); // Ensure this view exists
    }

    public function acceptVote()
    {
        return view('acceptvote'); // Ensure this view exists
    }

    public function results()
    {
        return view('results'); // Ensure this view exists
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

    public function addCand(Request $req)
    {
        $voter = DB::table('voters')->where('nic', '=', $req->input('nic'))->first();

        $candidate = new Candidate();
        $candidate->user_id = $voter->id;
        $candidate->contact_no = $req->input('contact');
        $candidate->save();

        return back();
    }

    public function startElection()
    {
        $electionStarted = Session::get('election_started', false);
        Session::put('election_started', !$electionStarted);

        return response()->json([
            'election_started' => !$electionStarted,
            'message' => !$electionStarted ? 'Election started successfully' : 'Election stopped successfully',
        ]);
    }

    public function qrScanned(Request $req)
    {

        $data = $req->input('data');

        $voteDetails = DB::table('votes')
            ->join('voters', 'votes.vot_id', '=', 'voters.id')
            ->where('votes.vot_id', $data)
            ->select('voters.nic', 'voters.name', 'voters.email', 'voters.faculty', 'voters.level')
            ->first();

        try {
            event(new ScreenUpdated(['update_key' => 'accept-vote']));
            // ScreenUpdated::dispatch(['update_key' => 'accept-vote']);
            return response()->json([true, 'details' => $voteDetails]);
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
}
