<?php

namespace App\Http\Controllers;

use App\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeamController extends Controller
{
    public function __construct()
    {
        // TBD
        $this->middleware('auth', ['only' => [
            'showAll',
            'showOne',
            'create',
            'delete',
            'confirm'
        ]]);
    }

    public function showAll()
    {
        return response()->json(Team::all());
    }

    public function showOne($id)
    {
        return response()->json(Team::find($id));
    }

    public function create(Request $request)
    {
        // TBD
        $this->validate($request, [
            'name' => 'required'
        ]);

        try {
            $team = Team::create([
                'name' => $request->input('name'),
                'event' => $request->input('event'),
                'registered_by' => \Auth::user()->id // to be checked
            ]);
            return response()->json($team, 201);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }        
    }

    public function delete($id)
    {
        Team::findOrFail($id)->delete();
        return response('Deleted successfully', 200);
    }

    // TBD check API token
    public function confirm($id)
    {
        try {
            $team = Team::findOrFail($id);
            try {
                $team->update([
                    'confirmed' => true
                ]);
                return response()->json($team, 200);
            }  catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['message' => $e->getMessage()], 500);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}