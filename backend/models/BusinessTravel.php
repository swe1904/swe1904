<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

class BusinessTravel extends ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_business_travel';
    }

    public function rules()
    {
        return [
            [['employee_id', 'country', 'from_date', 'to_date', 'reason'], 'required'],
            [['employee_id'], 'integer'],
            [['from_date', 'to_date', 'created_at'], 'safe'],
            [['reason'], 'string'],
            [['country'], 'string', 'max' => 255],
            ['to_date', 'validateDates'],
        ];
    }
public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'employee_id' => 'Employee Name',
            'country' => 'Country of Travel',
            'reason' => 'Reason of Travel',
            'from_date' => 'Travel Start Date',
            'to_date' => 'Travel End Date',
        ];
    }
    public function getEmployee()
    {
        return $this->hasOne(Employee::class, ['user_id' => 'employee_id']);
    }
    public function getCountryModel()
{
    return $this->hasOne(Country::class, ['id' => 'country']);
}
public function validateDates($attribute, $params)
{
    if (strtotime($this->to_date) < strtotime($this->from_date)) {
        $this->addError($attribute, 'End date cannot be before the start date.');
    }
}
}
