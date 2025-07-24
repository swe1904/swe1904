<?php

namespace backend\modules\polling\controllers;

use backend\modules\polling\models\PollingQuizQuestionAnswer;
use yii\web\Controller;
use Yii;
use backend\modules\polling\models\PollingQuiz;
use yii\db\Expression;

class ShowResultController extends Controller
{
    public function actionIndex($id, $question_id = null)
    {
        $this->layout = '/show-result';
        Yii::$app->session->set('participantId', 62);
//        return $this->render('play_quiz');

        $pollingQuizModel = PollingQuiz::find()->where('polling_id=:polling_id', [':polling_id' => $id])->asArray()->one();
        $lengthOfTime = $pollingQuizModel['lengthoftime'];
        /*
         * set url for template index file
         * */

        $joinQuestions = [
            'pollingQuizQuestions.pollingQuizQuestionAnswers'
        ];

        if ($lengthOfTime > 0) {
            $joinQuestions = [
                'pollingQuizQuestions.pollingQuizQuestionAnswers' => function ($query) use ($lengthOfTime){
                    $query->andWhere(['>=', 'tbl_polling_quiz_question_answer.created_at', new Expression("DATE_SUB( NOW(),INTERVAL $lengthOfTime DAY)")]);
                },
            ];
        }

        $pollingQuiz = PollingQuiz::find()
            ->joinWith([
                    'pollingQuizTeams',
                    'pollingQuizQuestions',
                    'pollingQuizQuestions.pollingQuizQuestionOptions',
                    'pollingQuizQuestions.pollingQuizQuestionType',
                    //'pollingQuizQuestions.pollingQuizQuestionAnswers',
                    'pollingQuizQuestions.pollingQuizQuestionCorrectAnswer',
                ])
            ->joinWith($joinQuestions)
            ->where('polling_id=:polling_id', [':polling_id' => $id]);



//        echo $pollingQuiz->createCommand()->getRawSql();
//        die();
        $pollingQuiz = $pollingQuiz->one();
        $stepKey = 1;

     /*   echo '<pre>';
        print_r($pollingQuiz);
        echo '<pre>';
        die('die here');*/


        //$result=PollingQuizQuestionAnswer::find()->where(['>=', 'pollingQuizQuestions.pollingQuizQuestionAnswers.created_at', new Expression('DATE_SUB( CURDATE(),INTERVAL 7 DAY)')])->all();

//        echo '<pre>';
//        print_r($result);
//        echo '<pre>';
//        die('die here');

        if (!empty($question_id)) {
            if (!empty($pollingQuiz->pollingQuizQuestions)) {
                foreach ($pollingQuiz->pollingQuizQuestions as $key => $question) {
                    if ($question->polling_quiz_question_direct_id == $question_id) {
                        $stepKey = $key + 1;
                        break;
                    }
                }
            }
        }
        $redirectPage = 'index';
        if (empty($pollingQuiz->pollingQuizQuestions)) {
            $redirectPage = 'error';
        }
        return $this->render($redirectPage, [
            'pollingQuiz' => $pollingQuiz,
            'stepKey' => $stepKey,
        ]);

    }

    public function actionClearAnswer($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $pollingQuiz = PollingQuiz::find()
            ->with(
                array(
                    'pollingQuizQuestions'
                )
            )
            ->where('polling_id=:polling_id', [':polling_id' => $id])
            ->one();

        if (count($pollingQuiz->pollingQuizQuestions) > 0) {
            $pollingQuizQuestionIdArray = [];
            foreach ($pollingQuiz->pollingQuizQuestions as $pollingQuizQuestion) {
                array_push($pollingQuizQuestionIdArray, $pollingQuizQuestion->id);
            }
            PollingQuizQuestionAnswer::deleteAll(['polling_quiz_question_id' => $pollingQuizQuestionIdArray]);
            $returnData = [
                'message' => Yii::t('backend', 'Cleared.'),
                'code' => 1,
            ];
            return $returnData;
        }
        $returnData = [
            'message' => Yii::t('backend', 'Data is empty.'),
            'code' => 0,
        ];
        return $returnData;
    }
}
