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
                                @foreach ($recording->toArray() as $col => $b)
                                    <th>{{ $col }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        @endif
                        <tr>
                            @foreach ($recording->toArray() as $field)
                            <td>{{ $field }}</td>
                            @endforeach
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
