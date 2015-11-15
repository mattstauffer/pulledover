<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pulled Over</title>

    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header" style="float: left;">
            <a class="navbar-brand" href="/home"><img src="/pulledover-logo.png" alt="Pulled Over" class="logo"></a>
        </div>

        <ul class="nav navbar-nav navbar-right auth-menu">
            @if (Auth::guest())
                <li><a href="{{ route('auth.login') }}">Login</a></li>
                <li><a href="{{ route('auth.register') }}">Register</a></li>
            @else
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                       aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <!-- <li><a href="#">Edit Profile</a></li> -->
                        <li><a href="{{ route('auth.logout') }}">Logout</a></li>
                    </ul>
                </li>
            @endif
        </ul>
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
        <p class="text-muted">1-844-311-OVER</p>
    </div>
</footer>

<!-- Scripts -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>

@yield('scripts')

</body>
</html>
