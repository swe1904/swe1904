/**
 * src: http://stackoverflow.com/questions/11112608/angularjs-where-to-put-model-data-and-behaviour
 */
pqs.factory('PollingQuizModel', function($http,PollingQuizQuestionModel,GlobalConstants)
{
    //constructor
    var pollingQuiz = function(data){
        angular.extend(this,{
            id : null,
            user_id : null,
            name : null,
            description : null,
            created_at : null,
            pollingQuizQuestions : []
        });

        //populate passed data
        angular.extend(this, data);
    }

    pollingQuiz.recursiveHide = function(questions){
        angular.forEach(questions, function(quizQuestion, key) {
            quizQuestion.isVisible = PollingQuizQuestionModel.questionVisible(questions, quizQuestion);
        });
    };

    pollingQuiz.updateVisible = function(questions,updatedQuizQuestion,answer){
        angular.forEach(questions, function(quizQuestion, key) {
            if(quizQuestion.visible == GlobalConstants.QUIZ_QUESTION_VISIBLE_CONDITION){

                if(quizQuestion.visible_quiz_question_id == updatedQuizQuestion.id){
                    switch (parseInt(updatedQuizQuestion.type)){
                        case GlobalConstants.QUIZ_QUESTION_TYPE_BOOLEAN:
                        case GlobalConstants.QUIZ_QUESTION_TYPE_RADIO:
                        case GlobalConstants.QUIZ_QUESTION_TYPE_TEXT:
                            if(answer == quizQuestion.visible_value)
                                quizQuestion.isVisible = true;
                            else
                                quizQuestion.isVisible = false;
                            break;

                        case GlobalConstants.QUIZ_QUESTION_TYPE_NUMBER:
                        case GlobalConstants.QUIZ_QUESTION_TYPE_RATING:
                            var visibleValue = parseInt(quizQuestion.visible_value);//in this case always number comparison
                            switch(parseInt(quizQuestion.visible_compare)){
                                case GlobalConstants.QUIZ_QUESTION_ACTION_COMPARE_EQUAL:
                                    if(answer == visibleValue)
                                        quizQuestion.isVisible = true;
                                    else
                                        quizQuestion.isVisible = false;
                                    break;

                                case GlobalConstants.QUIZ_QUESTION_ACTION_COMPARE_GREATER:
                                    if(answer > visibleValue)
                                        quizQuestion.isVisible = true;
                                    else
                                        quizQuestion.isVisible = false;
                                    break;

                                case GlobalConstants.QUIZ_QUESTION_ACTION_COMPARE_LESS:
                                    if(answer < visibleValue)
                                        quizQuestion.isVisible = true;
                                    else
                                        quizQuestion.isVisible = false;
                                    break;

                                case GlobalConstants.QUIZ_QUESTION_ACTION_COMPARE_GREATER_EQUAL:
                                    if(answer >= visibleValue)
                                        quizQuestion.isVisible = true;
                                    else
                                        quizQuestion.isVisible = false;
                                    break;

                                case GlobalConstants.QUIZ_QUESTION_ACTION_COMPARE_LESS_EQUAL:
                                    if(answer <= visibleValue)
                                        quizQuestion.isVisible = true;
                                    else
                                        quizQuestion.isVisible = false;
                                    break;
                            }
                            break;
                    }
                }
            }
        });
        pollingQuiz.recursiveHide(questions);
    }

    return pollingQuiz;
});
