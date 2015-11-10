@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <a href="{{ route('numbers.create') }}" class="pull-right btn btn-primary">Add New Phone Number</a>
                <h2>Numbers</h2>
                <p class="intro">In order to identify incoming phone calls with your account, you need to verify each of your phone numbers you want to be able to call from.</p>

                <h3>My phone numbers</h3>
                @if ($numbers->count() == 0)
                    <p>You haven't added any phone numbers yet. Why don't you <a href="{{ route('numbers.create') }}">add one</a>?</p>
                @else
                    @foreach ($numbers as $i => $number)
                        <h4 class="number">{{ $number->number }}
                            <i class="glyphicon glyphicon-{{ $number->is_verified ? 'checked number--verified' : 'unchecked number--unverified' }}"></i>
                            <span class="number__label">{{ $number->is_verified ? 'Verified' : 'Un-verified' }}</span>
                        </h4>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@stop
