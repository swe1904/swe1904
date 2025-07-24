<?php
namespace backend\models\search;

use app\components\GlobalConstant;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\LeaveRequest;
use yii\data\Pagination;
use yii;
class LeaveRequestSearch extends LeaveRequest
{
    public function rules()
    {
        return [
            [['id', 'employee_id'], 'integer'],
            [['leave_type', 'status','no_of_days'], 'safe'],
            [['start_date', 'end_date','pay_type','approved_by','approved_on'], 'safe'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
   public function search($params, $perPage = 10, $leaveType = null)
{
    $query = LeaveRequest::find();

    // Filter leave_type based on passed parameter
    if ($leaveType === 'WFH') {
        $query->andWhere(['leave_type' => 'WFH']);
    } elseif ($leaveType === 'NOT_WFH') {
        $query->andWhere(['!=', 'leave_type', 'WFH']);
    }

    // Filter by currently logged-in user
    if (!Yii::$app->user->isGuest) {
        $userId = Yii::$app->user->id;
        $query->andWhere(['employee_id' => $userId]);
    }

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'pagination' => [
            'pageSize' => $perPage,
        ],
        'sort' => [
            'defaultOrder' => ['id' => SORT_DESC],  // optional, adjust if you want
        ],
    ]);

    // Load and validate filter form inputs
    $this->load($params);

    if (!$this->validate()) {
        // Return data provider without applying additional filters
        return $dataProvider;
    }

    // Apply other filtering conditions from search form
    $query->andFilterWhere(['like', 'leave_type', $this->leave_type])
          ->andFilterWhere(['like', 'status', $this->status]);

    return $dataProvider;
}

    
//     public function searchForApproval($params, $perPage = 10)
// {
//     // Start with the query joining with the employee relation.
//     $query = LeaveRequest::find()->joinWith(['employee e']);  // Make sure 'employee' is the correct relation

//     $user = Yii::$app->user->identity;
//     $userId = Yii::$app->user->id;

//     if (!$user) {
//         // If user is not logged in, return no results
//         $query->where('0=1');
//     } else {
//         // Check if the user is HR Manager
//         $isHR = method_exists($user, 'getRole') && $user->getRole() == GlobalConstant::ROLE_HR_MANAGER;

//         if ($isHR) {
//             // If the user is HR, they can see all pending leave requests
//             $query->andWhere(['tbl_leave_request.status' => 'pending']);
//         } else {
//             // If the user is a department manager, find their employee record
//             $managerEmployee = \backend\models\Employee::findOne(['department_manager' => $userId]);
           
//             if ($managerEmployee) {
//                 // Get employees under this manager
//                 $employeeIds = \backend\models\Employee::find()
//                 ->select('user_id')
//                 ->where(['department_manager' => $userId])
//                 ->column();
            
        
//                 if (!empty($employeeIds)) {
//                     // Filter leave requests by employee IDs with pending status
//                     $query->andWhere(['tbl_leave_request.employee_id' => $employeeIds])
//                           ->andWhere(['tbl_leave_request.status' => 'pending']);
//                 } else {
//                     // If there are no employees under this manager
//                     $query->where('0=1');
//                 }
//             } else {
//                 // If no matching employee record found for the manager
//                 $query->where('0=1');
//             }
//         }
//     }

//     // Set up pagination for the result set
//     $dataProvider = new ActiveDataProvider([
//         'query' => $query,
//         'pagination' => ['pageSize' => $perPage],
//     ]);

//     // Load filters and apply them
//     $this->load($params);

//     // Validate and apply additional filters
//     if (!$this->validate()) {
//         return $dataProvider;
//     }

//     // Apply filters for leave type and status if provided
//     $query->andFilterWhere(['like', 'tbl_leave_request.leave_type', $this->leave_type])
//           ->andFilterWhere(['like', 'tbl_leave_request.status', $this->status]);

//     return $dataProvider;
// }

// public function searchForApproval($params, $perPage = 10)
// {
//     $query = LeaveRequest::find()->joinWith(['employee e']); // e is alias for employee relation

//     $user = Yii::$app->user->identity;
//     $userId = Yii::$app->user->id;

//     if (!$user) {
//         $query->where('0=1'); // Not logged in
//     } else {
//         $isHR = method_exists($user, 'getRole') && $user->getRole() == GlobalConstant::ROLE_HR_MANAGER;
//         $isTM = method_exists($user, 'getRole') && $user->getRole() == GlobalConstant::ROLE_TEAM_MANAGER;

//         if ($isHR || $isTM) {
//             // Fetch organisation ID of the logged-in HR user
//             $organisationId = $user->organisation_id ?? null;

//             if ($organisationId) {
//                 // Join with employee and filter by organisation and pending status
//                 $query->andWhere([
//                     'tbl_leave_request.status' => 'pending',
//                     'e.organisation_id' => $organisationId,
//                 ]);
//             } else {
//                 // If HR has no organisation_id, don't show anything
//                 $query->where('0=1');
//             }
//         } else {
//             // Department Manager Logic
//             $managerEmployee = \backend\models\Employee::findOne(['user_id' => $userId]);

//             if ($managerEmployee) {
//                 $employeeIds = \backend\models\Employee::find()
//                     ->select('user_id')
//                     ->where(['department_manager' => $userId])
//                     ->column();

//                 if (!empty($employeeIds)) {
//                     $query->andWhere(['tbl_leave_request.employee_id' => $employeeIds])
//                           ->andWhere(['tbl_leave_request.status' => 'pending']);
//                 } else {
//                     $query->where('0=1');
//                 }
//             } else {
//                 $query->where('0=1');
//             }
//         }
//     }

//     $dataProvider = new ActiveDataProvider([
//         'query' => $query,
//         'pagination' => ['pageSize' => $perPage],
//     ]);

//     $this->load($params);

//     if (!$this->validate()) {
//         return $dataProvider;
//     }

//     // Add optional search filters
//     $query->andFilterWhere(['like', 'tbl_leave_request.leave_type', $this->leave_type])
//           ->andFilterWhere(['like', 'tbl_leave_request.status', $this->status]);

//     return $dataProvider;
// }

public function searchForApproval($params, $perPage = 10)
{
    $query = LeaveRequest::find()->joinWith(['employee e']);

    $user = Yii::$app->user->identity;
    $userId = Yii::$app->user->id;

    if (!$user) {
        $query->where('0=1'); // Not logged in
    } else {
        $role = method_exists($user, 'getRole') ? $user->getRole() : null;

        switch ($role) {
            case GlobalConstant::ROLE_HR_MANAGER:
                // ✅ HR Manager: View all pending requests in same organisation
                $orgId = $user->organisation_id ?? null;
                if ($orgId) {
                    $query->andWhere([
                        'tbl_leave_request.status' => 'pending',
                        'e.organisation_id' => $orgId,
                    ]);
                } else {
                    $query->where('0=1');
                }
                break;

            case GlobalConstant::ROLE_TEAM_MANAGER:
                // ✅ Team Manager: View pending requests from their team
                $team = \backend\models\Team::findOne(['team_manager' => $userId]);
                if ($team) {
                    $teamMemberUserIds = \backend\models\Employee::find()
                        ->select('user_id')
                        ->where(['team' => $team->id])
                        ->column();

                    $query->andWhere([
                        'tbl_leave_request.status' => 'pending',
                        'tbl_leave_request.employee_id' => $teamMemberUserIds,
                    ]);
                } else {
                    $query->where('0=1');
                }
                break;

            default:
                // ✅ Department Manager: Find users in same department (from Department table)
                $managerEmployee = \backend\models\Employee::findOne(['user_id' => $userId]);
                if ($managerEmployee && $managerEmployee->department_id) {
                    $department = \backend\models\Department::findOne($managerEmployee->department_id);
                    if ($department && $department->department_manager == $userId) {
                        $userIds = \backend\models\Employee::find()
                            ->select('user_id')
                            ->where(['department_id' => $department->id])
                            ->column();

                        $query->andWhere([
                            'tbl_leave_request.status' => 'pending',
                            'tbl_leave_request.employee_id' => $userIds,
                        ]);
                    } else {
                        $query->where('0=1');
                    }
                } else {
                    $query->where('0=1');
                }
        }
    }

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'pagination' => ['pageSize' => $perPage],
    ]);

