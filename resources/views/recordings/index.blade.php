@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <h2>Recordings</h2>

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
                            <th>{{ $recording->from }}</th>
                            <th>{{ $recording->city }}</th>
                            <th>{{ $recording->state }}</th>
                            <th><a href="{{ $recording->url }}">{{ $recording->url }}</a></th>
                            <th>{{ $recording->duration }}s</th>
                            <th>{{ $recording->created_at }}</th>
                        </tr>
                    @empty
                        <tr>
                            <td>No recordings!</td>
                        </tr>
                    @endforelse
                </table>
            </div>
        </div>
    </div>
@endsection
