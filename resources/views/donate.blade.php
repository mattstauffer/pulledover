@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="well sales-well">
                    <h2>Donate</h2>
                    <p>For now, if you want to give, you can send me cash directly using <a href="https://cash.me/$stauffermatt">Square Cash</a>.</p>
                    <p>
                        <a href="https://cash.me/$stauffermatt" class="big-green-button big-donate-button">Donate now</a>
                    </p>
                    <p><strong>All donations just go toward covering the costs of running the service. They will pay for hosting costs, Twilio costs, and to pay off the initial $200 investment on design.</strong></p>
                    <P>But my desire is to add a donation method with greater transparency. If you know one, please <a href="http://twitter.com/stauffermatt">ping me on Twitter</a>.</p>
                    <h3>Why Donate?</h3>
                    <p>Because Twilio, the service that sends the texts and records your voice, costs money. As does hosting, and as did the design I paid to get done for this site. And right now that money is coming out of my bank account. If PulledOver survives, it will require some financial assistance.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
