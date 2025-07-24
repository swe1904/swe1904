<?php
namespace backend\models\search;

use app\components\GlobalConstant;
use backend\models\Attendance;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class AttendanceSearch extends Attendance
{
    public function rules()
    {
        return [
            [['employee_id'], 'integer'],
            [['attendance_date', 'status'], 'safe'],
        ];
    }

    // public function search($params)
    // {
    //     $query = Attendance::find()->joinWith('employee');
    //     $dataProvider = new ActiveDataProvider(['query' => $query]);
    //     $this->load($params);
    //     if (!$this->validate()) {
    //         return $dataProvider;
    //     }
    //     $query->andFilterWhere(['employee_id' => $this->employee_id]);
    //     $query->andFilterWhere(['like', 'status', $this->status]);
    //     $query->andFilterWhere(['attendance_date' => $this->attendance_date]);
    //     return $dataProvider;
    // }

    
public function search($params)
{
    $query = Attendance::find()->joinWith('employee');

    $userId = Yii::$app->user->id;
    $user = \common\models\User::findOne($userId);

    // Get assigned roles
    $roles = Yii::$app->authManager->getRolesByUser($userId);
    $roleNames = array_keys($roles); // array of role names

    // Check if user is NOT HR Manager or Super Admin
    if (!in_array(GlobalConstant::ROLE_HR_MANAGER, $roleNames) &&
        !in_array(GlobalConstant::ROLE_SUPERADMIN, $roleNames)) {

        // Then restrict to only their own attendance
        $employee = \backend\models\Employee::findOne(['user_id' => $userId]);
        if ($employee) {
            $query->andWhere(['tbl_attendance.employee_id' => $employee->user_id]);
        } else {
            // No employee record found, prevent showing anything
            $query->andWhere('0=1');
        }
    }

    $dataProvider = new ActiveDataProvider(['query' => $query]);

    $this->load($params);
    if (!$this->validate()) {
        return $dataProvider;
    }

    $query->andFilterWhere(['tbl_attendance.employee_id' => $this->employee_id]);
    $query->andFilterWhere(['like', 'tbl_attendance.status', $this->status]);
    $query->andFilterWhere(['tbl_attendance.date' => $this->attendance_date]);

    return $dataProvider;
}

}
