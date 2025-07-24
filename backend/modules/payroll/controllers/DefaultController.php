<?php

namespace backend\modules\payroll\controllers;
use backend\modules\payroll\models\ZohoAuthToken;
use backend\modules\payroll\models\Payroll;
use backend\modules\payroll\models\PayrollPayPeriodSetting;
use backend\models\Employee;
use backend\models\ApiReqResLog;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * Default controller for the `payroll` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */

 
    public function actionIndex()
    {   
        if(isset($_POST['rowObject']))
        {
            $this->dateCreate("9 - Oct", "2022");

            
            $startYear = substr($_POST['startDate'], -4);
            $endYear = substr($_POST['endDate'], -4);
            
        $excelData = json_decode(($_POST['rowObject']), true);
        foreach($excelData as $data)
        {
            $email= $data["Email ID"];
            $name = $data["Employee Name"];
            unset($data["Employee Id"]);
            unset($data["Employee Name"]);
            unset($data["Email ID"]);
            $days = array_keys($data);
            
            $exists = Payroll::find()->where([ 'email' => $email])->andWhere(['day' => $days[0] ])->exists();
            if($exists)
            {
                foreach($days as $day)
                {
                    $row = Payroll::find()->where([ 'email' => $email])->andWhere(['day' => $day])->one();                    
                    $row->status =  $data[$day];
                    $row->year = $startYear;
                    $row->date = $this->dateCreate($day, $startYear);
                    $row->save();
                }  
                echo "Attendance of employee ". $name . " Updated \n";
            }
            else
            {
                
                foreach($days as $day)
                {
                    $row = new Payroll();
                    $row->email = $email;
                    $row->day = $day;
                    $row->year = $startYear;
                    $row->status = $data[$day];
                    $row->date = $this->dateCreate($day, $startYear);
                    $row->save();
                }
                echo "Attendance of employee ". $name . " saved \n";
                
            }

            Yii::$app->session->setFlash('success', "Attendance data saved!");
            Yii::$app->response->redirect(['slip/index']);
        }

        }
        else{
            $payPeriodModel = PayrollPayPeriodSetting::find()->one();
            if (empty($payPeriodModel)) {
                $payPeriodModel = new PayrollPayPeriodSetting();
                $payPeriodModel->start_date = "1";
                $payPeriodModel->end_date = "Last Day";
            }
            return $this->render('index', [
                'payPeriodModel' => $payPeriodModel,
            ]);
        }
    }
    public function dateCreate($day, $year)
    {
        $month = substr($day,-3);
        
        $date = substr($day,0,(strpos($day,' ')));
       
        if($date<"10")
        $date= '0'.$date;
       
        switch ($month) {
            case "January":        
            case "Jan":
                $month = "01";
                break;
            case "February":
            case "Feb":
                $month = "02";
                break;
            case "March":
            case "Mar":
                $month = "03";
                break;
            case "April":
            case "Apr":
                $month = "04";
                break;
            case "May":
                $month = "05";
                break;
            case "June":
            case "Jun":
                $month = "06";
                break;
            case "July":        
            case "Jul":
                $month = "07";
                break;
            case "August":
            case "Aug":
                $month = "08";
                break;
            case "September":
            case "Sep":
                $month = "09";
                break;
            case "October":
            case "Oct":
                $month = "10";
                break;
            case "November":
            case "Nov":
                $month = "11";
                break;
            case "December":
            case "Dec":
                $month = "12";
                break;
            default:
                $month = false;
                break;
        }
        return $year.'-'.$month.'-'.$date;
    
    }

    private function leaveTypeAPI($accessToken) {
        $serviceUrl = "https://people.zoho.com/people/api/leave/getLeaveTypeDetails?userId=habib.rehman@pangeaworldwide.sa";
        $curl = curl_init($serviceUrl);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Zoho-oauthtoken '.$accessToken,
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

        $curlResponse = curl_exec($curl);

        echo '<pre>';
        var_dump(json_decode($curlResponse));
        echo '</pre>'; die();
        $info = curl_getinfo($curl);
        if ($curlResponse === false) {
            curl_close($curl);
            die('error occured during curl exec. Additional info: ' . var_dump($info));
        }
        curl_close($curl);

        $statusArray = json_decode($curlResponse);
        return [
            'status' => $info['http_code'],
            'response' => $curlResponse,
        ];
    }

    private function fetchEmployeeAttendanceDataZoho($accessToken, $startDate, $endDate) {
        // $sdate = date('01-M-Y', strtotime($month . $year));
        // $edate = date('t-M-Y', strtotime($month . $year));

        $employeeEmails = ArrayHelper::getColumn(Employee::find()->select('email')->all(), 'email');
        
        foreach($employeeEmails as $employeeEmail) {
            $serviceUrl = "https://people.zoho.com/people/api/attendance/getUserReport?sdate=".$startDate."&edate=".$endDate."&emailId=".$employeeEmail;

            $curl = curl_init($serviceUrl);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Authorization: Zoho-oauthtoken '.$accessToken,
            ));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

            $curlResponse = curl_exec($curl);
            $info = curl_getinfo($curl);
            if ($curlResponse === false) {
                curl_close($curl);
                die('error occured during curl exec. Additional info: ' . var_dump($info));
            }
            curl_close($curl);

            $statusArray = json_decode($curlResponse);

            if ($info['http_code'] == 401) {
                return [
                    'status' => $info['http_code'],
                    'response' => $statusArray
                ];
            }

            if ($info['http_code'] == 200) {
                foreach($statusArray as $date => $employeeData) {
                    $existing = Payroll::find()->where(['email' => $employeeEmail])->andWhere(['date' => $date])->one();
    
                    if (!empty($existing)) {
                        $existing->status = $employeeData->Status;
                        $existing->save();
                        continue;
                    }
    
                    $payroll = new Payroll();
                    $payroll->status = $employeeData->Status;
                    $payroll->date = $date;
                    $payroll->email = $employeeEmail;
                    $payroll->day = date('d - M', strtotime($date));
                    $payroll->year = date('Y', strtotime($date));
                    $payroll->save();
                }
            } else {
                $apiLog = new ApiReqResLog();
                $apiLog->api_type = 'GET';
                $apiLog->api_url = $serviceUrl;
                $apiLog->response_body = json_encode($curlResponse);
                $apiLog->save();
                Yii::$app->session->setFlash('alert', [
                    'body'=>\Yii::t('backend', 'Attendance could not be fetched. Please check logs for more details'),
                    'options'=>['class'=>'alert-success']
                ]);
                return $this->redirect('index');
            }
        }

        return [
            'status' => $info['http_code'],
            'response' => $curlResponse,
        ];
    }

    private function refreshAccessTokenZoho($refreshToken) {
        $serviceUrl = getenv('ZOHO_ACCOUNTS_URL')."/oauth/v2/token?refresh_token=".$refreshToken."&client_id=".getenv('ZOHO_CLIENT_ID')."&client_secret=".getenv('ZOHO_CLIENT_SECRET')."&grant_type=refresh_token";
        $curl = curl_init($serviceUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);

        $curlResponse = curl_exec($curl);
        $info = curl_getinfo($curl);
        if ($curlResponse === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            die('error occured during curl exec. Additional info: ' . var_dump($info));
        }
        curl_close($curl);
        if ($info['http_code'] == 200) {
            $decoded = json_decode($curlResponse);
            if (isset($decoded->error)) {
                die('error occured: ' . $decoded->error);
            }
            return $decoded->access_token;
        } else {
            $apiLog = new ApiReqResLog();
            $apiLog->api_type = 'POST';
            $apiLog->api_url = $serviceUrl;
            $apiLog->response_body = json_encode($curlResponse);
            $apiLog->save();
            Yii::$app->session->setFlash('alert', [
                'body'=>\Yii::t('backend', 'Attendance could not be fetched. Please check logs for more details'),
                'options'=>['class'=>'alert-success']
            ]);
            return $this->redirect('index');
        }
    }
    
    public function actionZoho() {
        // flow description: 
        // handler for redirect from zoho - saves access and refresh tokens in DB
        // if not redirect from zoho, tries to find existing tokens for current user
        // if find is successful - try to use access token - if that fails, check refresh token expiry
            // if expired - pass the scope and generate fresh tokens and save in DB with 1 week expiry time
            // if not expired - generate new access token and update the DB entry
        // if find is unsuccessful - redirect to zoho

        $scope = "ZOHOPEOPLE.attendance.READ,ZOHOPEOPLE.leave.READ";
        if (isset($_GET['code']) && isset($_GET['state'])) {
            $state = explode(',', $_GET['state']);
            $startDate = $state[0];
            $endDate = $state[1];
            // generate fresh access and refresh tokens after OAuth from zoho and save in DB
            $grantCode = $_GET['code'];
            $serviceUrl = getenv('ZOHO_ACCOUNTS_URL')."/oauth/v2/token";
            $curl = curl_init($serviceUrl);
            $curlPostData = array(
                'grant_type' => 'authorization_code',
                'client_id' => getenv('ZOHO_CLIENT_ID'),
                'client_secret' => getenv('ZOHO_CLIENT_SECRET'),
                'redirect_uri' => getenv('BACKEND_URL')."payroll/default/zoho",
                'code' => $grantCode, 
            );
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPostData);
            $curlResponse = curl_exec($curl);
            $info = curl_getinfo($curl);
            if ($curlResponse === false) {
                curl_close($curl);
                die('error occured during curl exec. Additional info: ' . var_dump($info));
            }
            curl_close($curl);
            if ($info['http_code'] == 200) {
                $decoded = json_decode($curlResponse);
                if (isset($decoded->error)) {
                    die('error occured: ' . $decoded->error);
                }
                $model = new ZohoAuthToken();
                $model->access_token = $decoded->access_token;
                $model->refresh_token = $decoded->refresh_token;
                //setting 1 week expiry of refresh token
                $model->expires_on = date('Y-m-d', strtotime('+1 week', strtotime(date('Y-m-d'))));
                $model->scope = $scope;
                $model->user_id = Yii::$app->user->id;

                if ($model->save()) {
                    //make api call here
                    $result = $this->fetchEmployeeAttendanceDataZoho($model->access_token, $startDate, $endDate);
                    // $result = $this->leaveTypeAPI($model->access_token);
                    Yii::$app->session->setFlash('success', "Attendance data saved!");
                    return $this->redirect('index');
                }
            } else {
                $apiLog = new ApiReqResLog();
                $apiLog->api_type = 'POST';
                $apiLog->api_url = $serviceUrl;
                $apiLog->request_body = $curlPostData;
                $apiLog->response_body = json_encode($curlResponse);
                $apiLog->save();
                Yii::$app->session->setFlash('alert', [
                    'body'=>\Yii::t('backend', 'Attendance could not be fetched. Please check logs for more details'),
                    'options'=>['class'=>'alert-success']
                ]);
                return $this->redirect('index');
            }
        }

        if (Yii::$app->request->post()) {
            $startDate = Yii::$app->request->post()["start_date"];
            $endDate = Yii::$app->request->post()["end_date"];
            
            $model = ZohoAuthToken::find()->where(['user_id' => Yii::$app->user->id, 'scope' => $scope])->one();
            if (!empty($model)) {
                //check if refresh token needs to be updated 
                if ($model->expires_on < date('Y-m-d')) {
                    $model->delete();
                    $redirectUrl = getenv('ZOHO_ACCOUNTS_URL')."/oauth/v2/auth?scope=".$scope."&client_id=".getenv('ZOHO_CLIENT_ID')."&response_type=code&access_type=offline&redirect_uri=".getenv('BACKEND_URL')."payroll/default/zoho&prompt=consent&state=".$startDate.",".$endDate;
                    return $this->redirect($redirectUrl);
                }

                //make api calls here
                $result = $this->fetchEmployeeAttendanceDataZoho($model->access_token, $startDate, $endDate);
                if (isset($result['status']) && $result['status'] == 200) {
                    Yii::$app->session->setFlash('success', "Attendance data saved!");
                    return $this->redirect('index');
                }
                if (isset($result['status']) && $result['status'] == 401) {
                    $newAccessToken = $this->refreshAccessTokenZoho($model->refresh_token);
                    $model->updateAttributes(['access_token' => $newAccessToken, 'expires_on' => date('Y-m-d', strtotime('+1 week', strtotime(date('Y-m-d'))))]);
                    $result = $this->fetchEmployeeAttendanceDataZoho($model->access_token, $startDate, $endDate);
                    Yii::$app->session->setFlash('success', "Attendance data saved!");
                    return $this->redirect('index');
                } 
            }     
            // if no access token exists in DB and Zoho authentication hasn't been performed yet, we redirect to Zoho
            $redirectUrl = getenv('ZOHO_ACCOUNTS_URL')."/oauth/v2/auth?scope=".$scope."&client_id=".getenv('ZOHO_CLIENT_ID')."&response_type=code&access_type=offline&redirect_uri=".getenv('BACKEND_URL')."payroll/default/zoho&prompt=consent&state=".$startDate.",".$endDate;
            return $this->redirect($redirectUrl);
        }
    }

    public function actionPayPeriodSettings() {
        if (Yii::$app->request->post()) {
            $payPeriodModel = PayrollPayPeriodSetting::find()->one();
            if (empty($payPeriodModel)) {
                $payPeriodModel = new PayrollPayPeriodSetting();
            }

            if ($payPeriodModel->load(Yii::$app->request->post()) && $payPeriodModel->save()) {
                Yii::$app->session->setFlash('success', "Pay period settings saved!");
                return $this->redirect('index');
            }
        }
    }
}   
