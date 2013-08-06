/*global define: false */
define([
    'underscore',
    'views/home/OverviewView',
    'modulesSrc/fangate/js/views/FangateView'
], function (_, OverviewView, FangateView) {
    'use strict';

    return function () {
        var overviewView = new OverviewView(),
            fangateView = new FangateView();

        // check for fangate
        // ToDo[marcus] add local storage check if accepted
        if (_.aa.config.mod_fangate_activated.value === '1') {
            fangateView.openFangate();
        }
    };
});