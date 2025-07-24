<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

class Timesheet extends ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_employee_timesheets';
    }

    public function rules()
    {
        return [
            [['employee_id', 'date', 'start_time', 'end_time', 'total_duration'], 'required'],
            [['employee_id'], 'integer'],
            [['date'], 'date', 'format' => 'php:Y-m-d'],
            [['start_time', 'end_time', 'total_duration'], 'safe'],
           // [['note'], 'string', 'max' => 255],
        ];
    }

    public function getEmployee()
    {
        return $this->hasOne(Employee::class, ['id' => 'employee_id']);
    }
}
