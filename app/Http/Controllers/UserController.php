<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController
{
    public function login()
    {
        $linkGit = 'https://github.com/login/oauth/authorize';

        $parameters = [
            'client_id' => env('GITHUB_OAUTH_CLIENT_ID'),
            'redirect_uri' => env('GITHUB_OAUTH_REDIRECT_URI'),
            'scope' => 'user,user:email'
        ];

        $user = Auth::user() ?? null;
        $linkGit .= '?' . http_build_query($parameters);
        return view('reg.reg', compact('linkGit' , 'user' ));
    }

    public function store(Request $request){
        $id = User::where('email' , $request['email'])->first()->id ?? null;

        $data = $request->validate([
            'email' => ['email' , 'min:5' , "unique:users,email,$id"] ,
            'password' => ['min:4']
        ]);

        if (Auth::attempt($data)){
            return new RedirectResponse('/user');
        }

        return back()->withErrors([
            'email' => 'Doesn\'t matches with our records.'
        ]);
    }

    public function showUser(){
        $user = Auth::user() ?? null;
        return view('user.user' , compact('user'));
    }




    public function logout()
    {
        Auth::logout();
        return new RedirectResponse('/');
    }
}
