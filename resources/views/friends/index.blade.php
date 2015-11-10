@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <a href="{{ route('friends.create') }}" class="pull-right btn btn-primary">Add New Friend</a>
                <h2>Friends</h2>
                <p class="intro">Your friends are the ones who will get notified if you get pulled over.</p>

                <h3>My friends</h3>
                @if ($friends->count() == 0)
                    <p>You haven't added any friends yet. Why don't you <a href="{{ route('friends.create') }}">add one</a>?</p>
                @else
                    @foreach ($friends as $i => $friend)
                        <h4 class="number">{{ $friend->name }} - {{ $friend->number }}
                            <i class="glyphicon glyphicon-{{ $friend->is_verified ? 'checked number--verified' : 'unchecked number--unverified' }}"></i>
                            <span class="number__label">{{ $friend->is_verified ? 'Verified' : 'Un-verified' }}</span>
                        </h4>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
