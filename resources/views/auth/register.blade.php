@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Register</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ route('auth.register') }}">
                            <div class="well">
                                <h3 style="margin-top: 0">Disclaimer</h3>
                                <p><strong>Please note:</strong> Any recordings made using this product are <em>not</em> private. If your situation becomes worthy of investigation&mdash;that is, if something <em>does</em> happen while you're recording&mdash;we will help the recordings be available to anyone who needs it.</p>
                                <p>But because the recordings cost me money, I also reserve the right to review any recordings. I will only do so for accounts that look like they're abusing the service, but just know up front: nothing recorded with this app is private. That's not what it's for.</p>
                                <p>Please, please, don't abuse the service and make me have to shut it down.</p>
                                <p>Finally, <strong>you are responsible for ensuring the legality of recording in each situation</strong>. I don't make any guarantees of the legality of recording in your paritcular situation. See <a href="https://reason.com/archives/2012/04/05/7-rules-for-recording-police/singlepage">this brief article about your rights when recording the police</a> for some guidelines.</p>

                            </div>

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            @include('partials.errors')

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
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" aria-describedby="emailHelpBlock">
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
                                        <input type="text" class="form-control" name="phone_number" value="{{ old('phone_number', Input::get('phone_number')) }}" aria-describedby="numberHelpBlock" maxlength="10" placeholder="5552221234">
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
                                <div class="checkbox col-md-6 col-md-push-4">
                                    <label style="font-weight: bold;">
                                        <input type="checkbox" name="disclaimer" {{ old('disclaimer', false) ? 'checked' : '' }}> I have read and agree with the Disclaimer text in the box above.
                                    </label>
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
