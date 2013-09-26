/**
 * Class to manage all necessary admin javascript functions of the multimedia contest
 * @author Sebastian Buckpesch < s.buckpesch@iconsultants.eu >
 * @requires jQuery, FB.init, http://www.flotcharts.org/
 */

var AAContest = new Object();

/* Display warning modals */
AAContest.resetPhotosAndVotesModal = function ( params, callback ) {
    $('.modal .reset').hide();
    $('.modal .reset-1').show();
    $('#modal-reset').modal('show');
}
AAContest.resetVotesModal = function ( params, callback ) {
    $('.modal .reset').hide();
    $('.modal .reset-2').show();
    $('#modal-reset').modal('show');
}
AAContest.resetVotingRoundModal = function ( params, callback ) {
    $('.modal .reset').hide();
    $('.modal .reset-3').show();
    $('#modal-reset').modal('show');
}
AAContest.resetUploadRoundModal = function ( params, callback ) {
    $('.modal .reset').hide();
    $('.modal .reset-4').show();
    $('#modal-reset').modal('show');
}


/**
 *
 * @param params Array of paramters {
 *                  'target': (String) Target jquery selector the graph should be rendered in
 *              }
 * @param callback
 */
AAContest.resetPhotosAndVotes = function ( params, callback ) {
    /* Check data param */
    if ( typeof( params ) != 'undefined' ) {
        if ( !params.hasOwnProperty('interval') ) {
            interval = 'day';
        }else {
            interval = params['interval'];
        }
    }
    $.ajax({
        type:'POST',
        async:true,
        url:'ajax/reset_votes_and_photos.php',
        data:({
            aa_inst_id:aa_inst_id
        }),
        success:function (data) {
            callback( data );
        }

    });
}

/**
 *
 * @param params Array of paramters {
 *                  'target': (String) Target jquery selector the graph should be rendered in
 *              }
 * @param callback
 */
AAContest.resetVotes = function ( params, callback ) {
    /* Check data param */
    if ( typeof( params ) != 'undefined' ) {
        if ( !params.hasOwnProperty('interval') ) {
            interval = 'day';
        }else {
            interval = params['interval'];
        }
    }
    $.ajax({
        type:'POST',
        async:true,
        url:'ajax/reset_votes.php',
        data:({
            aa_inst_id:aa_inst_id
        }),
        success:function (data) {
            callback( data );
        }

    });
}

/**
 *
 * @param params Array of paramters {
 *                  'interval': (String) Interval between the the data points
 *              }
 * @param callback
 */
AAContest.resetVotingRound = function ( params, callback ) {
    /* Check data param */
    if ( typeof( params ) != 'undefined' ) {
        if ( !params.hasOwnProperty('interval') ) {
            interval = 'day';
        }else {
            interval = params['interval'];
        }
    }
    $.ajax({
        type:'POST',
        async:true,
        url:'ajax/reset_round_vote.php',
        data:({
            aa_inst_id:aa_inst_id
        }),
        success:function (data) {
            callback( data );
        }

    });
}

/**
 *
 * @param params Array of paramters {
 *                  'target': (String) Target jquery selector the graph should be rendered in
 *              }
 * @param callback
 */
AAContest.resetUploadRound = function ( params, callback ) {
    /* Check data param */
    if ( typeof( params ) != 'undefined' ) {
        if ( !params.hasOwnProperty('interval') ) {
            interval = 'day';
        }else {
            interval = params['interval'];
        }
    }
    $.ajax({
        type:'POST',
        async:true,
        url:'ajax/reset_round_upload.php',
        data:({
            aa_inst_id:aa_inst_id
        }),
        success:function (data) {
            callback( data );
        }

    });
}


/**
 * Renders a graph to show votes or uploads
 * @param params
 * @param callback
 * @return {Boolean}
 */
AAContest.renderGraph = function ( params, callback ) {

    /* Check data param */
    if ( typeof( params ) != 'undefined' ) {
        if ( !params.hasOwnProperty('target') ) {
            target = '#chart';
        }else {
            target = params['target'];
        }
        if ( !params.hasOwnProperty('data') ) { // Set data
            data = [[0,8], [1,5], [2,3], [3,8], [4,5], [5,6], [6,12], [7,4], [8,8], [9,3], [10,1]];
        }else {
            data = params['data'];
        }
        if ( !params.hasOwnProperty('data_label') ) { // Set data
            data_label = AAInsights.fb_page_name;
        }else {
            data_label = params['data_label'];
        }
        if ( !params.hasOwnProperty('data_point_caption') ) { // Set data
            data_point_caption = ' Votes';
        }else {
            data_point_caption = params['data_point_caption'];
        }
        if ( !params.hasOwnProperty('graph_color') ) { // Set data
            graph_color = '#000000';
        }else {
            graph_color = params['graph_color'];
        }
    } else {
        return false;
    }

    /* Normalize data */
    var d1 = data;
    var max = 0;
    $.each( data, function(index, value) {
        max = value[1];
    });

    var plot = $.plot( $( target ),
        [ { data: d1, label: data_label, color: graph_color} ], {
            series: {
                lines: { show: true, fill: true, fillColor: { colors: [ { opacity: 0.05 }, { opacity: 0.15 } ] } },
                points: { show: true }
            },
            legend: { position: 'nw'},
            grid: { hoverable: true, clickable: true, borderColor: '#ccc', borderWidth: 1, labelMargin: 10 },
            yaxis: { min: 0, max: max*1.05 },
            xaxis: { mode: "time", timeformat: "%d.%m." }
        });

    function showTooltip(x, y, contents) {
        jQuery('<div id="tooltip" class="tooltipflot">' + contents + '</div>').css( {
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5
        }).appendTo("body").fadeIn(200);
    }

    jQuery( target ).bind("plothover", function (event, pos, item) {
        jQuery("#x").text(pos.x.toFixed(2));
        jQuery("#y").text(pos.y.toFixed(2));

        if(item) {
            if (previousPoint != item.dataIndex) {
                previousPoint = item.dataIndex;

                jQuery("#tooltip").remove();
                var x = item.datapoint[0].toFixed(0),
                    y = item.datapoint[1].toFixed(0);

                // Format date
                //var date = new Date( x );
                //var strDate = date.getDate() + "." + date.getMonth()+1 + "." + date.getFullYear();
                //showTooltip(item.pageX, item.pageY, item.series.label + " - " + x + " = " + y);
                showTooltip(item.pageX, item.pageY, y + data_point_caption );
            }

        } else {
            jQuery("#tooltip").remove();
            previousPoint = null;
        }

    });
};