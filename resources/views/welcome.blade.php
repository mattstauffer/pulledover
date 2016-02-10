@extends('layouts.app')

@section('headerScripts')
    <link href="{{ asset('/css/homepage.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="jumbotron home-page-hero">
    <div class="container">
        <div class="pitch-column">
            <div class="pitch">
                <p>The next time you're being pulled over by the police and worry for your safety, call this number.</p>
                <p>
                    <a href="tel:+18443116837" class="big-number">1 (844) 311-OVER</a>
                </p>
                <p>When you hang up, a recording of everything that happened will be sent to you and your friends.</p>
            </div>
            <a href="{{ route('auth.register') }}" class="big-green-button">Sign up now. It's free</a>
            <br>
            <span class="pitch-donate-button">
                or <a href="/donate">help support development</a>
            </span>
        </div>
    </div>
</div>
<div class="container">
    <div class="row home-page-columns">
        <div class="col-md-6">
            <div class="well home-page-well">
                <h2>Why does this exist?</h2>
                <p>A lot of people very close to me, especially women of color, are legitimately afraid of what might happen if they were pulled over by the police in the United States.</p>
                <p>While I have no intention of suggesting that every law enforcement officer has malicious intent, it is very true that history shows this fear is legitimate. It is my hope that this tool can be a first step towards ensuring that interactions between police and traditionally disadvantaged people groups will end well.</p>
                <p><strong>Is this legal?</strong> Probably. While I can't make any guarantees of legality (each user is responsible for ensuring the legality of recording in their particular situation), you should be OK using PulledOver. However, if you are in Illinois or Massachusetts, you are required to ask for the police officer's consent before you record (<a href="https://reason.com/archives/2012/04/05/7-rules-for-recording-police/singlepage">source</a>).</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="well home-page-well">
                <h2>The future of <strong>Pulled Over</strong></h2>
                <p><strong>How is this free?</strong> Right now I'm covering all costs out of pocket Ibecause I believe it's the right thing to do. If the service is abused I may have to shut it down. If it gets legitimately popular I may seek sponsorship from like-minded people (not advertisers, but people who care about civil liberties).</li>
            <p><strong>How can I help?</strong> If PulledOver has helped you or you believe in this idea, you can <a href="{{ route('donate') }}">donate</a>. I would love to add a full, simple mobile application that works without the phone call&mdash;capturing audio, geographic coordinates, notifying family, friends, and perhaps even social media.</p>
            <p>If you're a coder, you can contribute on <a href="http://github.com/mattstauffer/pulledover">GitHub</a> (or if you're a mobile developer, message me at <a href="http://twitter.com/stauffermatt">@stauffermatt</a>).</p>
            </div>
        </div>
    </div>
</div>
@endsection
