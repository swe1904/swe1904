<?php

namespace backend\modules\polling\models\base;

use Yii;

/**
 * This is the model class for table "tbl_polling_quiz_question_type".
 *
 * @property integer $id
 * @property string $name
 *
 * @property PollingQuizQuestion[] $pollingQuizQuestions
 */
class PollingQuizQuestionType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_polling_quiz_question_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
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
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPollingQuizQuestions()
    {
        return $this->hasMany(PollingQuizQuestion::className(), ['type' => 'id']);
    }
}
