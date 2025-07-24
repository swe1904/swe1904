<?php 
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

class Attendance extends ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_attendance';
    }

    public function rules()
    {
        return [
            [['employee_id', 'attendance_date', 'status'], 'required'],
            [['employee_id'], 'integer'],
            [['attendance_date', 'in_time', 'out_time', 'created_on', 'updated_on','latitude','longitude','checkin_lat','checkin_lng','checkout_lat','checkout_lng'], 'safe'],
            [['status'], 'in', 'range' => ['Present', 'Absent', 'Late', 'Half-day']],
            [['remarks'], 'string', 'max' => 255],
        ];
    }

    public function getEmployee()
    {
        return $this->hasOne(Employee::class, ['user_id' => 'employee_id']);
    }
}
