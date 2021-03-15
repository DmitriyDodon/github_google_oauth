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
        $parametersgit = [
            'client_id' => config()->get('services.GitHub.client_id'),
            'redirect_uri' => config()->get('services.GitHub.redirect_uri'),
            'scope' => 'user,user:email'
        ];
        $linkGit .= '?' . http_build_query($parametersgit);


        $linkSpotify = 'https://accounts.spotify.com/authorize';
        $parametersspotify = [
            'response_type' => 'code',
            'client_id' => config()->get('services.Spotify.client_id'),
            'redirect_uri' => config()->get('services.Spotify.redirect_uri'),
            'scope' => 'user-read-email user-read-private ugc-image-upload'
        ];
        $linkSpotify .= '?' . http_build_query($parametersspotify , null , null , PHP_QUERY_RFC3986);


        $user = Auth::user() ?? null;

        return view('reg.reg', compact('linkGit' , 'user' , 'linkSpotify' ));
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
