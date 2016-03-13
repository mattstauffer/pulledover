"use strict";

import Recording from './recording/recording.js';

module.exports = {
    template:require('./user-details.html'),

    props:['user'],

    data(){
        return {
            recording:false
        }
    },

    components:{
        recording:Recording
    },

    ready(){

        this.$watch('user', function(u){
            this.recording = u.recordings[0];
        },{
            immediate:true
        });
    }
};