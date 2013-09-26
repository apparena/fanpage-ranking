/**
 * Class to request and render Facebook Insight Graphs
 * @author Sebastian Buckpesch < s.buckpesch@iconsultants.eu >
 * @requires jQuery, FB.init, http://www.flotcharts.org/
 */

var AAFb = new Object();

/**
 * Initialize the login. First check if the user is connected.If not, call the login event.
 * @param params
 * @param callback
 */
AAFb.initLogin = function ( params, callback ) {

    /* Check data param */
    if ( typeof( params ) != 'undefined' ) {
        /*if ( !params.hasOwnProperty('target') ) {
         target = '#chart';
         }else {
         target = params['target'];
         }*/
    } else {
        return false;
    }

    // check if the user is already connected to this app
    FB.getLoginStatus( function ( response ) {
        if ( response.status === 'connected' ) {
            if ( typeof callback == 'function' )
                callback( response.authResponse );

        } else if ( response.status === 'not_authorized' ) {

            FB.Event.subscribe( 'auth.login',
                function ( response ) {
                    if ( typeof callback == 'function' )
                        callback( response.authResponse );
                }
            );
        } else {
            // the user isn't logged in to Facebook.
            // hook login event
            FB.Event.subscribe( 'auth.login',
                function ( response ) {
                    if ( typeof callback == 'function' )
                        callback( response.authResponse );
                }
            );

        }
    });
    FB.XFBML.parse();
}