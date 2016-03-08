"use strict";

Vue.config.debug = true;
var chartOptions = {
    chartPadding: {
        right: 40
    },
    axisY       : {
        position   : 'end',
        onlyInteger: true
    },
    axisX       : {
        // On the x-axis start means top and end means bottom
        position: 'start'
    },
    lineSmooth  : Chartist.Interpolation.none(),
    plugins     : [
        Chartist.plugins.ctAxisTitle({
            axisX: {
                axisTitle : 'Created',
                axisClass : 'ct-axis-title',
                offset    : {
                    x: 0,
                    y: 0
                },
                textAnchor: 'middle'
            },
            axisY: {
                axisTitle : 'Duration',
                axisClass : 'ct-axis-title',
                offset    : {
                    x: 0,
                    y: -50
                },
                textAnchor: 'middle',
                flipTitle : false
            }
        })
    ]
};

var Recordings = Vue.extend({
    template: '#recordings-template',
    props   : ['recordings', 'index'],
    data    : function () {
        return {
            selectedRecording: null
        }
    },
    methods : {
        selectRecording: function (e) {
            //will set recording if point clicked and clear otherwise
            this.selectedRecording = this.recordings[e.target.getAttribute('ct:meta')];

            // reset all points to default color
            $('.ct-point').css({stroke: '#d70206'});

            if (this.selectedRecording) {
                $(e.target).css({stroke: 'blue'});

                this.$nextTick(function () {
                    this.$els.audio.load();
                });
            }
        }
    },
    ready   : function () {
        new Chartist.Line("#chart-" + this.index, {
            // axisX data
            labels: this.recordings.map(function (r) {
                return r.created_at.fromNow();
            }),

            //axisY data (could be separated by phone number)
            series: [
                this.recordings.map(function (r, i) {
                    return {
                        value: r.duration,
                        meta : i // this is what we will look for on click in recordings::selectRecording
                    };
                })
            ]
        }, chartOptions);
    }
});

var Phone = Vue.extend({
    template: '#phone-template',
    props   : ['phone'],
    data    : function () {
        return {
            iconClass: {
                // todo use font-awesome mixin to make number--[un]verified an icon
                'fa-check-square-o': this.phone.is_verified,
                'number--verified' : this.phone.is_verified,

                'fa-square-o'       : !this.phone.is_verified,
                'number--unverified': !this.phone.is_verified
            }
        }
    },
    filters : {
        phone: function (number) {
            return '(' + number.substring(0, 3) + ') ' + number.substring(3, 6) + '-' + number.substring(6)
        }
    }
});

var User = Vue.extend({
    template  : '#user-template',
    props     : ['user', 'index'],
    components: {
        phone     : Phone,
        recordings: Recordings
    },
    filters   : {
        thisMonth: function (arr) {

        }
    }
});

new Vue({
    el  : '#admin-dashboard',
    data: {
        users: []
    },

    components: {
        user: User
    },

    filters: {
        chunk: function (items, count) {
            return _.chunk(items, count);
        }
    },

    ready:function(){
        var users = $(this.$el).data('users');
        console.log(users);
        var startOfMonth = moment().startOf('month');

        //todo move to a class
        users = users.map(function (user) {
            var recordings = user.recordings.map(function (rec) {
                rec.created_at = moment(rec.created_at);

                return rec;
            });

            //totals
            user.recordings = _.sortBy(recordings, 'created_at');
            user.calls      = user.recordings.length;
            user.minutes    = _.sumBy(recordings, 'duration');

            //monthly totals
            user.recordingsThisMonth = recordings.filter(function (r) {
                return r.created_at.isAfter(startOfMonth);
            });
            user.callsThisMonth      = user.recordingsThisMonth.length;
            user.minutesThisMonth    = _.sumBy(user.recordingsThisMonth, 'duration');

            return user;
        });

        this.users = _.sortBy(users, 'minutes').reverse();
    }
});