"use strict";

module.exports = {
    template:require('./recording.html'),

    props:['recording'],

    ready(){

        this.$watch('recording', r => {
            $(this.$els.audio).load();
        })
    }
};