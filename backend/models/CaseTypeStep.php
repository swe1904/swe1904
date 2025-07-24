<?php

namespace backend\models;

use himiklab\sortablegrid\SortableGridBehavior;
use Yii;

/**
 * This is the model class for table "tbl_case_type_step".
 *
 * @property integer $id
 * @property integer $case_type_id
 * @property string $name
 * @property integer $number_of_days
 * @property integer $order
 *
 * @property CaseType $caseType
 */
class CaseTypeStep extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'sort' => [
                'class' => SortableGridBehavior::className(),
                'sortableAttribute' => 'order'
            ],
        ];
    }
    public static function tableName()
    {
        return 'tbl_case_type_step';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['case_type_id', 'number_of_days', 'order' ], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['number_of_days'], 'required'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'case_type_id' => Yii::t('backend', 'Case Type ID'),
            'name' => Yii::t('backend', 'Name'),
            'number_of_days' => Yii::t('backend', 'Number Of Days'),
            'order' => Yii::t('backend', 'Order'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaseType()
    {
        return $this->hasOne(CaseType::className(), ['id' => 'case_type_id']);
    }
}
