"use strict";

import Recordings from './recordings.js';
import Phone from './phone.js';

module.exports = {
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
};