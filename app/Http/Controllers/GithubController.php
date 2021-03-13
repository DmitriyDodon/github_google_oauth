<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class GithubController
{
    public function callback(Request $request)
    {
        $link = 'https://github.com/login/oauth/access_token';
        $code = $request['code'];
        $parameters = [
            'client_id' => env('GITHUB_OAUTH_CLIENT_ID'),
            'client_secret' => env('GITHUB_OAUTH_CLIENT_SECRET'),
            'code' => $code,
            'redirect_uri' => env('GITHUB_OAUTH_REDIRECT_URI')
        ];

        $link .= '?' . http_build_query($parameters);

        $response = Http::post($link);

        if ($response->ok()) {
            $body = $response->body();
        } else {
            throw new \Exception('Oooops there is some problems with authorization.');
        }


        $data = [];
        parse_str($body, $data);
        if (!isset($data['access_token'])) {
            return new RedirectResponse('/');
        }
        $token = $data['access_token'];

        $link = 'https://api.github.com/user';

        $response = Http::withHeaders(['Authorization' => 'token ' . $token])->get($link);

        if ($response->ok()) {
            $user_data = $response->json();
        } else {
            throw new \Exception('Oooops there is some problems with authorization.');
        }

        $user_info = ['password' => Hash::make($user_data['node_id']), 'name' => $user_data['name'] , 'avatar' => $user_data['avatar_url'] ];



        $link = 'https://api.github.com/user/emails';

        $response = Http::withHeaders(['Authorization' => 'token ' . $token])->get($link);

        if ($response->ok()) {
            $user_email = $response->json();
        } else {
            throw new \Exception('Oooops there is some problems with authorization.');
        }

        $user_info['email'] = $user_email[0]['email'];

        if (null === ($user = User::where('email' , $user_info['email'])->first())) {
           $user = User::create($user_info);
        }

        Auth::login($user);
        return new RedirectResponse('/user');
    }
}
