define(
    ['backbone'],
    function(Backbone){
        var RankingModel = Backbone.Model.extend({

            defaults: {
                id: '',
                name: '',
                description: '',
                likes: '',
                talking_about_count: ''
            },

            initialise: function(){
                console.log('this model has been initialized with default values');
            }

        });
        return RankingModel;
    });



