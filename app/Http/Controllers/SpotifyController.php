<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class SpotifyController
{
    public function callback(Request $request){

        $code = $request['code'];

        $link = 'https://accounts.spotify.com/api/token?';

        $parametrs = [
            'grant_type' => 'authorization_code',
            'code' => $code ,
            'redirect_uri' => config()->get('services.Spotify.redirect_uri'),
            'client_id' => config()->get('services.Spotify.client_id'),
            'client_secret' => config()->get('services.Spotify.client_secret'),
        ];

        $response = Http::asForm()->post($link , $parametrs);

        if ($response->ok()){
            $body = $response->json();
        }else{
            return new RedirectResponse('/');
        }
        $token = $body['access_token'];
        $token_type = $body['token_type'];


        $link = 'https://api.spotify.com/v1/me';

        $response = Http::withHeaders(['Authorization' => "$token_type $token"])->get($link);

        if ($response->ok()){
            $data = $response->json();
        }else {
            throw new \Exception('Oooops there is some problems with authorization.');
        }


        if (null === ($user = User::where('email' , $data['email'])->first())){
            $user = User::create([
                'name' => $data['display_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['id']),
                'avatar' => $data['images'][0] ?? 'https://avatanplus.com/files/resources/original/5ca814ac72fe5169f090c1b7.png',
            ]);
        }

        Auth::login($user);
        return new RedirectResponse('/user');
    }
}
