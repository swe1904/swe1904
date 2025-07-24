<?php

namespace backend\modules\polling\models;

use Yii;

/**
 * This is the model class for table "{{%polling_quiz_question_option}}".
 *
 * @property integer $id
 * @property integer $polling_quiz_question_id
 * @property string $value
 * @property integer $order
 * @property string $explanation
 *
 * @property PollingQuizQuestion $pollingQuizQuestion
 */
class PollingQuizQuestionOption extends \backend\modules\polling\models\base\PollingQuizQuestionOption
{

}
