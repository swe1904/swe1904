/**
 * src: http://stackoverflow.com/questions/11112608/angularjs-where-to-put-model-data-and-behaviour
 */
pqs.factory('PollingQuizQuestionModel', function($http, GlobalConstants)
{
    //constructor
    var pollingQuizQuestion = function(data){
        angular.extend(this,{
            id : null,
            polling_quiz_id : null,
            question : null,
            type : null,
            visible : null,
            pollingQuizOptions : [],
            visible_compare : null,
            visible_quiz_question_id : null,
            visible_value : null,

            //Only frontend properties
            isVisible : true
        });

        //populate passed data
        angular.extend(this, data);
    }

    pollingQuizQuestion.isVisible = true;

    pollingQuizQuestion.getQuestionFromId = function (pollingQuizQuestions,id) {
        var returnQuizQuestion = new this();
        angular.forEach(pollingQuizQuestions, function(pollingQuizQuestion, key) {
             if(pollingQuizQuestion.id == id){
                 returnQuizQuestion = pollingQuizQuestion;
             }
        });
        return returnQuizQuestion;
    };

    /**
     * If current visible false then return
     * If current visible true then ecursively check if parent question visible too.
     * @param questions
     * @param question
     * @returns {*}
     */
    pollingQuizQuestion.questionVisible = function(questions, question){
        if(question.visible == GlobalConstants.QUIZ_QUESTION_VISIBLE_CONDITION && question.visible_quiz_question_id){//parent
            var parentQuestion = pollingQuizQuestion.getQuestionFromId(questions, question.visible_quiz_question_id);
            if(question.isVisible == false)
                return false;
            else if(parentQuestion.visible == GlobalConstants.QUIZ_QUESTION_VISIBLE_CONDITION && parentQuestion.visible_quiz_question_id)
                    return pollingQuizQuestion.questionVisible(questions, parentQuestion);
            else
                return question.isVisible;
        }
        return true;
    }

    return pollingQuizQuestion;
});
