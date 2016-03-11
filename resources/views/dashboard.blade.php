@extends('layouts.app')

@section('content')
    <div class="container">
        @if (! Auth::user()->dismissed_welcome)
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true" id="dismissWelcome">&times;</button>

                <p><h2 style="margin-top: 0">Welcome to Pulled Over!</h2></p>
                <div style="font-size: 1.25em;">
                    <p>Make sure you remember to add <b><tel>1-844-311-OVER</tel></b> to your phonebook right now, for when you need to call!
                    <p>Next, you'll need to verify your phone number, and then add some friends to be notified if you ever call in.</p>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-sm-1 dashboard-section-icon">
                <i class="fa fa-3x fa-phone"></i>
            </div>
            <div class="col-sm-10" tabindex="0">
                <h2>My Phone Numbers</h2>
                <p class="intro text-muted">In order to identify incoming phone calls with your account, you need to verify each of your phone numbers you want to be able to call from.</p>

                @if ($numbers->count() == 0)
                    <p>You haven't added any phone numbers yet. Why don't you <a href="{{ route('numbers.create') }}">add one</a>?</p>
                @else
                    @foreach ($numbers as $i => $number)
                        <h4 class="number">{{ $number->formattedNumber }}
                            <i class="phone-number {{$number->is_verified ? 'verified':''}}"></i>
                            <span class="number__label">{{ $number->is_verified ? 'Verified' : 'Un-verified' }}</span>
                        </h4>
                    @endforeach
                @endif
                <a href="{{ route('numbers.create') }}" class="btn btn-primary"><i class="fa fa-lg fa-plus-circle"></i> &nbsp;Add New Phone Number</a>
            </div>
        </div>

        <hr class="dashboard-divider">

        <div class="row">
            <div class="col-sm-1 dashboard-section-icon">
                <i class="fa fa-3x fa-users"></i>
            </div>
            <div class="col-sm-10">
                <h2>My Friends' Numbers</h2>
                <p class="intro text-muted">Your friends are the ones who will get notified if you get pulled over.</p>

                @if ($friends->count() == 0)
                    <p>You haven't added any friends yet.
                    @if (Auth::user()->hasAVerifiedPhoneNumber())
                        Why don't you <a href="{{ route('friends.create') }}">add one</a>?
                    @else
                        You can add friends once you verify at least one of your own phone numbers.
                    @endif
                    </p>
                @else
                    @foreach ($friends as $i => $friend)
                        <h4 class="number">{{ $friend->name }} - {{ $friend->formattedNumber }}
                            <i class="phone-number {{$friend->is_verified ? 'verified':''}}"></i>
                            <span class="number__label">{{ $friend->is_verified ? 'Verified' : 'Un-verified' }}</span>
                        </h4>
                    @endforeach
                @endif
                @if (Auth::user()->hasAVerifiedPhoneNumber())
                <a href="{{ route('friends.create') }}" class="btn btn-primary"><i class="fa fa-lg fa-plus-circle"></i> &nbsp;Add New Friend</a>
                @endif
            </div>
        </div>

        <hr class="dashboard-divider">

        <div class="row">
            <div class="col-sm-1 dashboard-section-icon">
                <i class="fa fa-3x fa-headphones"></i>
            </div>
            <div class="col-sm-10">
                <h2>Recordings</h2>

                <p class="intro text-muted">Important note! Recordings are deleted from the server after 10 days to save space. Please have your recordings saved before they're deleted!</p>

                <div class="table-responsive">
                    <table class="table">
                        @forelse ($recordings as $i => $recording)
                            @if ($i === 0)
                            <thead>
                                <tr>
                                    <th>From</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Recording</th>
                                    <th>Duration</th>
                                    <th>Date/Time</th>
                                </tr>
                            </thead>
                            @endif
                            <tr>
                                <th>{{ $recording->formattedFrom }}</th>
                                <th>{{ $recording->city }}</th>
                                <th>{{ $recording->state }}</th>
                                <th>
                                    @if ($recording->created_at->lt(\Carbon\Carbon::parse('-10 days')))
                                    Recording has expired.
                                    @else
                                    <a href="{{ $recording->url }}">(link)</a>
                                    @endif
                                </th>
                                <th>{{ $recording->duration }}s</th>
                                <th>{{ $recording->created_at }}</th>
                            </tr>
                        @empty
                            <tr>
                                <td>No recordings! Remember to add 1-844-311-OVER to your phonebook!</td>
                            </tr>
                        @endforelse
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
$(function () {
    $('#dismissWelcome').on('click', function () {
        $.ajax({
             url: "{{ route('dismiss-welcome') }}",
        });
    });
});
</script>
@endsection
