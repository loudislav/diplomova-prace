<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $regex = '/^\S*(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/';

    public function __construct()
    {
        $this->middleware('auth', ['only' => [
            'logout',
            'update',
            'delete'
        ]]);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('username', $request->input('username'))->first();
        if (!empty($user)) {
            if (Hash::check($request->input('password'), $user->password)) {
                try {
                    $api_token = sha1($user->id.time());

                    $user->update(['api_token' => $api_token]);
                    return response()->json($user, 200);
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json(['message' => $e->getMessage()], 500);
                }
            } else {
                return response()->json(['message' => 'Incorrect username or password.'], 401);
            }
        } else {
            return response()->json(['message' => 'Incorrect username or password.'], 401);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = User::where('api_token', $request->input('api_token'))->first();
            try {
                $user->update([
                        'api_token' => null
                ]);
                return response()->json($user, 200);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['message' => $e->getMessage()], 500);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function showAll()
    {
        return response()->json(User::all());
    }

    public function showOne($id)
    {
        return response()->json(User::find($id));
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            /*
             * temporary workaround
             */
            //'person_id' => 'required',
            'username' => 'required',
            'password' => 'required|min:8|confirmed|regex:'.$this->regex
        ]);

        try {
            $hasher = app()->make('hash');
            $password = $hasher->make($request->input('password'));

            $user = User::create([
                'person_id' => $request->input('person_id'),
                'username' => $request->input('username'),
                'password' => $password
            ]);
            return response()->json($user, 201);
        } catch (\Illuminate\Database\QueryException $e) { 
            if ($e->getCode() == 23000) {
                $code = 409;
                $message = "Duplicate entry";
            } else {
                $code = 500;
                $message = $e->getMessage();
            }
            return response()->json(['message' => $message], $code);
        }        
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password_old' => 'required',
            'password' => 'required|min:8|confirmed|regex:'.$this->regex
        ]);

        try {
            $user = User::findOrFail($id);
            if (Hash::check($request->input('password_old'), $user->password)) {
                try {
                    $hasher = app()->make('hash');
                    $password = $hasher->make($request->input('password'));

                    $user->update([
                        'username' => $request->input('username'),
                        'password' => $password
                    ]);
                    return response()->json($user, 200);
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json(['message' => $e->getMessage()], 500);
                }
            } else {
                return response()->json(['message' => 'Incorrect username or password.'], 422);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        return response('Deleted successfully', 200);
    }

    public function recover()
    {
        $this->validate($request, [
            'username' => 'required'
        ]);

        $user = User::where('username', $request->input('username'))->first();
        if (!empty($user)) {
            try {
                $recovery_token = sha1($user->id.time());

                $user->update(['api_token' => $api_token]);
                return response()->json($user, 200);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['message' => $e->getMessage()], 500);
            }
        } else {
            return response()->json(['message' => 'Incorrect username or password.'], 401);
        }        
    }
}