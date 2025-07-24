/**
 * src: http://stackoverflow.com/questions/11112608/angularjs-where-to-put-model-data-and-behaviour
 */
pqs.factory('PollingQuizInviteModel', function($http)
{
    //constructor
    var quizInviteModel = function(data){
        angular.extend(this,{
            id : null,
            uuid : null,
            //quiz_questionnaire_id : null,
            polling_quiz_id : null,
            quiz_status : null,
            clicked_is : null,
            created_at : null,
            expiryDate : null,
            expired : false
        });

        //populate passed data
        angular.extend(this, data);
    }

    return quizInviteModel;
});
