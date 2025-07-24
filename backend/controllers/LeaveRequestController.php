<?php

namespace backend\controllers;

use app\components\GlobalConstant;
use backend\models\Department;
use backend\models\Employee;
use Yii;
use backend\models\LeaveRequest;
use backend\models\Notification;
use backend\models\search\LeaveRequestSearch;
use common\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use Swift_Plugins_Loggers_ArrayLogger;
use Swift_Plugins_LoggerPlugin;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper; 

class LeaveRequestController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    // public function actionIndex()
    // {
    //     $searchModel = new LeaveRequestSearch();
        
    //     // Get the "per-page" parameter from the GET request, default to 10
    //     $perPage = Yii::$app->request->get('per-page', 10);
    
    //     $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $perPage, false);
    
    //     // Get pagination object from data provider
    //     $pagination = $dataProvider->pagination;
    
    //     return $this->render('index', [
    //         'searchModel' => $searchModel,
    //         'dataProvider' => $dataProvider,
    //         'pagination' => $pagination,
    //     ]);
    // }
    
    public function actionIndex()
{
    $searchModel = new LeaveRequestSearch();
    $perPage = Yii::$app->request->get('per-page', 10);
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $perPage,'NOT_WFH');

    return $this->render('index', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]);
}

// public function actionCreate()
// {
//     $model = new LeaveRequest();
//     $departmentManagerId = Yii::$app->request->post('department_manager');
//     $teamManagerId = Yii::$app->request->post('team_manager');

//     if ($model->load(Yii::$app->request->post())) {

//         // ✅ Calculate working days between start_date and end_date
//         $start = Carbon::parse($model->start_date);
//         $end = Carbon::parse($model->end_date);
//         $period = CarbonPeriod::create($start, $end);

//         $workingDays = 0;
//         foreach ($period as $date) {
//             if (!$date->isWeekend()) { // Saturday = 6, Sunday = 0
//                 $workingDays++;
//             }
//         }

//         // ✅ Store working days into the model (if you have a column for it)
//         $model->no_of_days = $workingDays; // Make sure you have a `leave_days` column in DB

//         if ($model->save()) {
//             // ✅ Notify department manager
//             $this->createNotification($model, $departmentManagerId);
//             $this->sendLeaveRequestEmail($model, $departmentManagerId);

//             // ✅ Notify all HR managers
//             $hrManagerIds = $this->getHrManagerIds();
//             foreach ($hrManagerIds as $hrUserId) {
//                 $this->createNotification($model, $hrUserId);
//                 $this->sendLeaveRequestEmail($model, $hrUserId);
//             }

//             Yii::$app->session->setFlash('success', 'Leave request submitted successfully.');
//             return $this->redirect(['index']);
//         }
//     }

//     return $this->render('create', ['model' => $model]);
// }

public function actionCreate()
{
    $model = new LeaveRequest();
    $departmentManagerId = Yii::$app->request->post('department_manager');
    $teamManagerId = Yii::$app->request->post('team_manager');

    if ($model->load(Yii::$app->request->post())) {

        // ✅ Calculate working days
        $start = Carbon::parse($model->start_date);
        $end = Carbon::parse($model->end_date);
        $period = CarbonPeriod::create($start, $end);

        $workingDays = 0;
        foreach ($period as $date) {
            if (!$date->isWeekend()) {
                $workingDays++;
            }
        }

        $model->no_of_days = $workingDays;

        if ($model->save()) {
            // ✅ Notify Department Manager
            if ($departmentManagerId) {
                $this->createNotification($model, $departmentManagerId);
            }

            // ✅ Notify Team Manager
            if ($teamManagerId) {
                $this->createNotification($model, $teamManagerId);
            }

            // ✅ Notify HRs
            $employee = \backend\models\Employee::findOne(['user_id' => $model->employee_id]);
            $hrManagerIds = $this->getHrManagerIds($employee->organisation_id ?? null);
            foreach ($hrManagerIds as $hrUserId) {
                $this->createNotification($model, $hrUserId);
            }

            // ✅ Send one email to all (HRs, dept manager, team manager)
            $this->sendLeaveRequestEmail($model, $departmentManagerId, $teamManagerId);

            Yii::$app->session->setFlash('success', 'Leave request submitted successfully.');
            return $this->redirect(['index']);
        }
    }

    return $this->render('create', ['model' => $model]);
}


