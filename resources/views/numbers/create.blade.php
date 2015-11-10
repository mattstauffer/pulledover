@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <h2>Add Your Phone Number</h2>
                {!! BootForm::open()->action(route('numbers.store')) !!}
                    {!! BootForm::text('Number (xxxxxxxxxx, no dashes, with area code)', 'number')->autofocus() !!}
                    {!! BootForm::submit('Add New Number') !!}
                {!! BootForm::close() !!}
            </div>
        </div>
    </div>
@endsection



