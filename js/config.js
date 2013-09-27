//var requirejsElem = document.getElementById('requirejs');

var require = {
    waitSeconds: 60,

    /*
     // uncomment only in develope mode! this make problems in r.js
     urlArgs: (function () {
     // add cache busting for development
     return !!(requirejsElem.getAttribute('data-devmode') | 0)
     ? 'bust=' + Date.now()
     : '';
     })(),
     */

    paths: {

        /* CDN Version, ATTENTION: this are no AMD versions and need additional shims!
         'jquery': '//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min',
         'underscore': '//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.4.4/underscore-min',
         'backbone': '//cdnjs.cloudflare.com/ajax/libs/backbone.js/1.0.0/backbone-min',
         'text': '//cdnjs.cloudflare.com/ajax/libs/require-text/2.0.5/text',
         */

        'jquery':       'vendor/jquery/jquery',
        'underscore':   'vendor/underscore-amd/underscore',
        'backbone':     'vendor/backbone-amd/backbone',
        'bootstrap':    'vendor/bootstrap-assets/js/bootstrap',

        // vendor extensions
        'text':         'vendor/requirejs-text/text',
        'localstorage': 'vendor/backbone.localStorage/backbone.localStorage',
        //facebook:     'vendor/backbone.api.facebook/backbone.api.facebook'
        'facebook':     '//connect.facebook.net/en_US/all',
        'fb_share':     'utils/fb_share',
        'tinysort':     'vendor/tinysort/jquery.tinysort',

        // graph
        'AA.facebook':  'vendor/AA.facebook/js/AA.facebook',
        'AA.contest':  'vendor/AA.contest/js/AA.contest',
        'jquery.flot.min':  'vendor/jquery.flot.min/jquery.flot.min',
        'jquery.flot.resize.min':  'vendor/jquery.flot.resize.min/jquery.flot.resize.min',
        'jquery.flot.time':  'vendor/jquery.flot.time/jquery.flot.time',
        'imperio.general':  'vendor/imperio.general/js/imperio.general',
        'select2.min':  'vendor/select2/js/select2.min',


        // directory settings
        'templates':    '../templates',
        'modulesSrc':   '../modules',
        'rootSrc':      '../js',
        'units':        '../units',

        // unit testing
        'QUnit':        '//code.jquery.com/qunit/qunit-git',
        'sinon':        '//sinonjs.org/releases/sinon-1.7.3.js',
        'sinon-ie':     '//sinonjs.org/releases/sinon-ie-1.7.3.js',
        'sinon-qunit':  '//sinonjs.org/releases/sinon-qunit-1.0.0.js',
        'tests':        '../tests/units'
    },

    shim: {
        'QUnit': {
            exports: 'QUnit',
            init:    function () {
                QUnit.config.autoload = false;
                QUnit.config.autostart = false;
            }
        },

        'storage': {
            deps:    [ 'backbone' ],
            exports: 'backbone'
        },

        'facebook': {
            exports: 'FB'
        },

        'fb_share': {
            deps:    [ 'facebook' ],
            exports: 'fb_share'
        },

        'tinysort':{
            deps: ['jquery'],
            exports: 'tinysort'
        },

        'AA.facebook':{
            deps: ['jquery', 'facebook', 'jquery.flot.min', 'jquery.flot.resize.min', 'jquery.flot.time'],
            exports: 'AA.facebook'
        },

        'AA.contest':{
            deps: ['jquery', 'AA.facebook', 'facebook', 'jquery.flot.min', 'jquery.flot.resize.min', 'jquery.flot.time'],
            exports: 'AA.contest'
        },

        'jquery.flot.min':{
            deps: ['jquery'],
            exports: 'jquery'
        },

        'jquery.flot.resize.min':{
            deps: ['jquery', 'jquery.flot.min'],
            exports: 'jquery'
        },

        'jquery.flot.time':{
            deps: ['jquery', 'jquery.flot.min'],
            exports: 'jquery'
        },

        'imperio.general':{
            deps: ['jquery', 'bootstrap'],
            exports: 'jquery'
        },

        'select2.min':{
            deps: ['jquery'],
            exports: 'jquery'
        },

        'sinon-qunit': {
            deps:    [ 'sinon', 'sinon-ie' ],
            exports: 'sinon'
        }
    }
};
