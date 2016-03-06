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
        <user v-for="user in users" :user="user" :index="$index"></user>
    </div>

    <script type="x/template" id="user-template">
        <div class="row">
            <div class="panel">
                <div class="panel-heading">
                    <h3>@{{ user.name }} (@{{ user.email}})</h3>
                </div>
                <div class="panel-body">
                    <div class="col-md-6 col-lg-4">
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
                    </div>

                    <div class="col-md-6 col-lg-8">
                        <recordings :recordings="user.recordings" :index="user.id"></recordings>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="x/template" id="phone-template">
        <li>
            @{{ phone.number | phone }}
            <i class="fa fa-li" :class="iconClass"></i>
        </li>
    </script>

    <script type="x/template" id="recordings-template">
        <div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="well">
                        <div class="ct-chart ct-major-tenth" id="chart-@{{ index }}" v-on:click="selectRecording"></div>
                    </div>
                </div>
            </div>

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

    <script type="text/javascript">

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
            lineSmooth: Chartist.Interpolation.none()
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

                    if(this.selectedRecording){
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
                        return r.created_at;
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
            }
        });

        new Vue({
            el:'#admin-dashboard',
            data: {
                users:{!! App\User::with(['recordings','phoneNumbers','friends'])->get()->toJson() !!}
            },

            components:{
                user:User
            }
        });

    </script>
@stop