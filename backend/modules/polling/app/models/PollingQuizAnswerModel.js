/**
 * src: http://stackoverflow.com/questions/11112608/angularjs-where-to-put-model-data-and-behaviour
 */
pqs.factory('PollingQuizAnswerModel', function($http)
{
    //constructor
    var quizAnswerModel = function(data){
        angular.extend(this,{
            id : null,
            quiz_invite_id : null,
            quiz_question_id : null,
            value : null
        });

        //populate passed data
        angular.extend(this, data);
    }

    return quizAnswerModel;
});
