@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <h2>Add Your Phone Number</h2>
                {!! BootForm::open()->action(route('numbers.store')) !!}
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="control-label" for="number">Number (xxxxxxxxxx, no dashes, with area code)</label>
                        <div class="input-group">
                            <div class="input-group-addon">+1</div>
                            <input type="text" name="number" id="number" class="form-control" autofocus="autofocus" placeholder="5552221234">
                        </div>
                    </div>
                </div>
                {!! BootForm::submit('Add New Number') !!}
                {!! BootForm::close() !!}
            </div>
        </div>
    </div>
@endsection



