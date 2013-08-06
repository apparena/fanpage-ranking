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

        _.aa = aa;
        if (_.aa.app_data !== false) {
            _.aa.app_data = $.parseJSON(_.aa.app_data);
        }
        // extend underscore with our aa object, so that it is accessible everywhere where the _ underscore object is known.
        _.extend(_, { aa: aa });

        // extend jquery to be able to pass form data as a json automatically
        // (calling serializeObject will pack the data from the name attributes as a js-object)
        $.fn.serializeObject = function () {

            var items = {},
                form = this[ 0 ],
                index,
                item;

            for (index = 0; index < form.length; index++) {
                item = form[ index ];

                if (typeof( item.type ) !== 'undefined' && item.type === 'checkbox') {
                    item.value = $(item).is(':checked');
                }

                if (typeof( item.name ) !== 'undefined' && item.name.length > 0) {
                    items[ item.name ] = item.value;
                } else {
                    if (typeof( item.id ) !== 'undefined' && item.id.length > 0) {
                        items[ item.id ] = item.value;
                    }
                }
            }
            return items;
        };

        if(body.hasClass('website') === false) {
            FB.init({
                appId:                aa.instance.fb_app_id, // App ID
                channelUrl:           aa.instance.fb_canvas_url + '/channel.php', // Channel File
                status:               true, // check login status
                cookie:               true, // enable cookies to allow the server to access the session
                xfbml:                true, // parse XFBML
                oauth:                true,
                frictionlessRequests: true
            });

            FB.Canvas.setAutoGrow();

            body.scroll(function () {
                FB.Canvas.getPageInfo(function (info) {
                    console.log('offsetTop: ' + info.offsetTop);
                    console.log('scrollTop: ' + info.scrollTop);
                });
            });
        }

        App.initialize();
    });
}());