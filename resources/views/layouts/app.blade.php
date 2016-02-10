<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pulled Over</title>

    <meta name="twitter:card" content="summary_large_image">

    <meta property="og:title" content="Pulled Over" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://pulledover.us" />
    <meta property="og:image" content="https://pulledover.us/images/og-pulledover-logo.png" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:description" content="Call this free number to record any worrisome interactions and have the recording texted to your friends and family." />

    <meta name="description" content="Call this free number to record any worrisome interactions and have the recording texted to your friends and family.">

    <link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:400,300,700' rel='stylesheet' type='text/css'>

    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">

    @yield('headerScripts')
</head>
<body>
<nav class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="{{ Auth::guest() ? '/' : '/home' }}"><img src="/images/pulledover-logo.png" alt="Pulled Over" class="logo"></a>

            <button type="button" class="splash-nav-toggle navbar-toggle pull-right" data-toggle="collapse" data-target="#primary-nav" aria-expanded="true" aria-controls="primary-nav">
                <span class="sr-only">Toggle navigation</span>
                MENU
            </button>
        </div>

        <div id="primary-nav" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right auth-menu">
                <li><a href="{{ route('donate') }}">Donate</a></li>
                @if (Auth::guest())
                    <li><a href="{{ route('auth.register') }}">Sign Up</a></li>
                    <li><a href="{{ route('auth.login') }}">Login</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            @if (Auth::user()->isAdmin())
                            <li><a href="{{ route('admin.index') }}">Admin</a></li>
                            @endif
                            <li><a href="{{ route('auth.logout') }}">Logout</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

@if (session('messages'))
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <p class="btn-lg bg-success">
              @foreach (session('messages') as $message)
                  {{ $message }}<br>
              @endforeach
            </p>
        </div>
    </div>
</div>
@endif

@yield('content')

<footer class="footer">
    <div class="container">
        <p class="text-muted">
            <a href="tel:18443116837" style="font-size: 1.25em;">1-844-311-OVER</a><br>
            Built by <a href="http://mattstauffer.co/">Matt Stauffer</a> | Powered by <a href="http://twilio.com/">Twilio</a> and <a href="http://laravel.com/">Laravel</a> | Source on <a href="http://github.com/mattstauffer/pulledover">GitHub</a></p>
    </div>
</footer>

<!-- Scripts -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>

@if (app()->environment() === 'production')
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-65620560-1', 'auto');
  ga('send', 'pageview');
</script>
@endif

@yield('scripts')

</body>
</html>
