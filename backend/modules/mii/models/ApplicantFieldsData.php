<?php

namespace backend\modules\mii\models;

use Yii;

/**
 * This is the model class for table "tbl_applicant_fields_data".
 *
 * @property int $id
 * @property string|null $fields_json
 */
class ApplicantFieldsData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_applicant_fields_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['fields_json'], 'safe'],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fields_json' => 'Fields Json',
        ];
    }
}
