(function () {
    'use strict';

    require.config({
        baseUrl: 'js/'
    });

    require([
        'underscore',
        'text',
        'app'
    ], function (_, text, App) {

        var body = $('body');

        // extend underscore with our aa object, so that it is accessible everywhere where the _ underscore object is known.
        _.extend(_, { aa: aa });
        App.initialize();
    });
}());