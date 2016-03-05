@extends('layouts.app')

@section('scripts')
    <script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>

    <script type="text/javascript">

        <!-- todo import vue and fire up a component to handle showing the selected recording -->
        // Create a new line chart object where as first parameter we pass in a selector
        // that is resolving to our chart container element. The Second parameter
        // is the actual data object.
        $('.ct-chart').each(function(i, el){
            new Chartist.Line('#'+el.id, {
                labels:$(el).data('labels'),
                series:$(el).data('series')
            },{
                chartPadding: {
                    right: 40
                },
                axisY: {
                    position: 'end',
                    onlyInteger: true
                },
                axisX: {
                    // On the x-axis start means top and end means bottom
                    position: 'start'
                },
                lineSmooth: Chartist.Interpolation.none()
            });

            $('body').on('click', 'line.ct-point:not([aria-described-by])', function (e){
                e.stopImmediatePropagation();

                var meta = $("<div/>").html(e.target.getAttribute('ct:meta')).text();
                meta = JSON.parse(meta).data;
                console.log($('pre').html(JSON.stringify(meta)));

                $('.test').html($('<pre>').html(JSON.stringify(meta, null, 4)));
                window.data = meta;
            })
        });

    </script>
@stop

@section('headerScripts')
    <link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
    <style>
        .copy {
            cursor: copy;
        }

        .ct-point:hover {
            cursor: pointer;
        }

        hr { border-color: #888; }
    </style>
@stop

@section('content')
    <div class="container">
        <div class="">
            <h2>Users</h2>
            <hr>
            @foreach (\App\User::all() as $user)
                <h3>{{ $user->name }} ({{ $user->email}})</h3>
                <h4>Phones</h4>
                <ul>
                    @foreach ($user->phoneNumbers as $number)
                    <li>{{ $number->formattedNumber }} <i class="fa fa-{{ $number->is_verified ? 'check-square-o number--verified' : 'square-o number--unverified' }}"></i></li>
                    @endforeach
                </ul>
                <h4>Friends</h4>
                <ul>
                    @foreach ($user->friends as $number)
                    <li>{{ $number->formattedNumber }} <i class="fa fa-{{ $number->is_verified ? 'check-square-o number--verified' : 'square-o number--unverified' }}"></i></li>
                    @endforeach
                </ul>
                <h4>Recordings</h4>

                <!-- todo extract this, maybe just one level to a custom collection on the recording model -->
                <div
                        id="chart-{{$user->id}}"
                        class="ct-chart ct-major-tenth"
                        data-labels="{{
                                $user->recordings->lists('created_at')->transform(function($r){
                                    return $r->diffForHumans();
                                })->toJson()
                            }}"
                        data-series="[{{
                                $user->recordings->transform(function($r){
                                    return [
                                        'value' => $r->duration,
                                        'meta' => $r->toArray()
                                    ];
                                })->toJson()
                            }}]">
                </div>

                <div class="user-{{$user->id}}-recording-info"></div>
                <hr>
            @endforeach
        </div>
    </div>
@endsection
