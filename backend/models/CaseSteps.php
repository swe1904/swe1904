<?php

namespace backend\models;
use himiklab\sortablegrid\SortableGridBehavior;

use Yii;

/**
 * This is the model class for table "tbl_case_steps".
 *
 * @property integer $id
 * @property integer $case_id
 * @property integer $case_type_step_id
 * @property string $planned_completion_date
 * @property string $actual_completion_date
 * @property string $created_at
 * @property integer $status
 *
 * @property CaseTypeStep $caseTypeStep
 * @property Cases $case
 */
class CaseSteps extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_case_steps';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['case_id', 'case_type_step_id'], 'required'],
            [['case_id', 'case_type_step_id', 'status', 'order'], 'integer'],
            ['case_type_step_id', 'unique', 'targetAttribute' => ['case_id', 'case_type_step_id']],
            [['planned_completion_date', 'actual_completion_date','created_at', 'description'], 'safe'],
        ];
    }

    public function behaviors()
    {
        return [
            'sort' => [
                'class' => SortableGridBehavior::className(),
                'sortableAttribute' => 'order',
        
            ],
        ];
    } 
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'case_id' => Yii::t('backend', 'Case Number'),
            'case_type_step_id' => Yii::t('backend', 'Case Type Step ID'),
            'planned_completion_date' => Yii::t('backend', 'Planned Completion Date'),
            'actual_completion_date' => Yii::t('backend', 'Actual Completion Date'),
            'created_at' => Yii::t('backend', 'Created At'),
            'status' => Yii::t('backend', 'Status'),
            'order' => Yii::t('backend' , 'order'),
            'description' => Yii::t('backend', 'Notes'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaseTypeStep()
    {
        return $this->hasOne(CaseTypeStep::className(), ['id' => 'case_type_step_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCase()
    {
        return $this->hasOne(Cases::className(), ['id' => 'case_id']);
    }
}
