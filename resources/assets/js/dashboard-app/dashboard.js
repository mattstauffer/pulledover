"use strict";

import Vue from 'vue';
import User from './user.js';

Vue.config.debug = true;

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