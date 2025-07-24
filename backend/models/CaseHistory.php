<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%case_history}}".
 *
 * @property integer $id
 * @property integer $case_id
 * @property integer $is_complete
 * @property string $msg
 *@property string $status
 * @property Cases $case
 */
class CaseHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%case_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['case_id','is_complete','case_step_status'], 'integer'],
            [['created_at', 'msg','case_status'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'case_id' => Yii::t('backend', 'Case ID'),
            'created_at' => Yii::t('backend', 'Created at'),
            'case_step' => Yii::t('backend', 'Case step'),
            'case_status'=>Yii::t('backend', 'Case status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCase()
    {
        return $this->hasOne(Cases::className(), ['id' => 'case_id']);
    }
}
