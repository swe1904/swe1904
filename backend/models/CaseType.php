<?php

namespace backend\models;

use Yii;
//use backend\models\CaseType;    //Nemanja
use himiklab\sortablegrid\SortableGridBehavior;

/**
 * This is the model class for table "tbl_case_type".
 *
 * @property integer $id
 * @property string $name
 * @property string $order
 *
 * @property CaseTypeStep[] $caseTypeSteps
 */
class CaseType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_case_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['name'], 'required'],
            // [['name'], 'string', 'max' => 255],
            [['order'], 'integer'],
        ];
    }

    public function behaviors()
    {
        return [
            'sort' => [
                'class' => SortableGridBehavior::className(),
                'sortableAttribute' => 'order'
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
            'name' => Yii::t('backend', 'Cases'),
            'categories' => Yii::t('backend', 'Categories'),
        ];
    }

    public function getApplicantFields($caseTypeId) 
    {
        $connection = Yii::$app->getDb();
        $model = $connection->createCommand("
            select applicant_field_key from tbl_case_type_applicant_field where case_type_id = :id
        ", [':id' => $caseTypeId]);
        $newFields = [];

        foreach($model->queryAll() as $element) {
            array_push($newFields, $element['applicant_field_key']);
        }

        return $newFields;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaseTypeSteps()
    {
        return $this->hasMany(CaseTypeStep::className(), ['case_type_id' => 'id']);
    }

    public function totalStepDays(){
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
            select sum(number_of_days) from tbl_case_type_step where case_type_id = :caseTypeId
            ", [':caseTypeId'=> $this->id]);

        return $command->queryScalar();
    }
    
    /** 
    * get type name as string
    * @author Nemanja
    * @since 2021-01-11
    * @param tbl_case_type id
    * @return type name
    */
    public static function getTypeName($id)
    {
        if (@$id) {
            $casetype = CaseType::findOne(['id' => $id]);
            $case_type_name = $casetype->name;
        }else{
            $case_type_name = "";
        }

        return $case_type_name;
    }
}
