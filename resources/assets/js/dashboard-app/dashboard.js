"use strict";

import moment from 'moment';
import _ from 'lodash';
import Vue from 'vue';
import UserDetails from './user-details/user-details.js';

Vue.config.debug = true;

Vue.directive('selectable-table', {
    bind(){
        $(this.el)
            // wanna be able to jump to the table
            .attr('tabindex', 1).addClass('selectable')

            //and set some stuff on the parent
            .parent().addClass('selectable-table-wrapper')

            //prevent body scroll so table keyboard nav works (is there a better way to do this?)
            .hover(function () {
                $('body').css({overflow: 'hidden'})
            }, function () {
                $('body').css({overflow: 'auto'})
            })

            // select pre/next with up and down keys
            .keydown(function (e) {
                let row = $('tr.selected', this);

                console.log(row);

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
                    default :
                    {
                        return true;
                    }
                }

                row.click();
                return true;
            })
    }
});

new Vue({
    el      : '#admin-dashboard',
    template: require('./dashboard.html'),

    data: {
        users       : [],
        user        : null,
        startOfMonth: moment().startOf('month'),
        orderKey    : 'secondsThisMonth',
        order       : -1
    },

    components: {
        'user-details': UserDetails
    },

    methods: {
        orderBy(key){
            if (this.orderKey == key) {
                this.order = this.order * -1;

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
            user.recordings = _.sortBy(recordings, 'created_at').reverse();
            user.calls = user.recordings.length;
            user.seconds = _.sumBy(recordings, 'duration');

            //monthly totals
            user.recordingsThisMonth = recordings.filter(r => {
                return r.created_at.isAfter(this.startOfMonth);
            });

            user.callsThisMonth = user.recordingsThisMonth.length;
            user.secondsThisMonth = _.sumBy(user.recordingsThisMonth, 'duration');

            return user;
        });

        this.users = _.sortBy(users, this.orderKey).reverse();
        this.user = this.users[0];
    }
});