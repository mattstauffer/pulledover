@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
        <style>hr { border-color: #888; }</style>
            <h2>Users</h2>
            <hr>
            @foreach (\App\User::all() as $user)
                <h3>{{ $user->name }} ({{ $user->email}})</h3>
                <h4>Phones</h4>
                <ul>
                    @foreach ($user->phoneNumbers as $number)
                    <li>{{ $number->formattedNumber }} <i class="fa fa-{{ $number->is_verified ? 'check-square-o number--verified' : 'square-o number--unverified' }}"></i></li>
                    @endforeach
                </ul>
                <h4>Friends</h4>
                <ul>
                    @foreach ($user->friends as $number)
                    <li>{{ $number->formattedNumber }} <i class="fa fa-{{ $number->is_verified ? 'check-square-o number--verified' : 'square-o number--unverified' }}"></i></li>
                    @endforeach
                </ul>
                <h4>Recordings</h4>
                <ul>
                    @foreach ($user->recordings as $recording)
                    <li>Duration: <span style="font-size: {{ $recording->duration > 300 ? 2 : 1 }}em;">{{ $recording->duration / 60 }}m</span>, <a title="{{ json_encode($recording) }}">JSON</a>
                    @endforeach
                </ul>
                <hr>
            @endforeach
        </div>
    </div>
@endsection
