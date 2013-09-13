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
            '':                           'homeAction',
            'page/:module':               'moduleAction',
            'page/:module/:filename':     'moduleAction',
            'page/:module/:filename/*id': 'moduleAction',
            'call/*module':               'callAction'
        },

        // set up default routes
        initialize: function () {
            _.bindAll(this, 'setEnv', 'homeAction', 'callAction', 'moduleAction', 'loadModule');
        },

        loadModule: function (module, id) {
            console.log('load: ', module, 'id: ', id);
            require([module], function (module) {
                if (id !== false) {
                    module(id);
                } else {
                    module();
                }
            }, function (err) {
                //The errback, error callback
                //The error has a list of modules that failed
                var failedModule = err.requireModules && err.requireModules[0];
                console.log('canot loadmodule: ', failedModule);
            });
        },

        moduleAction: function (module, filename, id) {
            console.log('load module', module, filename);
            var env = module;

            if (_.isUndefined(filename)) {
                filename = 'main';
            }
            env += '-' + filename;

            if (_.isUndefined(id)) {
                id = false;
            } else {
                env += '-' + id;
            }

            this.setEnv(env);
            this.loadModule('modulesSrc/' + module + '/js/' + filename, id);
        },

        callAction: function (module) {
            console.log('call action', module);

            if (_.isUndefined(this.lastEnvClass) || this.lastEnvClass === '') {
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
            this.loadModule(module, false);
        },

        setEnv: function (envClass) {
            console.log('envClass', envClass);

            var body = $('body');

            if (typeof this.lastEnvClass !== 'undefined') {
                body.removeClass(this.lastEnvClass);
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

        // Extend the View class to make global ajax requests with jquery
        Backbone.View.prototype.ajax = function (data, async) {
            console.log('ajax', data);
            var returnData = {type: 'notReturned', data: {}};

            if (typeof async === 'undefined') {
                async = false;
            }

            // add instance id
            data.aa_inst_id = _.aa.instance.aa_inst_id;

            $.ajax({
                url:      'ajax.php',
                dataType: 'json',
                type:     'POST',
                async:    async,
                data:     data,
                success:  function (response) {
                    returnData.type = 'success';
                    returnData.data = response;
                },
                error:    function (response) {
                    returnData.type = 'error';
                    returnData.data = response;
                }
            });

            return returnData;
        };

        Backbone.history.start();
        return app_router;
    };

    return {
        initialize: initialize
    };
});