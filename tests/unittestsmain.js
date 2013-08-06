(function () {
    'use strict';

    require.config({
        baseUrl: '../js/'
    });

    require([
        'QUnit',
        'tests/myTests'
    ], function (QUnit, myTests) {
        _.aa = aa;
        if (_.aa.app_data !== false) {
            _.aa.app_data = $.parseJSON(_.aa.app_data);
        }
        // extend underscore with our aa object, so that it is accessible everywhere where the _ underscore object is known.
        _.extend(_, { aa: aa });

        // run tests
        myTests.run();

        // start QUnit
        QUnit.load();
        QUnit.start();
    });
}());