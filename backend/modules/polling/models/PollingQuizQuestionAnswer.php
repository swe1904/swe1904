<?php

namespace backend\modules\polling\models;

use Yii;

/**
 * This is the model class for table "{{%polling_quiz_question_answer}}".
 *
 * @property integer $id
 * @property integer $participant_id
 * @property integer $polling_quiz_question_id
 *
 * @property string $answer
 *
 * @property Participant $participant
 * @property PollingQuizQuestion $pollingQuizQuestion
 */
class PollingQuizQuestionAnswer extends \backend\modules\polling\models\base\PollingQuizQuestionAnswer
{

}
