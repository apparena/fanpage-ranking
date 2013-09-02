/*global define: false */
define([
    'jquery',
    'underscore',
    'backbone',
    'router'
], function ($, _, Backbone, Router) {

    var initialize = function(){
        this.router = Router.initialize();
    };

    return {
        initialize: initialize
    };
});