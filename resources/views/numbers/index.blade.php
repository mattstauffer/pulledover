@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <a href="{{ route('numbers.create') }}" class="pull-right btn btn-primary">Add New Phone Number</a>
                <h2>Numbers</h2>
                <table class="table">
                    @forelse ($numbers as $i => $number)
                        @if ($i === 0)
                        <thead>
                            <tr>
                                @foreach ($number->toArray() as $col => $b)
                                    <th>{{ $col }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        @endif
                        <tr>
                            @foreach ($number->toArray() as $field)
                            <td>{{ $field }}</td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td>No numbers!</td>
                        </tr>
                    @endforelse
                </table>
            </div>
        </div>
    </div>
@endsection
