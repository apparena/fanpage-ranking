define(
    ['backbone'],
    function(Backbone){
        var RankingModel = Backbone.Model.extend({

            defaults: {
                numFanPages: '',
                rank: '',
                photo: '',
                name: '',
                likes: '',
                talks_about: '',
                description: '',
                graph1: '',
                graph2: ''
            },

            initialise: function(){
                console.log('this model has been initialized with default values');
            },

            setAllRowsMinInfo: function() {
                var jsonObject = JSON.parse(this.rowRequest('', 'min'))
                var count = 0;
                for(var row in jsonObject){
                    console.log(row);
                    count++;
                }

                this.set({
                    numFanPages: count,
                    rank: 'x',
                    photo: '',
                    name: row['name'],
                    likes: row['likes'],
                    talks_about: row['talking_about_count'],
                    description: row['description'],
                    graph1: '',
                    graph2: ''

                });
            },
            rowRequest: function(the_fb_page_id, max_min){ // to continue
                var json;
                var the_Action;
                if(max_min == 'min') {the_Action = 'allRowsMinInfo';}
                if(max_min == 'min') {the_Action = 'maxIndividualRow';}
                $.ajax({
                    url: 'ajax.php',
                    data: {
                        //aa_inst_id:_.aa.instance.aa_inst_id,
                        fb_page_id: the_fb_page_id,
                        action: the_Action,  //mandatory  // the action must be located in the  'libs' directory of  module
                        module: 'ranking'   //mandatory // this will takes us to the  'libs' directory of the  module
                    },
                    dataType: 'json', //it returns a JSON Object
                    type: 'POST',
                    async: false,  // because this is the first thing we need to do
                    success: function(dataReturned){
                        json = dataReturned;
                    },
                    error: function(httpObject){
                        console.log('error in http request' + httpObject);
                    }
                });
            }










        });
        return RankingModel;
    });