private function getHrManagerIds()
{
    return Yii::$app->db->createCommand("
        SELECT e.user_id
        FROM employee e
        JOIN tbl_rbac_auth_assignment a ON a.user_id = e.user_id
        WHERE a.item_name = 'HR Manager'
    ")->queryColumn();
}


// private function getHrManagerIds($orgId)
// {
//     if (!$orgId) return [];

//     return Yii::$app->db->createCommand("
//         SELECT e.user_id
//         FROM employee e
//         JOIN tbl_rbac_auth_assignment a ON a.user_id = e.user_id
//         WHERE a.item_name = 'HR Manager' 
//     ")
//     ->bindValue(':org_id', $orgId)
//     ->queryColumn();
// }



private function sendLeaveRequestEmail($model, $departmentManagerId, $teamManagerId = null)
{
    $logger = new \Swift_Plugins_Loggers_ArrayLogger();
    Yii::$app->mailer->transport->registerPlugin(new \Swift_Plugins_LoggerPlugin($logger));

    // Get employee info
    $employee = \backend\models\Employee::findOne(['user_id' => $model->employee_id]);
    if (!$employee) {
        Yii::error("Employee not found for user_id: {$model->employee_id}", 'leave.debug');
        return;
    }

    $employeeName = $employee->first_name ?? 'Employee';
    $organisationId = $employee->organisation_id;

    // Prepare email content
    $subject = 'New Leave Request Submitted';
    $body = "Dear Sir/Madam,<br><br>"
          . "A new leave request has been submitted by <b>{$employeeName}</b>.<br><br>"
          . "<b>Leave Type:</b> {$model->leave_type}<br>"
          . "<b>Start Date:</b> {$model->start_date}<br>"
          . "<b>End Date:</b> {$model->end_date}<br><br>"
          . "Please login to the system to review and take appropriate action.<br><br>"
          . "Regards,<br>Leave Management System";

    // // Send email to Department Manager if in same organisation
    // $deptManager = \common\models\User::findOne($departmentManagerId);
    // if ($deptManager && $deptManager->organisation_id == $organisationId && $deptManager->email) {
    //     Yii::$app->mailer->compose()
    //         ->setTo($deptManager->email)
    //         ->setFrom(['notifications@myhr.northmansterling.com' => 'Notification - N&S HR Portal'])
    //         ->setSubject($subject)
    //         ->setHtmlBody($body)
    //         ->send();
    // }
// Send email to Department Manager (organisation check removed)
$deptManager = \common\models\User::findOne($departmentManagerId);
if ($deptManager && $deptManager->email) {
    Yii::$app->mailer->compose()
        ->setTo($deptManager->email)
        ->setFrom(['notifications@myhr.northmansterling.com' => 'Notification - N&S HR Portal'])
        ->setSubject($subject)
        ->setHtmlBody($body)
        ->send();
}

    // Send email to Team Manager if provided and in same organisation
    if ($teamManagerId) {
        $teamManager = \common\models\User::findOne($teamManagerId);
        // if ($teamManager && $teamManager->organisation_id == $organisationId && $teamManager->email) {
        if ($teamManager  && $teamManager->email) {
            Yii::$app->mailer->compose()
                ->setTo($teamManager->email)
                ->setFrom(['notifications@myhr.northmansterling.com' => 'Notification - N&S HR Portal'])
                ->setSubject($subject)
                ->setHtmlBody($body)
                ->send();
        }
    }

    // Send to all HR Managers in same organisation
    $hrManagerIds = $this->getHrManagerIds(); // Uses current logged-in user's organisation
    foreach ($hrManagerIds as $hrUserId) {
        $hrUser = \common\models\User::findOne($hrUserId);
        if ($hrUser && $hrUser->email) {
            Yii::$app->mailer->compose()
                ->setTo($hrUser->email)
                ->setFrom(['notifications@myhr.northmansterling.com' => 'Notification - N&S HR Portal'])
                ->setSubject($subject)
                ->setHtmlBody($body)
                ->send();
        }
    }

    Yii::info($logger->dump(), 'mail.debug');
}


// private function sendLeaveRequestEmail($model, $departmentManagerId)
// {
//     $logger = new Swift_Plugins_Loggers_ArrayLogger();
//     Yii::$app->mailer->transport->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

//     $manager = \common\models\User::findOne($departmentManagerId);
//     $managerEmail = $manager->email ?? null;

//     $hrEmail = 'sbottha@northmansterling.com';
//     $employee = \backend\models\Employee::findOne(['user_id' => $model->employee_id]);
//     $employeeName = $employee->first_name ?? 'Employee';

//     $subject = 'New Leave Request Submitted';
//     $body = "Dear Sir/Madam,<br><br>"
//           . "A new leave request has been submitted by <b>{$employeeName}</b>.<br><br>"
//           . "<b>Leave Type:</b> {$model->leave_type}<br>"
//           . "<b>Start Date:</b> {$model->start_date}<br>"
//           . "<b>End Date:</b> {$model->end_date}<br><br>"
//           . "Please login to the system to review and take appropriate action.<br><br>"
//           . "Regards,<br>Leave Management System";

//     Yii::$app->mailer->compose()
//         ->setTo($hrEmail)
//         ->setFrom(['notifications@myhr.northmansterling.com' => 'Notification - N&S HR Portal'])
//         ->setSubject($subject)
//         ->setHtmlBody($body)
//         ->send();

//     if ($managerEmail) {
//         Yii::$app->mailer->compose()
//             ->setTo($managerEmail)
//             ->setFrom(['notifications@myhr.northmansterling.com' => 'Notification - N&S HR Portal'])
//             ->setSubject($subject)
//             ->setHtmlBody($body)
//             ->send();
//     }

//     // Output the debug log
//     Yii::info($logger->dump(), 'mail.debug');
// }



private function createNotification($leaveRequest, $recipientUserId)
{
    $employee = \backend\models\Employee::findOne(['user_id' => $leaveRequest->employee_id]);

    $employeeName = $employee ? $employee->first_name . ' ' . $employee->last_name : 'Employee';
    $leaveType = $leaveRequest->leave_type ?? 'Leave';
    $startDate = Yii::$app->formatter->asDate($leaveRequest->start_date);
    $endDate = Yii::$app->formatter->asDate($leaveRequest->end_date);

    $message = "You have a new {$leaveType} request from {$employeeName}, scheduled from {$startDate} to {$endDate}, awaiting your approval.";

    $notification = new Notification();
    $notification->from_user_id = Yii::$app->user->id;
    $notification->to_user_id = $recipientUserId;
    $notification->title = "Leave Request from {$employeeName}";
    $notification->message = $message;
    $notification->table_name = "tbl_leave_requests";
    $notification->record_id = $leaveRequest->id;
    $notification->link = "/leave-request/approve?id=" . $leaveRequest->id;
    $notification->is_read = 0;
    $notification->created_at = date('Y-m-d H:i:s');
    $notification->save(false);
}



    /**
     * Displays a single leave request details.
     * @param integer $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    private function notifyEmployeeOnLeaveStatus($leaveRequest, $status)
    {
    // $status can be 'approved' or 'rejected'
    $notification = new Notification();
    $notification->from_user_id = Yii::$app->user->id; // The manager/admin who approved/rejected
    $notification->to_user_id = $leaveRequest->employee_id; // Assuming employee_id stores the user_id of the employee
    $notification->title = "Leave Request " . ucfirst($status);
    $notification->message = "Your leave request (ID: {$leaveRequest->id}) has been " . $status . ".";
    $notification->table_name = "tbl_leave_requests";
    $notification->record_id = $leaveRequest->id;
    $notification->link = "/leave-request/view?id=" . $leaveRequest->id; // Link to view leave details

    $notification->is_read = 0; // unread
    $notification->created_at = date('Y-m-d H:i:s');

    if (!$notification->save()) {
        Yii::error("Failed to save leave status notification: " . json_encode($notification->errors));
    }
    }

    public function actionViewNotification($id)
    {
    $notification = Notification::findOne($id);

    if ($notification && $notification->to_user_id == Yii::$app->user->id) {
        $notification->is_read = 1;
        $notification->save(false);

        // Redirect using the stored link
        return Yii::$app->getResponse()->redirect($notification->link);
    }

    throw new NotFoundHttpException('Notification not found.');
    }

    public function actionRedirect($id)
    {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Get current logged in user ID
    $currentUserId = Yii::$app->user->id;
    // echo "Logged in User ID: " . $currentUserId . "<br>";

    // Find notification
    $notification = Notification::findOne($id);

    if (!$notification) {
        throw new \yii\web\NotFoundHttpException('Notification not found.');
    }


    // Check if the notification belongs to logged-in user
    if ($notification->to_user_id == $currentUserId) {
        // Direct DB update without ActiveRecord save()
        $rowsUpdated = Yii::$app->db->createCommand()
            ->update('tbl_notifications', ['is_read' => 1], ['id' => $notification->id])
            ->execute();

        // echo "Rows updated: " . $rowsUpdated . "<br>";

        if ($rowsUpdated) {
            // Redirect to the notification link
            return $this->redirect([$notification->link]);
        } else {
            echo "Failed to update notification status.";
            die;
        }
    } else {
        echo "Notification not for this user.";
        die;
    }
    }

    public function actionUpdateNotificationRead()
    {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    $id = Yii::$app->request->post('id');
    $notification = Notification::findOne($id);

    if ($notification && $notification->to_user_id == Yii::$app->user->id) {
        $notification->is_read = 1;
        $notification->save(false);
        return ['status' => 'success'];
    }

    return ['status' => 'error'];
    }

    /**
     * Updates an existing leave request.
     * If update is successful, the user is redirected to the index page.
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    // public function actionUpdate($id)
    // {
    //     $model = $this->findModel($id);

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         Yii::$app->session->setFlash('success', 'Leave request updated successfully.');
    //         return $this->redirect(['index']);
    //     }
    //     return $this->render('update', [
    //         'model' => $model,
    //     ]);
    // }  
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $departmentManagerId = Yii::$app->request->post('department_manager');

        // Allow editing only if status is pending or postpone
        if (!in_array($model->status, ['pending', 'postpone'])) {
            Yii::$app->session->setFlash('error', 'You cannot update a leave request that is Approved or Rejected.');
            return $this->redirect(['index']);
        }

        $originalStatus = $model->status;

        if ($model->load(Yii::$app->request->post())) {
            // If request was postponed, reset status to pending after edit
            if ($originalStatus === 'postpone') {
                $model->status = 'pending';
            }

            if ($model->save()) {
                // Send email notification
                $this->sendLeaveRequestEmail($model, $departmentManagerId);

                Yii::$app->session->setFlash('success', 'Leave request updated successfully.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * Deletes an existing leave request.
     * If deletion is successful, the user is redirected to the index page.
     * @param integer $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Leave request deleted successfully.');
        // return $this->redirect(['index']);
         return $this->redirect(Yii::$app->request->referrer);
    }

   // Add this to your LeaveRequestController.php

   public function actionApprove()
   {
       $searchModel = new LeaveRequestSearch();
       $perPage = Yii::$app->request->get('per-page', 10);
       $dataProvider = $searchModel->searchForApproval(Yii::$app->request->queryParams, $perPage);
   
       return $this->render('approve', [
           'searchModel' => $searchModel,
           'dataProvider' => $dataProvider,
       ]);
   }
   
    public function actionApproveLeave()
    {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $request = Yii::$app->request->post();

    \Yii::error('Received POST Data: ' . json_encode($request), 'leave-debug');

    if (!isset($request['id']) || !isset($request['status'])) {
        return ['success' => false, 'message' => 'Missing required parameters.'];
    }

    $leaveId = (int)$request['id'];
    $leaveRequest = LeaveRequest::findOne($leaveId);
    if (!$leaveRequest) {
        return ['success' => false, 'message' => 'Leave request not found.'];
    }

    $employee = \backend\models\Employee::findOne(['user_id' => $leaveRequest->employee_id]);
    if (!$employee) {
        return ['success' => false, 'message' => 'Employee record not found.'];
    }

    $leaveDays = (int)$leaveRequest->no_of_days;
    $leaveRequest->pay_type = $request['pay_type'] ?? 'with pay';

    // Deduct balance for Paid Annual Leave
    if (
        $request['status'] === 'approve' &&
        $leaveRequest->leave_type === 'Paid Annual Leave'
    ) {
        if ($employee->annual_leave < $leaveDays) {
            return ['success' => false, 'message' => 'Insufficient annual leave balance.'];
        }
        $employee->annual_leave -= $leaveDays;
    }

    $leaveRequest->status = $request['status'];
    $leaveRequest->remarks = $request['remarks'] ?? null;
    $leaveRequest->leave_coverage = $request['leave_coverage'] ?? null;
    $leaveRequest->approved_by = $request['approved_by'] ?? null;
    $leaveRequest->approved_on = date('Y-m-d');

    if ($leaveRequest->save() && $employee->save()) {
        $leaveCoverage = $this->getLeaveCoverageName($leaveRequest->leave_coverage);

        $statusMap = [
            'approve' => 'Approved',
            'reject' => 'Rejected',
            'postpone' => 'Postponed',
        ];
        $statusText = $statusMap[$request['status']] ?? 'Pending';

        $subject = "Leave Request {$statusText}";
        $message = "
            <p>Dear {$employee->first_name},</p>
            <p>Your leave request for <strong>{$leaveDays}</strong> working day(s), from <strong>{$leaveRequest->start_date}</strong> to <strong>{$leaveRequest->end_date}</strong>, has been <strong>{$statusText}</strong>.</p>
            <p><strong>Leave Type:</strong> {$leaveRequest->leave_type}</p>
            <p><strong>Remarks:</strong> {$leaveRequest->remarks}</p>
            <p><strong>Leave Coverage:</strong> {$leaveCoverage}</p>
            <p><strong>Remaining Annual Leave Balance:</strong> {$employee->annual_leave} day(s)</p>
            <p>Thank you,<br>HR Department</p>
        ";

        // Fetch HR managers for this organisation
        $orgId = $employee->organisation_id;
        $hrUserIds = (new \yii\db\Query())
            ->select('tbl_rbac_auth_assignment.user_id')
            ->from('tbl_rbac_auth_assignment')
            ->innerJoin('tbl_user', 'tbl_rbac_auth_assignment.user_id = tbl_user.id')
            ->where([
                'tbl_rbac_auth_assignment.item_name' => 'HR Manager',
                'tbl_user.organisation_id' => $orgId
            ])
            ->column();

        $hrUsers = User::find()->where(['id' => $hrUserIds])->all();

        $fromEmail = 'hr@company.com';
        $fromName = 'HR Department';
        if (!empty($hrUsers)) {
            $fromEmail = $hrUsers[0]->email ?? $fromEmail;
            $fromName = $hrUsers[0]->username ?? $fromName;
        }

        // Send email to employee
        if (!empty($employee->email)) {
            Yii::$app->mailer->compose()
                ->setTo($employee->email)
                ->setFrom([$fromEmail => "HR - {$fromName}"])
                ->setSubject($subject)
                ->setHtmlBody($message)
                ->send();
        }

        // Send email to all HR managers
        foreach ($hrUsers as $hrUser) {
            if (!empty($hrUser->email)) {
                Yii::$app->mailer->compose()
                    ->setTo($hrUser->email)
                    ->setFrom([$fromEmail => "HR - {$fromName}"])
                    ->setSubject($subject . " - {$employee->first_name}")
                    ->setHtmlBody("
                        <p>Dear {$hrUser->username},</p>
                        <p>A leave request has been {$statusText} for <strong>{$employee->first_name}</strong>.</p>
                        <p><strong>Leave Days:</strong> {$leaveDays}</p>
                        <p><strong>From:</strong> {$leaveRequest->start_date}</p>
                        <p><strong>To:</strong> {$leaveRequest->end_date}</p>
                        <p><strong>Type:</strong> {$leaveRequest->leave_type}</p>
                        <p><strong>Remarks:</strong> {$leaveRequest->remarks}</p>
                        <p><strong>Leave Coverage:</strong> {$leaveCoverage}</p>
                        <p>Thanks,<br>Leave Management System</p>
                    ")
                    ->send();
            }
        }

        // Notify department manager
        if (!empty($request['department_manager'])) {
            $deptManagerEmployee = \backend\models\Employee::findOne($request['department_manager']);
            if ($deptManagerEmployee) {
                $deptManager = User::findOne($deptManagerEmployee->user_id);
                if ($deptManager && !empty($deptManager->email)) {
                    $managerMessage = "
                        <p>Dear {$deptManager->username},</p>
                        <p>The leave request of <strong>{$employee->first_name}</strong> ({$employee->position}) for <strong>{$leaveDays}</strong> working day(s), from <strong>{$leaveRequest->start_date}</strong> to <strong>{$leaveRequest->end_date}</strong>, has been <strong>{$statusText}</strong>.</p>
                        <p><strong>Leave Type:</strong> {$leaveRequest->leave_type}</p>
                        <p><strong>Remarks:</strong> {$leaveRequest->remarks}</p>
                        <p><strong>Leave Coverage:</strong> {$leaveCoverage}</p>
                        <p>Thank you,<br>HR Department</p>
                    ";

                    Yii::$app->mailer->compose()
                        ->setTo($deptManager->email)
                        ->setFrom([$fromEmail => "HR - {$fromName}"])
                        ->setSubject("Leave Request {$statusText}: {$employee->first_name}")
                        ->setHtmlBody($managerMessage)
                        ->send();
                }
            }
        }

        // Create system notification for the employee
        $notification = new Notification();
        $notification->from_user_id = Yii::$app->user->id;
        $notification->to_user_id = $leaveRequest->employee_id;
        $notification->title = "Leave Request {$statusText}";
        $notification->message = "Your leave request ({$leaveDays} day(s)) from {$leaveRequest->start_date} to {$leaveRequest->end_date} has been {$statusText}.";
        $notification->table_name = 'tbl_leave_requests';
        $notification->record_id = $leaveRequest->id;
        $notification->link = '/leave-request/view?id=' . $leaveRequest->id;
        $notification->is_read = 0;
        $notification->created_at = date('Y-m-d H:i:s');

        if (!$notification->save()) {
            Yii::error("Failed to save notification: " . json_encode($notification->errors), 'leave-debug');
        }

        return ['success' => true, 'message' => 'Leave request processed and notifications sent.'];
    } else {
        \Yii::error('Save Failed: ' . json_encode($leaveRequest->errors) . ' | ' . json_encode($employee->errors), 'leave-debug');
        return [
            'success' => false,
            'message' => 'Failed to update leave request or employee record.',
            'leaveRequestErrors' => $leaveRequest->errors,
            'employeeErrors' => $employee->errors
        ];
    }
    }


// public function actionApproveLeave()
// {
//     Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//     $request = Yii::$app->request->post();

//     \Yii::error('Received POST Data: ' . json_encode($request), 'leave-debug');

//     if (!isset($request['id']) || !isset($request['status'])) {
//         return ['success' => false, 'message' => 'Missing required parameters.'];
//     }

//     $leaveId = (int)$request['id'];
//     $leaveRequest = LeaveRequest::findOne($leaveId);
//     if (!$leaveRequest) {
//         return ['success' => false, 'message' => 'Leave request not found.'];
//     }

//     $employee = \backend\models\Employee::findOne(['user_id' => $leaveRequest->employee_id]);
//     if (!$employee) {
//         return ['success' => false, 'message' => 'Employee record not found.'];
//     }

//     // Get number of leave days from column
//     $leaveDays = (int)$leaveRequest->no_of_days;

//     // Set pay type
//     $leaveRequest->pay_type = $request['pay_type'] ?? 'with pay';

//     // Deduct leave balance only for approved, paid, and with-pay annual leave
//     if (
//         $request['status'] === 'approve' &&
//         $leaveRequest->leave_type === 'Paid Annual Leave'
//     ) {
//         if ($employee->annual_leave < $leaveDays) {
//             return ['success' => false, 'message' => 'Insufficient annual leave balance.'];
//         }

//         $employee->annual_leave -= $leaveDays;
//     }

//     // Assign fields
//     $leaveRequest->status = $request['status'];
//     $leaveRequest->remarks = $request['remarks'] ?? null;
//     $leaveRequest->leave_coverage = $request['leave_coverage'] ?? null;
//     $leaveRequest->approved_by = $request['approved_by'] ?? null;
//     $leaveRequest->approved_on = date('Y-m-d');

//     if ($leaveRequest->save() && $employee->save()) {

//         $leaveCoverage = $this->getLeaveCoverageName($leaveRequest->leave_coverage);

//         $statusMap = [
//             'approve' => 'Approved',
//             'reject' => 'Rejected',
//             'postpone' => 'Postponed',
//         ];
//         $statusText = $statusMap[$request['status']] ?? 'Pending';

//         $subject = "Leave Request {$statusText}";
//         $message = "
//             <p>Dear {$employee->first_name},</p>
//             <p>Your leave request for <strong>{$leaveDays}</strong> working day(s), from <strong>{$leaveRequest->start_date}</strong> to <strong>{$leaveRequest->end_date}</strong>, has been <strong>{$statusText}</strong>.</p>
//             <p><strong>Leave Type:</strong> {$leaveRequest->leave_type}</p>
//             <p><strong>Remarks:</strong> {$leaveRequest->remarks}</p>
//             <p><strong>Leave Coverage:</strong> {$leaveCoverage}</p>
//             <p><strong>Remaining Annual Leave Balance:</strong> {$employee->annual_leave} day(s)</p>
//             <p>Thank you,<br>HR Department</p>
//         ";

//         // Get HR sender info
//         $orgId = $employee->organisation_id;
//         $hrUserIds = (new \yii\db\Query())
//             ->select('tbl_rbac_auth_assignment.user_id')
//             ->from('tbl_rbac_auth_assignment')
//             ->innerJoin('tbl_user', 'tbl_rbac_auth_assignment.user_id = tbl_user.id')
//             ->where([
//                 'tbl_rbac_auth_assignment.item_name' => 'HR Manager',
//                 'tbl_user.organisation_id' => $orgId
//             ])
//             ->column();

//         $hrUsers = User::find()->where(['id' => $hrUserIds])->all();
//         $fromEmail = $hrUsers[0]->email ?? 'hr@company.com';
//         $fromName = $hrUsers[0]->username ?? 'HR Department';

//         // Notify employee
//         Yii::$app->mailer->compose()
//             ->setTo($employee->email)
//             ->setFrom([$fromEmail => "HR - {$fromName}"])
//             ->setSubject($subject)
//             ->setHtmlBody($message)
//             ->send();

//         // Notify department manager if present
//         if (!empty($request['department_manager'])) {
//             $deptManagerEmployee = \backend\models\Employee::findOne($request['department_manager']);
//             if ($deptManagerEmployee) {
//                 $deptManager = User::findOne($deptManagerEmployee->user_id);
//                 if ($deptManager) {
//                     $managerMessage = "
//                         <p>Dear {$deptManager->username},</p>
//                         <p>The leave request of <strong>{$employee->first_name}</strong> ({$employee->position}) for <strong>{$leaveDays}</strong> working day(s), from <strong>{$leaveRequest->start_date}</strong> to <strong>{$leaveRequest->end_date}</strong>, has been <strong>{$statusText}</strong>.</p>
//                         <p><strong>Leave Type:</strong> {$leaveRequest->leave_type}</p>
//                         <p><strong>Remarks:</strong> {$leaveRequest->remarks}</p>
//                         <p><strong>Leave Coverage:</strong> {$leaveCoverage}</p>
//                         <p>Thank you,<br>HR Department</p>
//                     ";

//                     Yii::$app->mailer->compose()
//                         ->setTo($deptManager->email)
//                         ->setFrom([$fromEmail => "HR - {$fromName}"])
//                         ->setSubject("Leave Request {$statusText}: {$employee->first_name}")
//                         ->setHtmlBody($managerMessage)
//                         ->send();
//                 }
//             }
//         }

//         // Create system notification
//         $notification = new Notification();
//         $notification->from_user_id = Yii::$app->user->id;
//         $notification->to_user_id = $leaveRequest->employee_id;
//         $notification->title = "Leave Request {$statusText}";
//         $notification->message = "Your leave request ({$leaveDays} day(s)) from {$leaveRequest->start_date} to {$leaveRequest->end_date} has been {$statusText}.";
//         $notification->table_name = 'tbl_leave_requests';
//         $notification->record_id = $leaveRequest->id;
//         $notification->link = '/leave-request/view?id='. $leaveRequest->id;
//         $notification->is_read = 0;
//         $notification->created_at = date('Y-m-d H:i:s');
//         if (!$notification->save()) {
//             Yii::error("Failed to save notification: " . json_encode($notification->errors), 'leave-debug');
//         }

//         return ['success' => true, 'message' => 'Leave request processed and notifications sent.'];
//     } else {
//         \Yii::error('Save Failed: ' . json_encode($leaveRequest->errors) . ' | ' . json_encode($employee->errors), 'leave-debug');
//         return [
//             'success' => false,
//             'message' => 'Failed to update leave request or employee record.',
//             'leaveRequestErrors' => $leaveRequest->errors,
//             'employeeErrors' => $employee->errors
//         ];
//     }
// }


// public function actionCalendar()
// {
//     $leaveRequests = LeaveRequest::find()->all();
//     return $this->render('calendar', [
//         'leaveRequests' => $leaveRequests,
//     ]);
// }

// public function actionCalendar($month = null, $year = null)
// {
//     if (!$month) $month = date('m');
//     if (!$year) $year = date('Y');

//     $startDate = Carbon::createFromDate($year, $month, 1);
//     $endDate = $startDate->copy()->endOfMonth();

//     $employees = Employee::find()->all();

//     $leaves = LeaveRequest::find()
//         ->andWhere(['or',
//             ['between', 'start_date', $startDate->toDateString(), $endDate->toDateString()],
//             ['between', 'end_date', $startDate->toDateString(), $endDate->toDateString()],
//             ['and',
//                 ['<=', 'start_date', $startDate->toDateString()],
//                 ['>=', 'end_date', $endDate->toDateString()]
//             ]
//         ])
//         ->all();

//     $period = CarbonPeriod::create($startDate, $endDate);

//     $leaveMap = [];
//     foreach ($leaves as $leave) {
//     $leavePeriod = CarbonPeriod::create($leave->start_date, $leave->end_date);
//     foreach ($leavePeriod as $date) {
//         $leaveMap[$leave->employee_id][$date->toDateString()] = [
//             'type' => $leave->leave_type,
//             'status' => $leave->status,
//         ];
//     }
// }


//     $prevMonth = $startDate->copy()->subMonth();
//     $nextMonth = $startDate->copy()->addMonth();

//     return $this->render('calendar', [
//         'employees' => $employees,
//         'leaveMap' => $leaveMap,
//         'period' => $period,
//         'startDate' => $startDate,
//         'prevMonth' => $prevMonth,
//         'nextMonth' => $nextMonth,
//         'month' => $month,
//         'year' => $year,
//     ]);
// }


public function actionCalendar($month = null, $year = null)
{
    if (!$month) $month = date('m');
    if (!$year) $year = date('Y');

    $startDate = Carbon::createFromDate($year, $month, 1);
    $endDate = $startDate->copy()->endOfMonth();
    $period = CarbonPeriod::create($startDate, $endDate);

    $currentUser = Yii::$app->user->identity;
    $role = $currentUser->getRole(); // assumes this returns role name
    $userId = $currentUser->id;

    // 🔹 Step 1: Filter employees based on role
    if ($role === GlobalConstant::ROLE_TEAM_MANAGER) {
        $employees = Employee::find()
            ->alias('e')
            ->innerJoin('tbl_teams t', 'e.team = t.id')
            ->where(['t.team_manager' => $userId])
            ->all();
    } elseif ($role === GlobalConstant::ROLE_DEPARTMENT_MANAGER) {
        $employees = Employee::find()
            ->alias('e')
            ->innerJoin('department d', 'e.department_id = d.id')
            ->where(['d.department_manager' => $userId])
            ->all();
    } elseif (in_array($role, [GlobalConstant::ROLE_HR_MANAGER, GlobalConstant::ROLE_SUPERADMIN])) {
        $employees = Employee::find()->all();
    } else {
        // Normal Employee → show only their own data
        $employees = Employee::find()->where(['user_id' => $userId])->all();
    }

    // 🔹 Step 2: Fetch employee IDs
    $employeeUserIds = ArrayHelper::getColumn($employees, 'user_id');

    // 🔹 Step 3: Get leave requests within month for those employees
    $leaves = LeaveRequest::find()
        ->where(['employee_id' => $employeeUserIds])
        ->andWhere([
            'or',
            ['between', 'start_date', $startDate->toDateString(), $endDate->toDateString()],
            ['between', 'end_date', $startDate->toDateString(), $endDate->toDateString()],
            ['and',
                ['<=', 'start_date', $startDate->toDateString()],
                ['>=', 'end_date', $endDate->toDateString()]
            ]
        ])
        ->all();

    // 🔹 Step 4: Map leave data
    $leaveMap = [];
    foreach ($leaves as $leave) {
        $leavePeriod = CarbonPeriod::create($leave->start_date, $leave->end_date);
        foreach ($leavePeriod as $date) {
            $leaveMap[$leave->employee_id][$date->toDateString()] = [
                'type' => $leave->leave_type,
                'status' => $leave->status,
            ];
        }
    }

    $prevMonth = $startDate->copy()->subMonth();
    $nextMonth = $startDate->copy()->addMonth();

    return $this->render('calendar', [
        'employees' => $employees,
        'leaveMap' => $leaveMap,
        'period' => $period,
        'startDate' => $startDate,
        'prevMonth' => $prevMonth,
        'nextMonth' => $nextMonth,
        'month' => $month,
        'year' => $year,
    ]);
}

private function getLeaveCoverageName($leaveCoverageId)
{
    $coverage = User::findOne($leaveCoverageId);
    return $coverage ? $coverage->username : 'N/A';
}

    /**
     * Finds the LeaveRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LeaveRequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LeaveRequest::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
public function actionHistory()
{
    $searchModel = new LeaveRequestSearch();
    $dataProvider = $searchModel->searchHistory(Yii::$app->request->queryParams);

    return $this->render('history', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]);
}

public function actionWfhRequest()
    {
        $model = new LeaveRequest();

        if ($model->load(Yii::$app->request->post())) {

            $employee = Employee::findOne(['user_id' => Yii::$app->user->id]);

            if ($employee) {
                $model->employee_id = $employee->id;
                $model->created_at = date('Y-m-d H:i:s');
                $model->updated_at = date('Y-m-d H:i:s');
                $model->status = 'Pending'; // or your enum value

                // Optional: Set leave_type to WFH for tracking, if you use leave_type column
                $model->leave_type = 'WFH';

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Your Work From Home request has been submitted.');
                    return $this->redirect(['index']); // or wherever you want to redirect
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to submit WFH request. Please try again.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Employee profile not found.');
            }
        }

        return $this->render('wfh_request', [
            'model' => $model,
        ]);
}

    public function actionWfhCreate()
{

     $model = new LeaveRequest();
    $departmentManagerId = Yii::$app->request->post('department_manager');

    // Debug if needed
    // print_r($_POST);

    if ($model->load(Yii::$app->request->post())) {

        // ✅ Calculate working days (excluding weekends)
        $start = Carbon::parse($model->start_date);
        $end = Carbon::parse($model->end_date);
        $period = CarbonPeriod::create($start, $end);

        $workingDays = 0;
        foreach ($period as $date) {
            if (!$date->isWeekend()) {
                $workingDays++;
            }
        }

        // ✅ Set number of working days in the model
        $model->no_of_days = $workingDays;

        // ✅ Assign notes safely (only if it exists)
        $postData = Yii::$app->request->post('LeaveRequest');
        if (isset($postData['notes'])) {
            $model->notes = $postData['notes'];
        }

        // ✅ Save the model and send notifications
        if ($model->save()) {
            // Notify department manager
            $this->createNotification($model, $departmentManagerId);
            $this->sendLeaveRequestEmail($model, $departmentManagerId);

            // Notify all HR managers
            // $hrManagerIds = $this->getHrManagerIds();
            // foreach ($hrManagerIds as $hrUserId) {
            //     $this->createNotification($model, $hrUserId);
            //     $this->sendLeaveRequestEmail($model, $hrUserId);
            // }

             $employee = \backend\models\Employee::findOne(['user_id' => $model->employee_id]);
            $hrManagerIds = $this->getHrManagerIds($employee->organisation_id ?? null);
            foreach ($hrManagerIds as $hrUserId) {
                  $this->createNotification($model, $hrUserId);
                $this->sendLeaveRequestEmail($model, $hrUserId);
            }
            Yii::$app->session->setFlash('success', 'Leave request submitted successfully.');
            return $this->redirect(['wfh-index']);
        }
    }

    return $this->render('wfh-create', ['model' => $model]);

   
}

public function actionWfhIndex()
{
  
 $searchModel = new LeaveRequestSearch();
  $perPage = Yii::$app->request->get('per-page', 10);
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $perPage,'WFH');
    
    $model = new LeaveRequest();
    // Filter to show only WFH leave_type entries
    // $dataProvider->query->andWhere(['leave_type' => 'WFH']);

    return $this->render('wfh-index', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
         'model' => $model
    ]); 
}
 public function actionWfhUpdate($id)
{
    $model = $this->findModel($id);
    $departmentManagerId = Yii::$app->request->post('department_manager');

    // Allow editing only if status is pending or postpone
    if (!in_array($model->status, ['pending', 'postpone'])) {
        Yii::$app->session->setFlash('error', 'You cannot update a leave request that is Approved or Rejected.');
        return $this->redirect(['index']);
    }

    $originalStatus = $model->status;

    if ($model->load(Yii::$app->request->post())) {
        // If request was postponed, reset status to pending after edit
        if ($originalStatus === 'postpone') {
            $model->status = 'pending';
        }

        if ($model->save()) {
            // Send email notification
            $this->sendLeaveRequestEmail($model, $departmentManagerId);

            Yii::$app->session->setFlash('success', 'Work From Home request updated successfully.');
            return $this->redirect(['wfh-index']);
        }
    }

    return $this->render('wfh-create', [
        'model' => $model,
    ]);
}

}
