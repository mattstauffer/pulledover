@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <h2>Add Your Friend's Phone Number</h2>
                {!! BootForm::open()->action(route('friends.store')) !!}
                    {!! BootForm::text('Name', 'name')->autofocus() !!}
                    {!! BootForm::text('Number (xxxxxxxxxx, no dashes, with area code)', 'number') !!}
                    {!! BootForm::submit('Add New Friend') !!}
                {!! BootForm::close() !!}
            </div>
        </div>
    </div>
@endsection



