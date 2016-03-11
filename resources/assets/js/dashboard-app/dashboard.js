"use strict";

import moment from 'moment';
import _ from 'lodash';
import Vue from 'vue';
import User from './user/user.js';

Vue.config.debug = true;

new Vue({
    el      : '#admin-dashboard',
    template: require('./dashboard.html'),

    data: {
        users       : [],
        user        : null,
        startOfMonth: moment().startOf('month'),
        orderKey    : 'minutesThisMonth',
        order       : -1
    },

    components: {
        user: User
    },

    methods: {
        orderBy(key){
            if(this.orderKey == key){
                this.order = this.order *-1;

                return;
            }

            this.orderKey = key;
        }
    },

    ready: function () {
        var users = $(this.$el).data('users');

        //todo move to a class
        users = users.map(user => {

            var recordings = user.recordings.map(rec => {
                rec.created_at = moment(rec.created_at);

                return rec;
            });

            //totals
            user.recordings = _.sortBy(recordings, 'created_at');
            user.calls = user.recordings.length;
            user.minutes = _.sumBy(recordings, 'duration');

            //monthly totals
            user.recordingsThisMonth = recordings.filter(r => {
                return r.created_at.isAfter(this.startOfMonth);
            });

            user.callsThisMonth = user.recordingsThisMonth.length;
            user.minutesThisMonth = _.sumBy(user.recordingsThisMonth, 'duration');

            return user;
        });

        this.users = _.sortBy(users, this.orderKey).reverse();
        this.user = this.users[0];

        $(this.$els.usage_table)
            .hover(function () {
                $('body').css({overflow: 'hidden'})
            }, function () {
                $('body').css({overflow: 'auto'})
            });

        let table = $(this.$els.usage_table).keydown(e => {
            let row = $('tr.selected');

            switch (e.keyCode) {
                case 38:
                {
                    row = row.prev();
                    break;
                }
                case 40:
                {
                    row = row.next();
                    break;
                }
                case 9:{
                    $(document).scrollTop($(this.$els.user).offset().top);
                    break;
                }
                default :
                {
                    console.log(e.keyCode);
                    return true;
                }
            }

            row.click();
            return true;
        });
    }
});