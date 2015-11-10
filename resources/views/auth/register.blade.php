@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Register</div>
                    <div class="panel-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="well">
                            <p><strong>Please note:</strong> Any recordings made using this product are <em>not</em> private. If your situation becomes worthy of investigation&mdash;that is, if something <em>does</em> happen while you're recording&mdash;we will help the recordings be available to anyone who needs it.</p>
                            <p>But because the recordings cost me money, I also reserve the right to review any recordings. I will only do so for accounts that look like they're abusing the service, but just know up front: nothing recorded with this app is private. That's not what it's for.</p>
                            <p>Please, please, don't abuse the service and make me have to shut it down.</p>
                        </div>
                        <form class="form-horizontal" role="form" method="POST" action="{{ route('auth.register') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <label class="col-md-4 control-label">Name</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}" autofocus aria-describedby="nameHelpBlock">
                                    <span id="nameHelpBlock" class="help-block">
                                        This is the name we'll use in text messages to your friends. E.g. "Your friend Kim has been Pulled Over."
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Email Address</label>

                                <div class="col-md-6">
                                    <input type="email" class="form-control" name="email" value="{{ old('email', Input::get('email')) }}" aria-describedby="emailHelpBlock">
                                    <span id="emailHelpBlock" class="help-block">
                                        We need your email address for login and password resets.
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Phone Number</label>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <div class="input-group-addon">+1</div>
                                        <input type="text" class="form-control" name="number" value="{{ old('number', Input::get('number')) }}" aria-describedby="numberHelpBlock" maxlength="10">
                                    </div>
                                    <span id="numberHelpBlock" class="help-block">
                                        Start with the phone number you're most likely to make a phone call from. You can add other numbers later. <strong>U.S. only.</strong> No dashes, just the numbers.
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Register
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
