<?php

namespace backend\modules\polling\models\base;

use Yii;

/**
 * This is the model class for table "tbl_polling_quiz_team".
 *
 * @property integer $id
 * @property integer $polling_quiz_id
 * @property string $name
 * @property PollingQuizQuestionAnswer[] $pollingQuizQuestionAnswers
 * @property PollingQuiz $pollingQuiz
 */
class PollingQuizTeam extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_polling_quiz_team';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['polling_quiz_id'], 'integer'],
            [['name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'polling_quiz_id' => 'Polling Quiz ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPollingQuiz()
    {
        return $this->hasOne(PollingQuiz::className(), ['id' => 'polling_quiz_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPollingQuizQuestionAnswers()
    {
        return $this->hasMany(PollingQuizQuestionAnswer::className(), ['polling_quiz_team_id' => 'id']);
    }
}
