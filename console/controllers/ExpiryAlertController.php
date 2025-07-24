<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use app\models\Document;
use backend\models\FileUpload;
use backend\components\Helper;
use backend\models\Cases;
use common\models\User;
use yii\db\Query;
use yii\helpers\Console;

class ExpiryAlertController extends Controller
{
    public function actionCheckExpiry()
    {

        Helper::sendEmailViaSes('alerts@northmansterling.app', "rahulkumar.handysolver@gmail.com", null, 'test mail', 'Test mail', null, null, null);
        // Get today's date $alertIntervals = [7, 15, 30];
    
        $INTERVAL_TYPE_ARRAY = [
            1 => [7, 15, 30],
            2 => [30, 60, 90],
            3 => [30, 90, 120],
        ];

        $today = date('Y-m-d');

        // Calculate 120 days from today (this is the max expiry date range we're interested in)
        $maxExpiryDate = date('Y-m-d', strtotime("+120 days"));

        $files = FileUpload::find()
            ->where(['>=', 'expiry_date', $today])  // Expiry date is not in the past
            ->andWhere(['<=', 'expiry_date', $maxExpiryDate])  // Expiry date is within the next 120 days
            ->all();
        // Define the alert intervals (7, 15, 30 days before expiry)
        foreach ($files as $file) {
            $case = Cases::findOne(['additional_attachments' => $file->file_id]);
            $alertIntervals = $INTERVAL_TYPE_ARRAY[$file->interval_days_type_id];
            $expiryDate = $file->expiry_date;
            echo 'Today: ' . $today . "-------------";            // Add the new line here
            // Check for each alert interval
            foreach ($alertIntervals as $daysBefore) {
                // Calculate the alert date (7, 15, or 30 days before expiry)
                $alertDate = date('Y-m-d', strtotime("-$daysBefore days", strtotime($expiryDate)));
                // To make sure the newlines work correctly in the terminal or output


                // If today matches the alert date, send the alert
                if ($alertDate == $today) {
                    echo 'Alert date: ' . $alertDate . "-------------";    // Add the new line here
                    // Call your function to send the expiry alert
                    $this->sendExpiryAlert($file, $daysBefore, $case);  // You should define the `sendExpiryAlert` function
                } else {
                    echo "No Dates found to send email. Invalid Interval";
                }
            }
        }
    }

    private function sendExpiryAlert($file, $days, $case)
    {
        try {
            // Fetch the manager's email
            $manager = User::find()
                ->select('email')
                ->where(['id' => $case->case_manager_id])
                ->one();

            // Fetch the worker's email
            $worker = User::find()
                ->select('email')
                ->where(['id' => $case->assigned_to])
                ->one();

            // Fetch the admin's email (superadmin)
            $admin = (new Query())
                ->select('u.email')
                ->from('tbl_rbac_auth_assignment a')
                ->innerJoin('tbl_rbac_auth_item i', 'a.item_name = i.name')
                ->innerJoin('tbl_user u', 'a.user_id = u.id')
                ->where(['i.name' => 'administrator'])
                ->limit(1)
                ->scalar();

            // Initialize the $emails array and add the emails to it
            $emails = [];
            if ($manager) {
                $emails[] = $manager->email;  // Only add the email if the record exists
            }
            if ($worker) {
                $emails[] = $worker->email;   // Only add the email if the record exists
            }
            if ($admin) {
                $emails[] = $admin;// Admin is already a string (email), so add directly
            }

            var_dump($emails);
            $subject = "Document Expiry Alert: '{$case->case_number}'";
            $body = "Hi,\nJust a quick reminder that the '{$file->name}' for {$case->case_number} will expire in {$days} days.\nExpiry Date: {$file->expiry_date}\n\nPlease take necessary action.";
            foreach ($emails as $value) {
                Helper::sendEmailViaSes('alerts@northmansterling.app', $value, null, $subject, $body, null, null, null);
            }
        } catch (\Exception $e) {
            Yii::error("Error sending email for case '{$case->case_number}': " . $e->getMessage());
        }
    }
    
}
