/*global define: false, require: true */
define([
    'jquery',
    'underscore',
    'backbone'
], function ($, _, Backbone) {

    var AppRouter,
        initialize;

    AppRouter = Backbone.Router.extend({
        // we'll define routes in a moment
        routes:     {
            '': 'homeAction',
            'page/:module': 'moduleAction',
            'call/:module': 'callAction'
        },

        // set up default routes
        initialize: function () {
            _.bindAll(this, 'setEnv', 'homeAction', 'callAction', 'moduleAction', 'loadModule');
            /*
            var router = this,
                routes = [
                    [ /^page\/([a-z]+)$/, 'getPage', this.moduleAction ],
                    [ /^call\/([a-z]+)$/, 'getCall', this.callAction ],
                    [ '', 'home', this.homeAction ]
                ];

            _.each(routes, function (route) {
                router.route.apply(router, route);
            });
            */
        },

        loadModule: function (module) {
            console.log('load: ', module);
            require([module], function (module) {
                module();
            }, function (err) {
                //The errback, error callback
                //The error has a list of modules that failed
                var failedModule = err.requireModules && err.requireModules[0];
                console.log('canot loadmodule: ', failedModule);
            });
        },

        moduleAction: function (module) {
            console.log('load module ', module);
            this.setEnv(module);
            //module = '../modules/' + module + '/js/main';
            module = 'modulesSrc/' + module + '/js/main';
            this.loadModule(module);
        },

        callAction: function (module) {
            console.log('call action', module);

            if (typeof this.lastEnvClass === 'undefined' || this.lastEnvClass === '') {
                this.navigate('', {trigger: true, replace: true});
            } else {
                this.setEnv(module);
            }
        },

        homeAction: function () {
            console.log('home action');
            //var module = '../modules/home/js/main';
            var module = 'home';
            this.setEnv(module);
            this.loadModule(module);
        },

        setEnv: function (envClass) {
            console.log('envClass', envClass);

            var body = $('body'),
                that = this;

            if (typeof that.lastEnvClass !== 'undefined') {
                body.removeClass(that.lastEnvClass);
            }
            body.addClass(envClass);
            this.lastEnvClass = envClass;
        }

    });

    initialize = function () {
        var app_router = new AppRouter();

        // Extend the View class to include a navigation method goTo
        Backbone.View.prototype.goTo = function (loc, trigger) {
            if (typeof trigger === 'undefined') {
                trigger = true;
            }

            app_router.navigate(loc, {trigger: trigger});
        };

        Backbone.history.start();
        return app_router;
    };

    return {
        initialize: initialize
    };
});