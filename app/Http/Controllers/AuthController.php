<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Auth;
use DB;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $LoginSuccessful = Auth::attempt([
            'name' => $request->input('username'),
            'password' => $request->input('password')
        ]);
    
        if ($LoginSuccessful){
            return redirect()->route('home');
        }

        return redirect()->route('login')->with('error','Invalid credentials');
    }
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|max:20|unique:users,name',
            'password' => 'required'
        ]);

        $avatar_id = null;
        $already_exist = DB::table('avatars')->where('id', $request->input('avatar_url'))->first();
        if ($already_exist) {
            $avatar_id = $already_exist->id;
        }
        else {
            $avatar_id = DB::table('avatars')->insertGetId([
                'image_url'=> $request->input('avatar_url'),
            ]);
        }

        $user = new User();
        $user->name = $request->input('username');
        $user->password = Hash::make($request->input('password'));
        $user->avatar_id =  $avatar_id;
        $user->created_at = now();
        $user->updated_at = now();
        $user->save();

        Auth::login($user);

        return redirect()->route('home');
    }

    public function logout()
    {
        Auth::logout();
        return view('logout');
    }
}
