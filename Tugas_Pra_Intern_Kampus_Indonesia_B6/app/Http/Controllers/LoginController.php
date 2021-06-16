<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use Auth;
use App\Models\User;

class LoginController extends Controller
{

    public function redirect()
{
    return Socialite::driver('google')->stateless()->redirect();
}

public function callback()
{
    try {
        try {
            $user = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error','Try after some time');
        }
            $existingUser = User::where('google_id', $user->id)->first();

            // $user = Socialite::driver('google')->stateless()->user();
            // $user = User::where('google_id', $user->id)->first();

            if($existingUser){
                Auth::login($existingUser);
                return redirect('/dashboard');
            }else{
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id'=> $user->id,
                    'password' => encrypt('123456dummy')
                ]);
                Auth::login($newUser);
                return redirect('/dashboard');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
   }
}
