<?php

namespace backend\modules\polling\models\base;

use Yii;

/**
 * This is the model class for table "tbl_polling_quiz_question_correct_answer".
 *
 * @property integer $id
 * @property integer $polling_quiz_question_id
 * @property string $answer
 *
 * @property PollingQuizQuestion $pollingQuizQuestion
 */
class PollingQuizQuestionCorrectAnswer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_polling_quiz_question_correct_answer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['polling_quiz_question_id'], 'integer'],
            [['answer'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'polling_quiz_question_id' => 'Polling Quiz Question ID',
            'answer' => 'Answer',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPollingQuizQuestion()
    {
        return $this->hasOne(PollingQuizQuestion::className(), ['id' => 'polling_quiz_question_id']);
    }
}
