<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use backend\models\Employee;

/**
 * This is the model class for table "tbl_document_request".
 *
 * @property int $id
 * @property int $employee_id
 * @property string $document_type
 * @property string $language_of_document
 */
class DocumentRequest extends ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_document_request';
    }

    public function rules()
    {
        return [
            [['employee_id', 'document_type', 'language_of_document'], 'required'],
            [['employee_id'], 'integer'],
            [['document_type', 'language_of_document'], 'string', 'max' => 500],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'employee_id' => 'Employee ID',
            'document_type' => 'Document Type',
            'language_of_document' => 'Language of Document',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(UserForm::class, ['id' => 'employee_id']);
    }
}
