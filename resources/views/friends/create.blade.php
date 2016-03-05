@extends('layouts.app')

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.2.7/inputmask/inputmask.min.js"></script>
    <script type="text/javascript">
        Inputmask({
            mask:'(999) 999-9999',
            removeMaskOnSubmit:true
        }).mask('#number');
    </script>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <h2>Add Your Friend's Phone Number</h2>

                @include('partials.errors')

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
                            <input type="text" name="number" id="number" class="form-control" autofocus="autofocus" placeholder="5552221234" value="{{ old('number') }}">
                        </div>
                    </div>
                </div>

                {!! BootForm::submit('Add New Friend') !!}
                {!! BootForm::close() !!}

                <br><br>
                <div class="well">
                    <h3 style="margin-top: 0">What message will this send to my friend?</h3>
                    <p>Your friend will receive a single text message right now verifying their address. It will say this:</p>
                    <p style="font-style: italic">"Your friend {{ Auth::user()->name }} wants to add you as a friend on Pulled Over. If you want that too, please visit (verification URL here)."</p>
                    <p>After that they won't receive any messages from us ever, unless you initiate a Pulled Over recording.</p>
                </div>
            </div>
        </div>
    </div>
@endsection



