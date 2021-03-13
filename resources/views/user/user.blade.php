@extends('layout')

@section('content')
{{--    @dd(asset($user->avatar))--}}
    @auth
        <div class="card" style="width: 18rem;" align="center">
            <img src="{{$user->avatar}}" class="card-img-top" alt="avatar">
            <div class="card-body" align="center">
                <h5 class="card-title">{{$user->name}}</h5>
                <p class="card-text">{{$user->email}}</p>
                <a href="/logout" class="btn btn-primary">Logout</a>
            </div>
        </div>
    @endauth
@endsection
