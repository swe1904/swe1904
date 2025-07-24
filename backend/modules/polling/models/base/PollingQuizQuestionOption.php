<?php

namespace backend\modules\polling\models\base;

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
class PollingQuizQuestionOption extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%polling_quiz_question_option}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['polling_quiz_question_id', 'value'], 'required'],
            [['polling_quiz_question_id', 'order'], 'integer'],
            [['value', 'explanation'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'polling_quiz_question_id' => Yii::t('app', 'Polling Quiz Question ID'),
            'value' => Yii::t('app', 'Value'),
            'order' => Yii::t('app', 'Order'),
            'explanation' => Yii::t('app', 'Explanation'),
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
