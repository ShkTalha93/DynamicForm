<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;


class Redirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
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
                    //return redirect('/admin');
                    $accessToken = $user->createToken('MyAppToken')->accessToken;

                    return response()->json(['role' => 'admin', 'token' => $accessToken], 200);
                } else {
                    //return redirect('/customer');
                    // $accessToken = $user->createToken('MyAppToken')->accessToken;
                    $accessToken = $user->createToken('MyAppToken')->accessToken;

                    return response()->json(['role' => 'user', 'token' => $accessToken], 200);
                }

            } else {
                return response()->json(['message' => 'Invalid Email or Password'], 401);
            }

        } catch (\Exception $e) {
            // Handle other exceptions
            \Log::error($e);
            return response()->json(['message' => $e->getMessage()]);
        }

        // return $next($request);
    }
}