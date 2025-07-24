<?php

namespace backend\models;

use common\models\User;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "leave_request".
 *
 * @property int $id
 * @property int $employee_id
 * @property int $no_of_days
 * @property string $leave_type
 * @property string $pay_type
 * @property string $status
 * @property string $start_date
 * @property string $end_date
 */
class LeaveRequest extends ActiveRecord
{

    const STATUS_REQUESTED = 'Requested';
    const STATUS_APPROVED = 'Approved';
    const STATUS_REJECTED = 'Rejected';
    const STATUS_POSTPONED = 'Postponed';
    public static function tableName()
    {
        return 'tbl_leave_request';
    }

    public function rules()
    {
       
        return [
            [['employee_id',  'leave_type', 'start_date', 'end_date'], 'required'],
            [['employee_id'], 'integer'],
            [['start_date', 'end_date','pay_type','approved_by','approved_on','no_of_days','notes'], 'safe'],
            [['leave_type'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'employee_id' => 'Employee ID',
            'leave_type' => 'Leave Type',
            'status' => 'Status',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
        ];
    }

//     public function getEmployee()
// {
//     return $this->hasOne(Employee::class, ['id' => 'employee_id']);
// }

public function getUser()
{
    return $this->hasOne(User::class, ['id' => 'user_id']);
}

public function getDepartment()
{
    return $this->hasOne(Department::class, ['id' => 'department_id']);
}
public function getEmployee()
{
    return $this->hasOne(Employee::className(), ['user_id' => 'employee_id']);
}

// Relation to Leave Coverage (if there's a lookup table, e.g., LeaveCoverage)
public function getCoverage()
{
    return $this->hasOne(Employee::className(), ['user_id' => 'leave_coverage']);
}
public function getApprover()
{
    return $this->hasOne(Employee::className(), ['user_id' => 'approved_by']);
}

}
