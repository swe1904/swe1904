<?php

namespace backend\models;

use Yii;
// use backend\models\CaseTypeApplicantField;

/**
 * This is the model class for table "tbl_case_type_applicant_field".
 *
 * @property int|null $case_type_id
 * @property string|null $applicant_field_key
 * @property string|null $applicant_field_value
 */
class CaseTypeApplicantField extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_case_type_applicant_field';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['case_type_id'], 'integer'],
            // [['applicant_field_key', 'applicant_field_value'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'case_type_id' => 'Case Type ID',
            'applicant_field_key' => 'Multiselect Fields',
            'applicant_field_value' => 'Applicant Field Value',
        ];
    }
}