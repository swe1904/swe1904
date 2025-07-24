<?php

namespace backend\models;


use Yii;
use yii\db\ActiveRecord;

class AttendanceLog extends ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_attendance_logs';
    }

    public function rules()
    {
        return [
            [['employee_id', 'date'], 'required'],
            [['clock_in_time', 'clock_out_time'], 'safe'],
            [['worked_minutes'], 'integer'],
            [['location_status'], 'in', 'range' => ['inside_geofence', 'outside']],
            [['ip_address', 'device_type'], 'string', 'max' => 50],
            [['manual_override'], 'boolean'],
            [['notes'], 'string'],
        ];
    }
}
