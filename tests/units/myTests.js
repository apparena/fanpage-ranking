define([
    'jquery',
    'underscore',
    'backbone'
], function ($, _, Backbone) {
    'use strict';

    var run = function () {

        module('App core', {
            setup: function () {
                // is is run before each test
            },

            teardown: function () {
                // is is run after each test
            }
        });

        test('loaded app dependencies', function () {
            expect(3);
            ok($, 'jQuery is loaded');
            ok(_, '_ is loaded');
            ok(Backbone, 'Backbone is loaded');
        });

    };

    return {run: run};
});