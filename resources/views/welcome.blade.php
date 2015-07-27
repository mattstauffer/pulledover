<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="Pulled Over records your audio and notifies your friends when you get pulled over.">
        <meta name="author" content="Matt Stauffer">
        <meta name="viewport" content="width=device-width">

        <title>Pulled Over</title>

        <style>
            html {
                font-size: 14px;
            }

            html, body {
                height: 100%;
            }

            body {
                font-family: arial;
                margin: 0;
                padding: 0;
                width: 100%;
            }

            .container {
                margin: 0 auto;
                max-width: 40rem;
                padding-left: 1rem;
                padding-right: 1rem;
                text-align: left;
            }

            .content {
                margin-top: 5rem;
                text-align: left;
            }

            .title {
                font-size: 3rem;
            }

            .the-goal {
                background: #eee;
                border: 1px solid #ddd;
                border-radius: 0.75rem;
                font-size: 1.5rem;
                padding: 1rem;
            }

            .call-now {
                font-size: 2rem;
                text-align: center;
            }

            .footer {
                color: #555;
                font-size: 0.75rem;
                margin-bottom: 2rem;
                text-transform: uppercase;
            }


            @media only screen and (min-width: 640px) {
                html {
                    font-size: 20px;
                }

                .title {
                    font-size: 4rem;
                }
            }

        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">Pulled Over</div>
                <p class="the-goal"><strong>The goal</strong>: To provide a free app that is simple to set up and allows you to turn it on when you get pulled over. It will notify your friends or family, start recording audio, and hopefully also capture geographic coordinates.</p>
                <p><strong>The reason</strong>: I've talked to a lot of people very close to me, especially women of color, who are legitimately terrified of what might happen if they get pulled over by a cop in the U.S. (and no, not all cops are bad, etc. etc., but this is still a completely legitimate fear). While this tool can't alleviate that terror, I hope it can at least be a first step towards helping.</p>
                <p><strong>How it works right now</strong>: Right now, it's just a phone number. You can call it, it'll record 15 seconds of your audio and then hang up, and it'll text you a link of the audio afterward. Soon, you'll be able to use this same phone number, paired with a user account on this site, to actually functionally record any time you're pulled over.</p>
                <p><strong>How it will eventually work</strong>: That's still up on the air, but my dream: A mobile app with a big red button. Press the button, it texts your friends with your location. Then it records everything until you stop it (with a passcode). After it's disabled, it'll text you and your friends a link to the recording.</p>
                <p class="call-now">Call now to try it out:<br><a href="tel:+18443116837">1-844-311-OVER</a><br>(<a href="tel:+18443116837">1-844-311-6837</a>)</p>
                <br><hr><br>
                <p><strong>Questions:</strong></p>
                <ul>
                    <li><strong>How can I help?</strong> If you're a coder, make some <a href="http://github.com/mattstauffer/pulledover">pull requests</a> (or if you're a mobile developer, message me at <a href="http://twitter.com/stauffermatt">@stauffermatt</a>). If you have money and want to support this, message me at <a href="http://twitter.com/stauffermatt">@stauffermatt</a></li>
                    <li><strong>When will the real app be ready?</strong> As soon as I can make time for it. I'll be adding to it incrementally every day as quickly as I can find free time. I don't plan to make money from this, though, so it can't take too much time from my day job.</li>
                </ul>
                <br><hr><br>
                <p class="footer">Built by <a href="http://mattstauffer.co/">Matt Stauffer</a>, powered by <a href="http://twilio.com/">Twilio</a> and <a href="http://laravel.com/">Laravel</a>.<br>source on <a href="http://github.com/mattstauffer/pulledover">GitHub.com/mattstauffer/pulledover</a></p>
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
