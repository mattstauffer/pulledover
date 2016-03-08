@extends('layouts.app')


@section('headerScripts')
    <link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
    <style>
        {{--Graph points are so tiny >.<--}}
        .ct-point:hover {
            cursor: pointer;
        }

        hr {
            border-color: #888
        }
    </style>
@stop

@section('content')
    <div class="container" id="admin-dashboard">
        <h2>Users</h2>
        <hr>

        <div class="row" v-for="group in users | chunk 2">
            <div class="col-md-6" v-for="user in group">
                <user :user="user" :index="$index"></user>
            </div>
        </div>
    </div>

    {{--User Template--}}
    <script type="x/template" id="user-template">
        <div class="">
            <div class="panel">
                <div class="panel-heading">
                    <h3>
                        @{{ user.name }} (@{{ user.email}})
                    </h3>
                </div>
                <div class="panel-body">
                    {{--High usage alert--}}
                    <div class="alert alert-danger" role="alert" v-if="user.minutes > 300 || user.calls > 5">
                        High usage!
                    </div>

                    {{--Phone number and Friends--}}
                    <div class="col-sm-12">
                        <div class="row">

                            <div class="col-sm-6">
                                <ul class="fa-ul">
                                    <li>Phones</li>
                                    <phone :phone="phone" v-for="phone in user.phone_numbers"></phone>
                                </ul>

                            </div>

                            <div class="col-sm-6">
                                <ul class="fa-ul">
                                    <li>Friends</li>
                                    <phone :phone="phone" v-for="phone in user.friends"></phone>
                                </ul>
                            </div>
                        </div>

                        <hr>
                    </div>

                    {{--Recordings chart and sums--}}
                    <div class="col-sm-12">
                        <div class="">
                            <ul class="fa-ul">
                                <li>
                                    <i class="fa fa-li fa-phone"></i>
                                    @{{ user.calls }} Calls (@{{ user.callsThisMonth }} This  month)
                                </li>
                                <li>
                                    <i class="fa fa-li fa-clock-o"></i>
                                    @{{ user.minutes }} Minutes (@{{ user.minutesThisMonth }} This  month)
                                </li>
                            </ul>
                        </div>

                        <div class="small">Select a recording from the chart below to view details.</div>

                        <recordings :recordings="user.recordings" :index="user.id"></recordings>
                    </div>
                </div>
            </div>
        </div>
    </script>

    {{--Phone Template--}}
    <script type="x/template" id="phone-template">
        <li>
            @{{ phone.number | phone }}
            <i class="fa fa-li" :class="iconClass"></i>
        </li>
    </script>

    {{--Recordings Template--}}
    <script type="x/template" id="recordings-template">
        <div>
            {{--Chart of recordings by date/duration--}}
            <div class="row">
                <div class="col-sm-12">
                    <div class="well">
                        <div class="ct-chart ct-major-tenth" id="chart-@{{ index }}" v-on:click="selectRecording"></div>
                    </div>
                </div>
            </div>

            {{--Current selecting recording details--}}
            <div class="row" v-if="selectedRecording">
                <div class="col-sm-12">
                    <div class="">
                        Id: @{{ selectedRecording.id }}<br>
                        Sid: @{{ selectedRecording.recording_sid }}<br>
                        Duration: @{{ selectedRecording.duration }}<br>
                        Created: @{{ selectedRecording.created_at }}<br>

                        <audio controls v-el:audio>
                            <source :src="selectedRecording.url" type="audio/wav">
                            Your browser does not support the audio element.
                        </audio>
                    </div>
                </div>
            </div>

        </div>
    </script>
@endsection


@section('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/vue/1.0.17/vue.min.js"></script>
    <script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
    <script src="/js/axis-title.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/4.6.1/lodash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment-with-locales.min.js"></script>

    <script type="text/javascript">

        //todo extract and compile
        Vue.config.debug = true;
        var chartOptions = {
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
            lineSmooth: Chartist.Interpolation.none(),
            plugins: [
                Chartist.plugins.ctAxisTitle({
                    axisX: {
                        axisTitle: 'Created',
                        axisClass: 'ct-axis-title',
                        offset: {
                            x: 0,
                            y: 0
                        },
                        textAnchor: 'middle'
                    },
                    axisY: {
                        axisTitle: 'Duration',
                        axisClass: 'ct-axis-title',
                        offset: {
                            x: 0,
                            y: -50
                        },
                        textAnchor: 'middle',
                        flipTitle: false
                    }
                })
            ]
        };

        var Recordings = Vue.extend({
            template:'#recordings-template',
            props:['recordings','index'],
            data:function(){
                return {
                    selectedRecording:null
                }
            },
            methods:{
                selectRecording:function(e){
                    //will set recording if point clicked and clear otherwise
                    this.selectedRecording = this.recordings[e.target.getAttribute('ct:meta')];

                    // reset all points to default color
                    $('.ct-point').css({stroke:'#d70206'});

                    if(this.selectedRecording){
                        $(e.target).css({stroke:'blue'});

                        this.$nextTick(function(){
                            this.$els.audio.load();
                        });
                    }
                }
            },
            ready:function(){
                new Chartist.Line("#chart-"+this.index, {
                    // axisX data
                    labels:this.recordings.map(function(r){
                        return r.created_at.fromNow();
                    }),

                    //axisY data (could be separated by phone number)
                    series:[this.recordings.map(function(r, i){
                        return {
                            value:r.duration,
                            meta:i // this is what we will look for on click in recordings::selectRecording
                        };
                    })]
                },chartOptions);
            }
        });

        var Phone = Vue.extend({
            template:'#phone-template',
            props:['phone'],
            data:function(){
                return {
                    iconClass:{
                        // todo use font-awesome mixin to make number--[un]verified an icon
                        'fa-check-square-o': this.phone.is_verified,
                        'number--verified': this.phone.is_verified,

                        'fa-square-o': !this.phone.is_verified,
                        'number--unverified': !this.phone.is_verified
                    }
                }
            },
            filters:{
                phone:function(number){
                    return '('+number.substring(0,3)+') '+number.substring(3,6)+'-'+number.substring(6)
                }
            }
        });

        var User = Vue.extend({
            template:'#user-template',
            props:['user', 'index'],
            components:{
                phone:Phone,
                recordings:Recordings
            },
            filters:{
                thisMonth:function(arr){

                }
            }
        });

        new Vue({
            el:'#admin-dashboard',
            data: {
                users:{!! App\User::with(['recordings','phoneNumbers','friends'])->get()->toJson() !!}
            },

            created:function(){
                var startOfMonth = moment().startOf('month');

                //todo move to a class
                var users = this.users.map(function(user){
                    var recordings = user.recordings.map(function(rec){
                        rec.created_at = moment(rec.created_at);

                        return rec;
                    });

                    //totals
                    user.recordings = _.sortBy(recordings, 'created_at');
                    user.calls = user.recordings.length;
                    user.minutes = _.sumBy(recordings, 'duration');

                    //monthly totals
                    user.recordingsThisMonth = recordings.filter(function(r){
                        return r.created_at.isAfter(startOfMonth);
                    });
                    user.callsThisMonth = user.recordingsThisMonth.length;
                    user.minutesThisMonth = _.sumBy(user.recordingsThisMonth, 'duration');

                    return user;
                });

                this.users = _.sortBy(users, 'minutes').reverse();
            },

            components:{
                user:User
            },

            filters:{
                chunk:function(items, count){
                    return _.chunk(items, count);
                }
            }
        });

    </script>
@stop