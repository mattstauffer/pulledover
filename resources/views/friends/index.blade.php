@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h2>Friends</h2>
                <table class="table">
                    @forelse ($friends as $i => $friend)
                        @if ($i === 0)
                        <thead>
                            <tr>
                                @foreach ($friend->toArray() as $col => $b)
                                    <th>{{ $col }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        @endif
                        <tr>
                            @foreach ($friend->toArray() as $field)
                            <td>{{ $field }}</td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td>No friends!</td>
                        </tr>
                    @endforelse
                </table>
            </div>
        </div>
    </div>
@endsection
