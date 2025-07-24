<?php

/**
 * Created by PhpStorm.
 * User: rahulsinghmatharu
 * Date: 03/08/17
 * Time: 5:33 PM
 */

namespace backend\controllers;

use backend\models\FileUpload;
use backend\components\Helper;
use backend\models\Cases;
use backend\models\Receipt;
use yii\web\Controller;
use backend\models\Client;
use backend\models\ClientOrganisation;
use common\models\User;
use DateTime;
use Yii;
use yii\db\Query;

class SandboxController extends Controller
{
    public function actionIndex()
    {
        if (extension_loaded('mbstring')) {
            echo "loaded";
        } else
            echo 'not';
        echo phpinfo();
        die();

        echo Helper::firstDateOfMonth(date('Y-m-d'));
        echo "<br>";
        echo Helper::lastDateOfMonth(date('Y-m-d'));
    }

    public function actionNewDate()
    {
        $receipts = Receipt::find()->all();
        foreach ($receipts as $receipt) {
            $receipt->new_date = date('Y-m-d', strtotime($receipt->date));
            if ($receipt->save()) {
                echo $receipt->new_date . '<br>';
            } else {
                echo "<pre>";
                var_dump($receipt->getFirstError());
                echo "</pre>";
            }
        }
    }
    public function actionMappingSaveClientOrganisation()
    {
        $clients = Client::find()->all();
        // $i=1;
        foreach ($clients as $client) {
            if ($client->organisation_id) {
                $clientOrg = new ClientOrganisation();
                $clientOrg->client_id = $client->id;
                $clientOrg->organisation_id = $client->organisation_id;
                $clientOrg->save();
            }
            // echo $i++.' :: '.$client->id.' : '.$client->client_name.' - '.$client->organisation_id.'<br>';
            echo "All Mapping saved";
        }
    }
    public function actionTest()
    {
        $email = 'rahulkumar.handysolver@gmail.com';
        $subject = 'Reset Password Link.';
        $message = '
        <p>Hello rahulkumar.handysolver@gmail.com,</p>
        <p>Follow the link below to reset your password::</p>
        <h3 style="font-size: 20px;"><a href="" target="_blank"></h3>';
        Helper::sendEmailViaSes('authverify@northmansterling.app', $email, null, $subject, $message, null, null, null);
    }

    public function actionCheckExpiry()
    {
        $INTERVAL_TYPE_ARRAY = [
            1 => [7, 15, 30],
            2 => [30, 60, 90],
            3 => [30, 90, 120],
        ];
        // Get today's date$alertIntervals = [7, 15, 30];

        $today = date('Y-m-d');

        // Calculate 120 days from today (this is the max expiry date range we're interested in)
        $maxExpiryDate = date('Y-m-d', strtotime("+120 days"));

        // Fetch records where expiry_date is within the next 120 days (i.e., expiry date is between today and 120 days from now)
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
                $emails[] = 'meena.handysolver@gmail.com';  // Only add the email if the record exists
            }
            if ($worker) {
                $emails[] = 'meena.handysolver@gmail.com';   // Only add the email if the record exists
            }
            if ($admin) {
                $emails[] = 'meena.handysolver@gmail.com';// Admin is already a string (email), so add directly
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
