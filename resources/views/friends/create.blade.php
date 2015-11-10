@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <h2>Add Your Friend's Phone Number</h2>
                {!! BootForm::open()->action(route('friends.store')) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! BootForm::text('Name', 'name')->autofocus() !!}
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="control-label" for="number">Number (xxxxxxxxxx, no dashes, with area code)</label>
                        <div class="input-group">
                            <div class="input-group-addon">+1</div>
                            <input type="text" name="number" id="number" class="form-control" autofocus="autofocus" placeholder="5552221234">
                        </div>
                    </div>
                </div>

                {!! BootForm::submit('Add New Friend') !!}
                {!! BootForm::close() !!}
            </div>
        </div>
    </div>
@endsection



