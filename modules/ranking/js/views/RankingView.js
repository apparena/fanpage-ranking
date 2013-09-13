/*global define: false */
define([
    'jquery',
    'underscore',
    'backbone',
    'tinysort',
    'text!modulesSrc/ranking/templates/bank.html',
    'modulesSrc/ranking/js/collections/RankingCollection'

], function ($, _, Backbone, TinySort, BankTemplate, RankingCollection) {

    //setting global variables
    //   _.aa.config.fanpage_ids.value;
    var fansPagesIdsAsStr = '163721403669672;106479362709892;212285335468828;160805495421;173099949403228;263416953165;332236356800801;241806709206264;168513559842620;329596519636;218266734578;153048968094202';
    var fansPagesIdsAsArray = fansPagesIdsAsStr.split(';');
    var fansPagesNumber = fansPagesIdsAsArray.length;

    var RankingView = Backbone.View.extend({

        el: $("#content-wrapper"),

        initialize: function () {
            this.render();
            this.collection = new RankingCollection();
        },

        render: function () {
            //this.model.rowRequest(5618, 'all', 'rowInfo', 'min');
            //this.rowRequest(5618, 163721403669672, 'rowInfo', 'max');
            console.log(this.allRowsMinInfo());
            this.setTime();
            this.setRows();
            this.expandingCollapsingElementsEachRow();  // event require insertAllElements() occurred before
            this.setOrderingForArrows();                // event require insertAllElements() occurred before
            this.refreshRowsOnClickArrows();            // event require insertAllElements() occurred before
            this.refreshRowsOnClickButton();            // event require insertAllElements() occurred before
        },

        allRowsMinInfo: function(){ // to continue
            var response = this.ajax({  //this is s custom fct that is written in router.js line 106 that return an object having 2 attributes : data and type
                module: 'ranking',
                action: 'allRowsMinInfo'
            });
            var messageObject = response.data;            //line 124 125 in router.js
            var messageJson = messageObject['message'];   //line 54   54  in ajax.php   // message value is set inside the called php file
            return messageJson;
        },



        handle: function(){

        },

        handelRequest: function(json){
            $.each(json, function(key, val) {
                console.log(key, val);
                this.collection.add({
                    numFanPages: '',
                    rank: '',
                    photo: '',
                    name: '',
                    likes: '',
                    talks_about: '',
                    description: '',
                    graph1: '',
                    graph2: ''
                })
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
            console.log(fansPagesNumber);
            $('.num-elements').append(fansPagesNumber);
        },

        setRows: function(){
            //sending data to the template
            for(var i=0;i<fansPagesNumber;i++){
                var data = this.returnsFacebookJSONObject(fansPagesIdsAsArray[i]); //it creates every time an object
                if(typeof data === 'object') {
                    var compiledTemplate = _.template(BankTemplate, data);
                    this.$el.append(compiledTemplate);
                }
            }
        },

        returnsFacebookJSONObject: function(pageId){
            var jsonObject;
            $.ajax({
                url: 'https://graph.facebook.com/' + pageId,
                dataType: 'json', //it returns a JSON Object
                type: 'GET',
                async: false,  // because this is the first thing we need to do
                success: function(dataReturned,requestStatus, httpObject){
                    console.log('request is: '+ requestStatus);
                    //console.log(JSON.stringify(dataReturned));  // lets display a string of the obj
                    jsonObject = dataReturned;
                },
                error: function(httpObject, errorType, errorDescription){
                    console.log('error in http request');
                }
            });
            return jsonObject;
        },

        expandingCollapsingElementsEachRow: function(){
            $('.list-group-item').on('click', function(){
                if($('.additional', $(this)).hasClass('collapsed')){  // find() // $('.additional', '.list-group-item') is equivalent to $('.list-group-item').find('.additional')
                    $('.additional', $(this)).removeClass('collapsed');
                }
                else {
                    $('.additional', $(this)).addClass('collapsed');
                }
            });
        },

        setOrderingForArrows: function(){
            // ordering ranks
            $('.ranks-desc').on('click', function(){
                $('.list-group-item').tsort('.ranks p',{order:'desc'});
            });
            $('.ranks-asc').on('click', function(){
                $('.list-group-item').tsort('.ranks p',{order:'asc'});
            });
            // ordering images
            $('.photos-desc').on('click', function(){
                $('.list-group-item').tsort('.photos p',{order:'desc'});
            });
            $('.photos-asc').on('click', function(){
                $('.list-group-item').tsort('.photos p',{order:'asc'});
            });
            // ordering fans pages names
            $('.fans-pages-names-desc').on('click', function(){
                $('.list-group-item').tsort('.fans-pages-names p',{order:'desc'});
            });
            $('.fans-pages-names-asc').on('click', function(){
                $('.list-group-item').tsort('.fans-pages-names p',{order:'asc'});
            });
            // ordering likes
            $('.likes-desc').on('click', function(){
                $('.list-group-item').tsort('.likes p',{order:'desc'});
            });
            $('.likes-asc').on('click', function(){
                $('.list-group-item').tsort('.likes p',{order:'asc'});
            });
            // ordering talks about
            $('.talks-about-desc').on('click', function(){
                $('.list-group-item').tsort('.talks-about p',{order:'desc'});
            });
            $('.talks-about-asc').on('click', function(){
                $('.list-group-item').tsort('.talks-about p',{order:'asc'});
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
            $('.list-group-item').removeClass('collapsed');  // to reset
            for (var i=maxRows; i<allRows; i++){
                // eq(n) is jquery to select element at index n
                ($('.list-group-item').eq(i)).addClass('collapsed');
                console.log(($('.list-group-item').eq(i)).attr('class')); //
            }
        },

        // fct that adds click event to the "ordering arrows" for showing limited number of rows in grid
        refreshRowsOnClickArrows: function(){
            var view = this;
            $('.ranks-desc, .ranks-asc, .photos-desc, .photos-asc, .fans-pages-names-desc, .fans-pages-names-asc, .likes-desc, .likes-asc, .talks-about-desc, .talks-about-asc').on('click', function(){
                view.showMaxRows(fansPagesNumber, view.setMaxRows());
            });
        },

        // fct that adds click event to the "refresh button" for showing limited number of rows in grid
        refreshRowsOnClickButton: function(){
            var view = this;
            $('.refresh-button').on('click', function(){
                view.showMaxRows(fansPagesNumber, view.setMaxRows());
            });
        }



    });
    return RankingView;
});