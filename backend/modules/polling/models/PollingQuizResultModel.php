<?php
/**
 * Created by PhpStorm.
 * User: OWNER
 * Date: 12-10-2016
 * Time: 12:14 PM
 */
namespace backend\modules\polling\models;


class PollingQuizResultModel{
    public $pollingQuizQuestion=null;
    public $shortAnswerArray=array();
    public $questionType=null;
    public $correctAnswerMR=array();
    public $answerByUsersMR=array();
    public $correctAnswer=null;
    public $answerByUsers=array();
    public $teamAnswerArray=array();
    public $teamAnswerArrayMR=array();

    public function __construct($pollingQuizQuestionModel){
        $this->pollingQuizQuestion=$pollingQuizQuestionModel;
    }
    public function setQuizData(){
        if(!empty($this->pollingQuizQuestion)){

            $pollingQuizQuestion=$this->pollingQuizQuestion;
            $this->questionType=$pollingQuizQuestion->pollingQuizQuestionType->id;

            switch((int)$pollingQuizQuestion->pollingQuizQuestionType->id){
                case PollingQuizQuestion::SHOW_NUMBER:
                    if(!empty($pollingQuizQuestion->pollingQuizQuestionAnswers)){
                        foreach($pollingQuizQuestion->pollingQuizQuestionAnswers as $pollingQuizQuestionAnswer){
                            array_push($this->shortAnswerArray,$pollingQuizQuestionAnswer->answer);
                            array_push($this->teamAnswerArray,$pollingQuizQuestionAnswer);
                        }
                    }
                    break;

                case PollingQuizQuestion::MULTIPLE_RESPONSE:
                    if(!empty($pollingQuizQuestion->pollingQuizQuestionCorrectAnswer)){
                        $this->correctAnswerMR=explode(',',$pollingQuizQuestion->pollingQuizQuestionCorrectAnswer->answer);
                    }
                    if(!empty($pollingQuizQuestion->pollingQuizQuestionAnswers)){
                        foreach($pollingQuizQuestion->pollingQuizQuestionAnswers as $pollingQuizQuestionAnswer){
                            $responseAnswerByUser=explode(',',$pollingQuizQuestionAnswer->answer);
                            array_push($this->answerByUsersMR,$responseAnswerByUser);
                            $this->teamAnswerArrayMR[]=['id'=>$pollingQuizQuestionAnswer->id,
                                                         'participant_id'=>$pollingQuizQuestionAnswer->participant_id,
                                                             'polling_quiz_question_id'=>$pollingQuizQuestionAnswer->polling_quiz_question_id,
                            'polling_quiz_team_id'=>$pollingQuizQuestionAnswer->polling_quiz_team_id,
                            'answer'=>$responseAnswerByUser];
                            //array_push($this->teamAnswerArrayMR,$pollingQuizQuestionAnswer);
                        }
                    }
                    break;
                default:
                    if(!empty($pollingQuizQuestion->pollingQuizQuestionCorrectAnswer)){
                            $this->correctAnswer=$pollingQuizQuestion->pollingQuizQuestionCorrectAnswer->answer;
                    }
                    if(!empty($pollingQuizQuestion->pollingQuizQuestionAnswers)){
                        foreach($pollingQuizQuestion->pollingQuizQuestionAnswers as $pollingQuizQuestionAnswer){
                            array_push($this->answerByUsers,$pollingQuizQuestionAnswer->answer);
                            array_push($this->teamAnswerArray,$pollingQuizQuestionAnswer);

                        }
                    }
                    break;

            }
        }

    }
}