    $this->load($params);

    if (!$this->validate()) {
        return $dataProvider;
    }

    // ✅ Optional Filters
    $query->andFilterWhere(['like', 'tbl_leave_request.leave_type', $this->leave_type])
          ->andFilterWhere(['like', 'tbl_leave_request.status', $this->status]);

    return $dataProvider;
}

 public function searchHistory($params, $perPage = 10)
{
    $query = LeaveRequest::find()->joinWith(['employee e']); // Ensure relation 'employee' is defined

    $user = Yii::$app->user->identity;

    if (!$user) {
        // Not logged in
        $query->where('0=1');
    } else {
        $role = method_exists($user, 'getRole') ? $user->getRole() : null;

        // If the user is an employee, filter by their employee ID
        if ($role == GlobalConstant::ROLE_EMPLOYEE) {
            $employee = $user->employee ?? null;
            if ($employee) {
                $query->andWhere(['tbl_leave_request.employee_id' => $employee->user_id]);
            } else {
                // No linked employee record, show nothing
                $query->where('0=1');
            }
        }
        // HR or Manager can see all history
    }

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'pagination' => ['pageSize' => $perPage],
        'sort' => ['defaultOrder' => ['start_date' => SORT_DESC]],
    ]);

    $this->load($params);

    if (!$this->validate()) {
        return $dataProvider;
    }

    $query->andFilterWhere(['like', 'tbl_leave_request.leave_type', $this->leave_type])
          ->andFilterWhere(['like', 'tbl_leave_request.status', $this->status]);

    return $dataProvider;
}


   
}
