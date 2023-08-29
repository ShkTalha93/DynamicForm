<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

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
                    return response()->json('Admin Login Successful!', 200);
                } else {
                    //return redirect('/customer');
                    return response()->json('Customer Login Successful!', 200);
                }

            } else {
                return response()->json(['message' => 'Invalid Email or Password'], 401);
            }

        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => 'Error Occured :Please Enter Both Email and Password']);
        }

        // return $next($request);
    }
}