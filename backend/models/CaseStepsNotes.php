<?php

namespace backend\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "{{%case_steps_notes}}".
 *
 * @property integer $id
 * @property integer $case_steps_id
 * @property integer $user_id
 * @property string $description
 * @property string $created_at
 *
 * @property CaseTypeStep $caseSteps
 * @property User $user
 */
class CaseStepsNotes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%case_steps_notes}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['case_steps_id', 'user_id', 'description'], 'required'],
            [['case_steps_id', 'user_id'], 'integer'],
            [['description'], 'string'],
            [['created_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'case_steps_id' => Yii::t('backend', 'Case Steps ID'),
            'user_id' => Yii::t('backend', 'User ID'),
            'description' => Yii::t('backend', 'Description'),
            'created_at' => Yii::t('backend', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaseSteps()
    {
        return $this->hasOne(CaseTypeStep::className(), ['id' => 'case_steps_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
