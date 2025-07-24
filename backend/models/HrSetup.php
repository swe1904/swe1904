<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "hr_setup".
 */
class HrSetup extends ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_hr_setup';
    }

    public function rules()
    {
        return [
            [[ 'receipt_alphabetic_part', 'receipt_number_part'], 'required'],
            [['name', 'address'], 'string', 'max' => 255],
            [['landline'], 'string', 'max' => 20],
            [['receipt_alphabetic_part'], 'string', 'max' => 5],
            [['receipt_number_part'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'HR Name',
            'address' => 'Address',
            'landline' => 'Landline Number',
            'receipt_alphabetic_part' => 'Employee Alphabetic Part',
            'receipt_number_part' => 'Employee Number Part',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Generate Receipt Alphabetic Part
     */
    public function generateReceiptAlphabeticPart()
    {
        return 'HR'; // Customize logic if needed
    }

    /**
     * Generate Receipt Number Part
     */
    public function generateReceiptNumberPart()
    {
        $lastRecord = self::find()->orderBy(['id' => SORT_DESC])->one();
        return $lastRecord ? $lastRecord->receipt_number_part + 1 : 1000;
    }

    /**
     * Before saving, auto-generate receipt parts if empty
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->receipt_alphabetic_part = $this->generateReceiptAlphabeticPart();
            $this->receipt_number_part = $this->generateReceiptNumberPart();
        }
        return parent::beforeSave($insert);
    }
}
