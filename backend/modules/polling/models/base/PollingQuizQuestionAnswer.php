<?php

namespace backend\modules\polling\models\base;

use backend\models\Participant;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%polling_quiz_question_answer}}".
 *
 * @property integer $id
 * @property integer $participant_id
 * @property integer $polling_quiz_question_id
 * @property string $answer
 * @property string $created_at
 *
 * @property Participant $participant
 * @property PollingQuizQuestion $pollingQuizQuestion
 */
class PollingQuizQuestionAnswer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return '{{%polling_quiz_question_answer}}';
    }
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                //'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['participant_id', 'polling_quiz_question_id'], 'required'],
            [['participant_id', 'polling_quiz_question_id'], 'integer'],
            [['answer'], 'string', 'max' => 512]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'participant_id' => 'Participant ID',
            'polling_quiz_question_id' => 'Questionnaire Question ID',
            'answer' => 'Answer',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParticipant()
    {
        return $this->hasOne(Participant::className(), ['id' => 'participant_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPollingQuizQuestion()
    {
        return $this->hasOne(PollingQuizQuestion::className(), ['id' => 'polling_quiz_question_id']);
    }
}
