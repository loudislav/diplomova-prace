<?php

namespace App\Http\Controllers;

use App\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    public function __construct()
    {
        // TBD
        $this->middleware('auth', ['only' => [
            'showAll',
            'showOne',
            'create',
            'delete',
            'confirm',
            'showByEvent'
        ]]);
    }

    public function showAll()
    {
        return response()->json(Registration::all());
    }

    public function showOne($id)
    {
        return response()->json(Registration::find($id));
    }

    public function showByUser($id)
    {
        return response()->json(Registration::select('name', 'surname', 'birthdate', 'id_number', 'street', 'city', 'zip')->where('registered_by', $id)->where('event', 'like', '2%')->groupBy('name', 'surname', 'birthdate', 'id_number', 'street', 'city', 'zip')->orderBy('surname', 'asc')->get());
    }

    public function showByEvent($id)
    {
        return response()->json(Registration::select('registrations.name', 'registrations.surname', 'registrations.event', 'teams.name as teamname')->where('registrations.registered_by', \Auth::user()->id)->where('registrations.event', 'like', $id.'%')->leftJoin('teams', 'registrations.team', '=', 'teams.id')->get());
    }

    public function create(Request $request)
    {
        // TBD
        $this->validate($request, [
            'name' => 'required'
        ]);

        try {
            $registration = Registration::create([
                'name' => $request->input('name'),
                'surname' => $request->input('surname'),
                'birthdate' => $request->input('birthdate'),
                'id_number' => $request->input('id_number'),
                'street' => $request->input('street'),
                'city' => $request->input('city'),
                'zip' => $request->input('zip'),
                'note' => $request->input('note'),
                'event' => $request->input('event'),
                'team' => $request->input('team'),
                'registered_by' => \Auth::user()->id // to be checked
            ]);
            return response()->json($registration, 201);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }        
    }

    public function delete($id)
    {
        Registration::findOrFail($id)->delete();
        return response('Deleted successfully', 200);
    }

    // TBD check API token
    public function confirm($id)
    {
        try {
            $registration = Registration::findOrFail($id);
            try {
                $registration->update([
                    'confirmed' => true
                ]);
                return response()->json($registration, 200);
            }  catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['message' => $e->getMessage()], 500);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}