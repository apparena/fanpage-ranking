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
        'facebook':     '../modules/fangate/js/libs/fb_sdk',
        'fb_share':     'utils/fb_share',

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

        'sinon-qunit': {
            deps:    [ 'sinon', 'sinon-ie' ],
            exports: 'sinon'
        }
    }
};
