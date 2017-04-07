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
                <h2>Add Your Phone Number</h2>

                @include('partials.errors')

                {!! BootForm::open()->action(route('numbers.store')) !!}
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="control-label" for="number">Number (xxxxxxxxxx, no dashes, with area code)</label>
                        <div class="input-group">
                            <div class="input-group-addon">+1</div>
                            <input type="text" name="number" id="number" class="form-control" autofocus="autofocus" placeholder="5552221234" value="{{ old('number') }}">
                        </div>
                    </div>
                </div>
                {!! BootForm::submit('Add New Number') !!}
                {!! BootForm::close() !!}

                <br><br>
                <div class="well">
                    <h3 style="margin-top: 0">What does adding my phone number mean?</h3>
                    <p>We'll send you a text message right away to verify that you own this phone number.</p>
                    <p>Then, any time an incoming recording comes from this phone number, it will be associated with your account, which means you'll be able to access the recording here, and it will also text the recording URL back to you and any friends you add here.</p>
                </div>
            </div>
        </div>
    </div>
@endsection



