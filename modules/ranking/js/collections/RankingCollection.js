/**
 * Created with JetBrains WebStorm.
 * User: bernardinhio
 * Date: 8/26/13
 * Time: 9:55 AM
 * To change this template use File | Settings | File Templates.
 */
define(
    ['backbone',
     'modulesSrc/ranking/js/models'
    ],
    function(Backbone, RankingModel){

        var RankingCollection = Backbone.Collection.extend({
            model: RankingModel
        });
        return RankingCollection;

});
