<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{


    public function store(Request $request)
    {

        try {

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:user',
                'password' => 'required|string|min:8|regex:/[0-9]/',
                'phone' => 'required|string|max:20',
                'is_admin' => 'nullable|boolean|in:1,0',

            ]);


            $validatedData['password'] = bcrypt($validatedData['password']);
            $users = new User($validatedData);

            $users->save();
            $token = $users->createToken('api-token')->plainTextToken;


            return response()->json(['message' => 'User registered successfully', 'token' => $token]);
        } catch (QueryException $e) {
            // Handle database-related exceptions
            print_r('Query Exception: ' . $e->getMessage());
            return response()->json(['message' => 'Database Query Error']);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => 'Error Occured :Please Enter Valid Data']);
        }

    }
    public function login(Request $request)
    {

        try {

            $validatedData = $request->validate([
                'email' => 'required',
                'password' => 'required',
            ]);
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                if ($user->is_admin) {
                    return response()->json('Admin', 200);
                } else
                    return response()->json('Customer', 200);

                // return response()->json(['message' => 'Login Successful!'], 200);
            } else {
                return response()->json(['message' => 'Invalid Email or Password'], 401);
            }


        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => $e->getMessage()]);
        }

    }


}