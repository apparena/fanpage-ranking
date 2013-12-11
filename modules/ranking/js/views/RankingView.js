define([
    'jquery',
    'underscore',
    'backbone',
    'tinysort',
    'text!modulesSrc/ranking/templates/bank.html',
    'modulesSrc/ranking/js/collections/RankingCollection',
    'jquery.flot.resize.min',
    'jquery.flot.time',
    'imperio.general',
    'select2.min'
], function ($, _, Backbone, TinySort, BankTemplate, RankingCollection) {

    //setting global variables
    //   _.aa.config.fanpage_ids.value;

    var fansPagesNumber;  //to be set later from the php response
    var typeOfTime = $('.typeOfTime.active').attr('id'); // in html is set checked as 'days'
    $('#e9').select2();
    var arrayIdsNames = new Array();

    console.log(typeOfTime);

    var RankingView = Backbone.View.extend({

        el: $("#content-wrapper"),

        initialize: function () {
            //console.log('bernard');
            //console.log(this);
            this.collection = new RankingCollection();
            this.render();
        },

        render: function () {
            this.handleJSON();
            this.setTime();
            this.expandingCollapsingElementsEachRow();  // event require insertAllElements() occurred before
            this.setOrderingForArrows();                // event require insertAllElements() occurred before
            this.refreshRowsOnClickArrows();            // event require insertAllElements() occurred before
            this.refreshRowsOnChangeMaxDisplayed();            // event require insertAllElements() occurred before
            this.updateTimeGraph();
            this.chooseItems();
            $('.page-spinner').addClass('collapsed');
            this.collapseAll();
        },

        allRowsMinInfo: function(){ // ajax call
            var response = this.ajax({  //this is s custom fct that is written in router.js line 106 that return an object having 2 attributes : data and type
                module: 'ranking',
                action: 'allRowsMinInfo'
            });
            var messageObject = response.data;            //line 124 125 in router.js
            var messageJson = messageObject['message'];   //line 54   54  in ajax.php   // message value is set inside the called php file
            return messageJson;
        },

        handleJSON: function(){
            var json = this.allRowsMinInfo();
            var obj = $.parseJSON(json);
            fansPagesNumber = Object.keys(obj).length;
            //fetching values from ajax Object and sending to collection
            for(var i=0; i<fansPagesNumber; i++){
                var item = 'item'+i;
                if (obj[item]) {
                var fieldsObj = obj[item];
                //console.log(fieldsObj);
                //add to collection
                this.collection.add({
                    id: fieldsObj['fb_page_id'],
                    name: fieldsObj['name'],
                    description: fieldsObj['description'],
                    likes: fieldsObj['likes'],
                    talking_about_count: fieldsObj['talking_about_count']
                });
            }
            }
            console.log(fansPagesNumber);
            // fetching data from collection.js  and using in view
            fansPagesNumber = this.collection.length;
            console.log(fansPagesNumber);
            for(var i= 0;i<fansPagesNumber; i++){
                //console.log(this.collection.at(i).get('id'));
                //console.log(this.collection.at(i).get('name'));
                //console.log(this.collection.at(i).get('description'));
                //console.log(this.collection.at(i).get('likes'));
                //console.log(this.collection.at(i).get('talking_about_count'));
                var data = this.collection.at(i);
                //console.log(data);
                if(typeof data === 'object') {
                    var compiledTemplate = _.template(BankTemplate, data);
                    this.$el.append(compiledTemplate);
                }
            }
        },


        expandingCollapsingElementsEachRow: function(view){
            view = this;  // the save 'this' before changing context
            $('.collapse-expand').on('click', function(){
                var rowId = $(this).attr('name');
                var jquery1 = $('#'.concat(rowId));
                var jquery2 = $('.'.concat('row-spinner-',rowId));
                var that = this; // save the this of the item the user clicked on
                jquery2.show();
                // slide down/up instead of just showing/hiding
                setTimeout( function () { // get around the bug that the loading indicator is not shown or shown too late
                    if($('.additional', jquery1).hasClass('collapsed')){  // find() // $('.additional', '.manipulate') is equivalent to $('.manipulate').find('.additional')
                        view.showGraph(rowId);
                        $('.additional', jquery1).slideDown( 300, function () {
                            $('.additional', jquery1).removeClass('collapsed');
                            $(that).removeClass('icon-collapse');
                            $(that).addClass('icon-collapse-top');
                        });
                    }
                    else {
                        $('.additional', jquery1).slideUp( 300, function () {
                            $('.additional', jquery1).addClass('collapsed');
                            $(that).removeClass('icon-collapse-top');
                            $(that).addClass('icon-collapse');
                        });
                    }
                    jquery2.hide();
                }, 100 );
            });
        },



        updateTimeGraph: function(){
            var view = this;  // the save 'this' before changing context
            // typeOfTime is global and in html (index.php) the active class is 30days
            $('.typeOfTime').on('click', function(){
                typeOfTime = this.id;
                console.log(typeOfTime);
                $('.typeOfTime').removeClass('active');
                $(this).addClass('active');
                $('.insert-graph1:not(.collapsed)').each(function(key, elmt){
                    var chart_likes_id = this.id;
                    var id = chart_likes_id.replace('chart_likes_','');
                    console.log(id);
                    view.showGraph(id);
                });
            });
        },

        showGraph: function(id){
            // do not execute the graph rendering and the ajax call if the item is collapsing
            if ( $( '#' + id + ' i[name="' + id + '"]' ).hasClass( 'icon-collapse-top' ) === true ) {
                return false;
            }
            var data = this.additionalInfo(id, typeOfTime);  //id must be string
            console.log(data);
            var obj = $.parseJSON(data);
            //console.log(obj);
            var len = Object.keys(obj).length;
            //console.log(len);
            var arrayLikes = new Array();
            var arrayTalksAbout = new Array();
            for(var i=0;i<len;i++){
                var row = obj[i];
                //console.log(row);
                var dateString = row.date;
                var date = new Date(dateString.concat('T00:00:00'));
                //console.log(date);
                var timestamp = date.getTime();
                //console.log(timestamp);
                var arrayInnerLikes = new Array();
                var arrayInnerTalksAbout = new Array();
                arrayInnerLikes.push(timestamp, row.likes);
                arrayInnerTalksAbout.push(timestamp, row.talking_about_count);
                arrayLikes.push(arrayInnerLikes);
                arrayTalksAbout.push(arrayInnerTalksAbout);
                //console.log(arrayLikes);
                //console.log(arrayTalksAbout);
            }
            this.renderGraph({
                'target':'#chart_likes_' + id,
                //'data_label':'Likes',
                'data_label': _.aa.locale.likes,
                'data_point_caption': '',
                //'graph_color':'#e11c8c',
                // take the color from app arena
                'graph_color': _.aa.config.graph_likes.value,
                'data':arrayLikes
            });
            this.renderGraph({
                'target':'#chart_talking_about_count_' + id,
                //'data_label':'Talking about',
                'data_label': _.aa.locale.talking_about,
                'data_point_caption': '',
                //'graph_color':'#941ce1',
                // take the color from app arena
                'graph_color': _.aa.config.graph_talking.value,
                'data':arrayTalksAbout
            });
        },


        additionalInfo: function(id,typeOfTime){ // ajax call
            var response = this.ajax({  //this is s custom fct that is written in router.js line 106 that return an object having 2 attributes : data and type
                module: 'ranking',
                action: 'additionalInfo',
                typeOfTime: typeOfTime,
                id: id
            });
            var messageObject = response.data;            //line 124 125 in router.js
            var messageJson = messageObject['message'];   //line 54   54  in ajax.php   // message value is set inside the called php file
            return messageJson;
        },


        setOrderingForArrows: function(){
            // ordering fans pages names
            $('.fans-pages-names-desc').on('click', function(){
                $('.icons').removeClass('order');
                $('.fans-pages-names-desc').addClass('order');
                $('.manipulate').tsort('.fans-pages-names p',{order:'desc'});
            });
            $('.fans-pages-names-asc').on('click', function(){
                $('.icons').removeClass('order');
                $('.fans-pages-names-asc').addClass('order');
                $('.manipulate').tsort('.fans-pages-names p',{order:'asc'});
            });
            // ordering likes
            $('.likes-desc').on('click', function(){
                $('.icons').removeClass('order');
                $('.likes-desc').addClass('order');
                $('.manipulate').tsort('.likes p',{order:'desc'});
            });
            $('.likes-asc').on('click', function(){
                $('.icons').removeClass('order');
                $('.likes-asc').addClass('order');
                $('.manipulate').tsort('.likes p',{order:'asc'});
            });
            // ordering talks about
            $('.talks-about-desc').on('click', function(){
                $('.icons').removeClass('order');
                $('.talks-about-desc').addClass('order');
                $('.manipulate').tsort('.talks-about p',{order:'desc'});
            });
            $('.talks-about-asc').on('click', function(){
                $('.icons').removeClass('order');
                $('.talks-about-asc').addClass('order');
                $('.manipulate').tsort('.talks-about p',{order:'asc'});
            });
        },




        //function to hand write elements we want to display only on the page
        chooseItems: function(){
            fansPagesNumber = this.collection.length;
            for(var i= 0;i<fansPagesNumber; i++){
                //create array to hold keys values as id, name
                var data = this.collection.at(i);
                var internal = new Array();
                internal['id'] = data.get('id');
                internal['name'] = data.get('name');
                arrayIdsNames.push(internal);
                //create dynamically <option> elements & append to <select>
                var str1 = "<option value='";
                var str2 = data.get('id');
                var str3 = "'>";
                var str4 = data.get('name');
                var str5 = '</option>';
                var html = str1.concat(str2, str3, str4, str5);
                $('#e9').append(html);
            }
            $('.select2-choices').on('DOMNodeInserted', function(){
                //hide selecting top and bottom elements
                $('.top-bottom').addClass('collapsed');
                //find the id of the chosen element by user
                var list = $('.select2-search-choice');
                var len = list.length;
                console.log(len);
                $('.manipulate').addClass('collapsed');
                //extract the id of the item chosen by the user in the select Box
                list.each(function(index, item){
                    var div = $(item).find('div');
                    var text = div.text();
                    //console.log(text);
                    for(var i in arrayIdsNames){
                        if(arrayIdsNames[i]['name'] == text){
                            var correspondingId = arrayIdsNames[i]['id'];
                            break;
                        }
                    }
                    //console.log(correspondingId);
                    var selector = '#'.concat(correspondingId);
                    //console.log(selector);
                    $(selector).removeClass('collapsed');
                });
                //remove rows when user remove his chosen item (by pressing X)
                $('.select2-search-choice-close').on('click', function(){
                    var div = $(this).prev();
                    var text = div.text();
                    console.log(text);
                    for(var i in arrayIdsNames){
                        if(arrayIdsNames[i]['name'] == text){
                            var correspondingId = arrayIdsNames[i]['id'];
                            break;
                        }
                    }
                    console.log(correspondingId);
                    var selector = '#'.concat(correspondingId);
                    //console.log(selector);
                    $(selector).addClass('collapsed');
                    //case where user removed all his chosen items (pressing X)
                    if($('.manipulate.collapsed').length == fansPagesNumber){
                        //when all rows are collapsed
                        $('.manipulate').removeClass('collapsed');
                        $('.top-bottom').removeClass('collapsed');
                    }
                });
            });

        },





        // function set the max number of rows to be displayed and returns it
        setMaxRows: function(){
            var maxResults = $('select option:selected').val();  // selector point the selected option
            if($('select option:selected').val() == 'all') {maxResults = fansPagesNumber; }
            return maxResults;
        },

        // fct to show a number of rows between all
        showMaxRows: function(allRows, maxRows){
            $('.manipulate').removeClass('collapsed');  // to reset
            for (var i=maxRows; i<allRows; i++){
                // eq(n) is jquery to select element at index n
                ($('.manipulate').eq(i)).addClass('collapsed');
                //console.log(($('.manipulate').eq(i)).attr('class')); //
            }
        },

        // fct that adds click event to the "ordering arrows" for showing limited number of rows in grid
        refreshRowsOnClickArrows: function(){
            var view = this;
            $('.fans-pages-names-desc, .fans-pages-names-asc, .likes-desc, .likes-asc, .talks-about-desc, .talks-about-asc').on('click', function(){
                if($('.select2-search-choice').length == 0){
                    view.showMaxRows(fansPagesNumber, view.setMaxRows());
                }
            });
        },

        // fct that adds click event to the "refresh button" for showing limited number of rows in grid
        refreshRowsOnChangeMaxDisplayed: function(){
            var view = this;
            $('#max-rows').change(function(){
                var lenChoice = $('.select2-search-choice').length;
                var lenTopBottom = view.setMaxRows();
                if(lenChoice == 0){
                    view.showMaxRows(fansPagesNumber, lenTopBottom);
                }
            });
        },


        setTime: function(){
            // settings vars for date and time of ranking
            var date = new Date();
            var d = date.getDate();
            var m =  date.getMonth(); m += 1;  // JavaScript months are 0-11
            var y = date.getFullYear();
            var h = date.getHours();
            var min = date.getMinutes();
            var dateStr = d + '/' + m + '/' + y;
            var timeStr = h + 'h' + min + 'min';
            $('.date').append(dateStr);
            $('.time').append(timeStr);
            $('.num-elements').append(fansPagesNumber);
        },

        collapseAll: function(){
            $('#collapse-all').on('click', function(){
                $('.additional').each(function(index,elmnt){
                    if(!$(elmnt).hasClass('collapsed') && $(elmnt).hasClass('insert-graph1')){
                        console.log(elmnt);
                        var id = elmnt.id;
                        console.log(id);
                        id = id.replace('chart_likes_','');
                        console.log(id);
                        $('[name=' + id + ']').trigger('click');
                    }
                })
            })
        },
        //
        renderGraph: function(params, callback){
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
            var arr = new Array();
            $.each( data, function(index, value) {
                arr.push(value[1]);
            });
            //console.log(arr);
            arr.sort(function(a, b) { return a - b });
            //console.log(arr);
            var min = arr[0];
            var max = arr[arr.length - 1];
            //console.log(min);
            //console.log(max);
            var plot = $.plot( $( target ),
                [ { data: d1, label: data_label, color: graph_color} ], {
                    series: {
                        lines: { show: true, fill: true, fillColor: { colors: [ { opacity: 0.05 }, { opacity: 0.15 } ] } },
                        points: { show: true }
                    },
                    legend: { position: 'nw'},
                    grid: { hoverable: true, clickable: true, borderColor: '#ccc', borderWidth: 1, labelMargin: 10 },
                    yaxis: { min: min*0.95, max: max*1.05 },
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
                        var date = new Date(parseInt(x));
                        //console.log(x);
                        //console.log(date);
                        var strDate = "" + date.getDate() + "." + (date.getMonth()+1) + ".";
                        showTooltip(item.pageX, item.pageY, "  " + item.series.label + ": " + y + " (" + strDate + ")" );
                        //showTooltip(item.pageX, item.pageY, y + data_point_caption );
                    }

                } else {
                    jQuery("#tooltip").remove();
                    previousPoint = null;
                }

            });
        }



    });
    return RankingView;
});