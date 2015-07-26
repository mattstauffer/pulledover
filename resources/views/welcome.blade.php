<!DOCTYPE html>
<html>
    <head>
        <title>Pulled Over</title>

        <style>
            html, body {
                height: 100%;
            }

            body {
                font-family: arial;
                font-size: 20px;
                margin: 0;
                padding: 0;
                width: 100%;
            }

            .container {
                margin: 0 auto;
                max-width: 40rem;
                text-align: center;
            }

            .content {
                margin-top: 5rem;
                text-align: center;
            }

            .title {
                font-size: 4rem;
            }

            .the-goal {
                background: #eee;
                border: 1px solid #ddd;
                border-radius: 0.75rem;
                font-size: 1.5rem;
                padding: 1rem;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">Pulled Over</div>
                <p class="the-goal"><strong>The goal</strong>: Give you a free app that is simple to set up and allows you to turn it on when you get pulled over, and it'll notify your friends or family, and start recording, and hopefully also capture geographic coordinates.</p>
                <p><strong>The reason</strong>: I've talked to a lot of people very close to me, especially women of color, who are legitimately terrified of what might happen if they get pulled over by a cop in the U.S. (and no, not all cops are bad, etc. etc., but this is still a completely legitimate fear). While this tool can't alleviate that terror, I hope it can at least be a first step towards helping.</p>
                <p><strong>How it works right now</strong>: It will likely be a phone app later. Right now, it's just a phone number. The goal is to let you set up your contacts but for the current version, it just texts you a link back with a recording of everything that happened. Not all that useful, I know.</p>
                <p><strong>Current limits:</strong> Because of the relatively limited usefulness of the current prototype, I've limited the prototype to record only 15 seconds of audio, and it'll delete your recording after a day, until I add appropriate constraints (and hopefully get some financial backing).</p>
                <p>Call now: <a href="tel:+18443116837">1-844-311-OVER</a> (<a href="tel:+18443116837">1-844-311-6837</a>)</p>
                <br><hr><br>
                <p><strong>Questions:</strong></p>
                <ul>
                    <li><strong>How can I help?</strong> If you're a coder, make some <a href="http://github.com/mattstauffer/pulledover">pull requests</a> (or if you're a mobile developer, message me at <a href="http://twitter.com/stauffermatt">@stauffermatt</a>). If you have money and want to support this, message me at <a href="http://twitter.com/stauffermatt">@stauffermatt</a></li>
                    <li><strong>When will the real app be ready?</strong> As soon as I can make time for it. I'll be adding to it incrementally every day as quickly as I can find free time. I don't plan to make money from this, though, so it can't take too much time from my day job.</li>
                </ul>
                <br><hr><br>
                Built by <a href="http://mattstauffer.co/">Matt Stauffer</a>, powered by <a href="http://twilio.com/">Twilio</a> and <a href="http://laravel.com/">Laravel</a>, source on <a href="http://github.com/mattstauffer/pulledover">GitHub</a>
            </div>
        </div>
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
    </body>
</html>
