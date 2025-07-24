/**
 * src: http://stackoverflow.com/questions/11112608/angularjs-where-to-put-model-data-and-behaviour
 */
pqs.factory('PollingQuizQuestionOptionModel', function($http)
{
    //constructor
    var quizOption = function(data){
        angular.extend(this,{
            id : null,
            quiz_question_id : null,
            value : null
        });

        //populate passed data
        angular.extend(this, data);
    }

    return quizOption;
});
