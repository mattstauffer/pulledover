"use strict";

// not sure whats up here...
//var Chartist = require('chartist');
//var AxitTile = require('chartist-plugin-axistitle');

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

module.exports = {
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
};