/**
 * src: http://stackoverflow.com/questions/11112608/angularjs-where-to-put-model-data-and-behaviour
 */
pqs.factory('PollingQuizSettingModel', function($http)
{
    //constructor
    var quizSettingModel = function(data){
        angular.extend(this,{
            id : null,
            klanten_id : null,
            email_notification : null,
            thank_text : null
        });

        //populate passed data
        angular.extend(this, data);
    }

    return quizSettingModel;
});
