@extends('layout')

@section('title' , 'Registration')

@section('content')
    @guest
    <form method="post">
        @csrf
        @if($errors->has('email'))
            @foreach($errors->get('email') as $email)
                <div class="alert alert-danger" role="alert">
                    {{$email}}
                </div>
            @endforeach
        @endif
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>

        @if($errors->has('password'))
            @foreach($errors->get('password') as $password)
                <div class="alert alert-danger" role="alert">
                    {{$password}}
                </div>
            @endforeach
        @endif

        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="exampleInputPassword1">
        </div>
        <div class="d-flex justify-content-center">
            <a class="mx-2" href="{{ $linkSpotify }}"><img width="50" height="50" src="https://cdn2.iconfinder.com/data/icons/font-awesome/1792/spotify-512.png" alt="G+"></a>
            <a class="mx-2" href="{{ $linkGit }}"><img width="50" height="50" src="https://cdn.iconscout.com/icon/free/png-512/github-154-675675.png" alt="GitHUB"></a>
            <button type="submit" class="btn btn-primary mx-2">Log in</button>
        </div>
    </form>
    @endguest
@endsection
