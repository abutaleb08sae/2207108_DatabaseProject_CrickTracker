<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FixtureController extends Controller
{
    // Render the list of all fixtures (Screenshot 2)
    public function index()
    {
        $fixtures = DB::table('matches')
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->get();

        return view('admin.fixtures', compact('fixtures'));
    }

    // Render the create form (Screenshot 1)
    public function create()
    {
        $teams = DB::table('teams')->get();
        return view('admin.fixtures-create', compact('teams'));
    }

    // Process and save the scheduled match into the database
    public function store(Request $request)
    {
        $request->validate([
            'team1_id' => 'required',
            'team2_id' => 'required|different:team1_id',
            'match_datetime' => 'required',
            'venue' => 'required|string|max:255',
            'status' => 'required|in:Upcoming,LIVE,Completed'
        ]);

        // Separate date and time from raw datetime input
        $datetime = new \DateTime($request->match_datetime);
        $date = $datetime->format('Y-m-d');
        $time = $datetime->format('h:i A');

        // Resolve names for caching or display fallbacks if needed
        $team1 = DB::table('teams')->where('id', $request->team1_id)->first();
        $team2 = DB::table('teams')->where('id', $request->team2_id)->first();

        DB::table('matches')->insert([
            'team1_name' => $team1->name ?? $request->team1_id,
            'team2_name' => $team2->name ?? $request->team2_id,
            'date' => $date,
            'time' => $time,
            'venue_name' => $request->venue,
            'status' => $request->status, // 'Upcoming' or 'LIVE' will determine which screen it appears on
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('admin.fixtures.index')->with('success', 'Match fixture scheduled successfully!');
    }

    // Delete a fixture item instantly from both interfaces
    public function destroy($id)
    {
        DB::table('matches')->where('match_id', $id)->delete();
        return redirect()->route('admin.fixtures.index')->with('success', 'Match fixture dropped from system.');
    }
}