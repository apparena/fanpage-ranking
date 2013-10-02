/*global define: false */
define([
    'underscore',
    'modulesSrc/ranking/js/views/RankingView'
], function (_, RankingView) {
    'use strict';

    return function () {
        console.log('home.js is here');


        var rankingView = new RankingView();
        //console.log(rankingView);
        //console.log(RankingView);

    };
});