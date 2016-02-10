@extends('layouts.app')

@section('content')
<style>
.pitch-column {
    color: #fff;
    font-family: georgia, serif;
    margin-left: 5rem;
    text-align: center;
    width: 500px;
}
.pitch-column a {
    color: #fff;
}
.pitch {
    background: rgba(0, 0, 0, .5);
        margin-top: 13rem;
    padding: 2em;
    text-align: left;
}
.pitch .big-number {
    color: #fff;
    font-family: arial;
    font-size: 5rem;
    font-weight: bold;
}
.pitch-sign-up-button {
    background: #43c25b;
    color: #fff;
    display: inline-block;
    font-size: 1.1em;
    margin: 2em auto;
    padding: 1em 2em;
    font-family: arial;
    font-weight: bold;
    text-transform: uppercase;
}
.pitch-donate-button {
    font-size: 1.2em;
}
</style>
<div class="jumbotron" style="background-size: cover; background-position: 50% 50%; background-image: url('/images/hero-header.jpg'); height: 912px; margin-top: -20px;">
    <div class="container">
        <div class="pitch-column">
            <div class="pitch">
                <p>The next time you're being pulled over by the police and worry for your safety, call this number.</p>
                <p>
                    <a href="tel:+18443116837" class="big-number">1 (844) 311-OVER</a>
                </p>
                <p>When you hang up, a recording of everything that happened will be sent to you and your friends.</p>
            </div>
            <a href="{{ route('auth.register') }}" class="pitch-sign-up-button">Sign up now. It's free</a>
            <br>
            <span class="pitch-donate-button">
                or <a href="/donate">help support development</a>
            </span>
        </div>
    </div>
</div>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <p class="well the-goal"><strong>What's the eventual goal?</strong><br>To provide a free app that is simple to set up and allows you to turn it on when you get pulled over. It will notify your friends or family, start recording audio, and hopefully also capture geographic coordinates.</p>

                <p><strong>The reason</strong>: I've talked to a lot of people very close to me, especially women of color, who are legitimately terrified of what might happen if they get pulled over by a cop in the U.S. (and no, not all cops are bad, etc. etc., but this is still a completely legitimate fear). While this tool can't alleviate that terror, I hope it can at least be a first step towards helping.</p>
                <p><strong>How it will eventually work</strong>: That's still up in the air, but my dream: A mobile app with a big red button. Press the button, it texts your friends with your location. Then it records everything until you stop it (with a passcode). After it's disabled, it'll text you and your friends a link to the recording.</p>

                <p><strong>Questions:</strong></p>
                <ul>
                    <li><strong>How can I help?</strong> If you're a coder, make some <a href="http://github.com/mattstauffer/pulledover">pull requests</a> (or if you're a mobile developer, message me at <a href="http://twitter.com/stauffermatt">@stauffermatt</a>). If you have money and want to support this, message me at <a href="http://twitter.com/stauffermatt">@stauffermatt</a></li>
                    <li><strong>How you can you afford this?</strong> Right now I'm just going to cover it. If people abuse it I may have to shut it down. If it gets legitimately popular I may seek sponsorship from like-minded people (not advertisers, but people who care about civil liberties).</li>
                    <li><strong>Is this legal?</strong> Mostly. I can't make any guarantees of legality&mdash;each user is responsible for ensuring the legality of recording in their particular situation, and I can't guarantee it is. But if you live anywhere other than Illinois or Massachusetts you should be OK, and even in those states you are OK if you ask for the police officer's consent before recording (<a href="https://reason.com/archives/2012/04/05/7-rules-for-recording-police/singlepage">source</a>).</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
