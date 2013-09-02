/*global define: false */
define([
    'jquery',
    'underscore',
    'backbone',
    'text!modulesSrc/demo/templates/demomodul.html'
], function ($, _, Backbone, testTemplate) {
    var TestView = Backbone.View.extend({

        el: $("#content-wrapper"),

        initialize: function () {
            this.render();
        },

        render: function () {
            var compiledTemplate = _.template(testTemplate, {});
            this.$el.html(compiledTemplate);
        }

    });
    return TestView;
});