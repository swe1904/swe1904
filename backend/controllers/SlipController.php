<?php
        namespace backend\controllers;

        use app\components\GlobalConstant;
        use backend\components\Helper;
        use backend\models\Employee;
        use DateTime;
        use Yii;
        use backend\models\Slip;
        use backend\models\SlipItem;
        use backend\models\search\SlipSearch;
        use yii\web\Controller;
        use yii\web\NotFoundHttpException;
        use yii\filters\VerbFilter;
        use yii\helpers\Json;
        use kartik\mpdf\Pdf;
        use backend\models\Organisation;
        use backend\modules\payroll\models\Payroll;
        use backend\models\SlipItemSection;
        use backend\models\DynamicCurrency;
        use backend\modules\payroll\models\PayrollPayPeriodSetting;
        /**
         * SlipController implements the CRUD actions for Slip model.
         */
        class SlipController extends Controller
        {
            /**
             * @inheritdoc
             */
            public function behaviors()
            {
                return [
                    'verbs' => [
                        'class' => VerbFilter::className(),
                        'actions' => [
                            'delete' => ['POST'],
                        ],
                    ],
                ];
            }

            /**
             * Lists all Slip models.
             * @return mixed
             */
            public function actionIndex()
            {
                $searchModel = new SlipSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }

public function actionSlipPdf($id) {


    $receiptModel = \backend\models\Slip::find()->with('employee')->where('id=:id', array(':id'=>$_GET['id']))->one();
    $receiptServiceModel =\backend\models\Earning::find()->all();
    $model = \backend\models\Organisation::find()->all();

    $url = "https://pangeaportal.com/backend/web/images/logo.png";
    $arrContextOptions=array(
        "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false,
        ),
    ); 
    $img = file_get_contents($url, false, stream_context_create($arrContextOptions));
    $data = base64_encode($img);


    $content=$this->renderPartial('view', [
        'model' => $model,
                'receiptModel'=>$receiptModel,
                'receiptServiceModel'=>$receiptServiceModel,

        
    ]);
        // var_dump($content); die();
        // $content = '<div class="text-center" style="padding-bottom: 10px"> </div> <div class="text-center"> <td style="width: 100px;text-align: center;" >Address pangea </td> <br> <br> <br> <tr> <td style="font-size: 16px;font-weight: bold;text-decoration: underline"> Payslip for the month of January 2022<br>Payslip for the period of 01-10-2022&nbsp;to&nbsp;31-10-2022 </td> </tr> </div> <br> <div> <table style="width: 100%;" border="3" cellpadding="5"> <tbody> <tr > <th>Name</th> <th>Tahir Ali</th> </tr> <tr> <td>Position</td> <td>Operator</td> </tr> </tr> <tr> <td>Permanent Address</td> <td>Karol Bagh New Delhi</td> </tr> <tr> <td>Account Number</td> <td> 12345678 </td> </tr> <tr> <td>Bank</td> <td> Indian Bank </td> </tr> </tbody> </table> </div> <br><br> <div> <table style="width: 100%;" border="3" cellpadding="5"> <tbody> <tr> <th>Compensation Breakdown</th> <th>Earnings</th> <th>Deduction</th> </tr> <tr> </tr> <tr> <td> Leave </td> <td style="border-bottom: 1pt solid black"> </td> <td style="border-bottom: 1pt solid black"> 0.00</td> </tr> <tr> <td>Total Earnings</td> <td style="width:50%"> <b>INR 20000.00</b> </td> </tr> <tr> <td style="font-weight: bold">Bonus</td> <td style="width:50%"> INR 500.00 </td> </tr> </tbody> </table> </div> <br> <div> <table style="width: 100%;" border="3" cellpadding="5"> <tbody> <tr> <td> Payment made via cheque number: &nbsp;156423 </td> </tr> </table> </div> <div> <table style="width: 100%;" border="3" cellpadding="5"> <tbody> <tr> <td> </td> </tr> </table> </div>';
        // $content = '
    // <div>
    //     <table style="width: 100%;" border="3" cellpadding="5">
    //         <tbody>
    //         <tr>
    //             <th>Name</th>
    //             <th>Tahir Ali</th>
    //         </tr>
    //         <tr>
    //             <td>Position</td>
    //             <td>Operator</td>
    //         </tr>
    //         </tr>
    //         <tr>
    //             <td>Permanent Address</td>
    //             <td>Karol Bagh New Delhi</td>
    //         </tr>
    //         <tr>
    //             <td>Account Number</td>
    //             <td> 12345678</td>
    //         </tr>
    //         <tr>
    //             <td>Bank</td>
    //             <td> Indian Bank</td>
    //         </tr>
    //         </tbody>
    //     </table>
    // </div> <br><br>
    // <div>
    //     <table style="width: 100%;" border="3" cellpadding="5">
    //         <tbody>
    //         <tr>
    //             <th>Compensation Breakdown</th>
    //             <th>Earnings</th>
    //             <th>Deduction</th>
    //         </tr>
    //         <tr></tr>
    //         <tr>
    //             <td> Leave</td>
    //             <td style="border-bottom: 1pt solid black"></td>
    //             <td style="border-bottom: 1pt solid black"> 0.00</td>
    //         </tr>
    //         <tr>
    //             <td>Total Earnings</td>
    //             <td style="width:50%"><b>INR 20000.00</b></td>
    //         </tr>
    //         <tr>
    //             <td style="font-weight: bold">Bonus</td>
    //             <td style="width:50%"> INR 500.00</td>
    //         </tr>
    //         </tbody>
    //     </table>
    // </div> <br>
    // <div>
    //     <table style="width: 100%;" border="3" cellpadding="5">
    //         <tbody>
    //         <tr>
    //             <td> Payment made via cheque number: &nbsp;156423</td>
    //         </tr>
    //     </table>
    // </div>
    // <div>
    //     <table style="width: 100%;" border="3" cellpadding="5">
    //         <tbody>
    //         <tr>
    //             <td></td>
    //         </tr>
    //     </table>
    // </div>';
// echo htmlspecialchars($content);
// var_dump($content);
// die();
$pdf = new Pdf([
            // set to use core fonts only
            // 'mode' =>  Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
//              'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // call mPDF methods on the fly
        ]);


  $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
       
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
       

          return $pdf->render();
}


        /**
             * Displays a single Slip model.
             * @param integer $id
             * @return mixed
             */
            public function actionView($id)
            {

        $receiptModel = \backend\models\Slip::find()->with('employee')->where('id=:id', array(':id'=>$_GET['id']))->one();

                $receiptServiceModel =\backend\models\Earning::find()->all();
                $model = \backend\models\Organisation::find()->all();
                return $this->render('view', [

                    'model' => $model,
            'receiptModel'=>$receiptModel,
            'receiptServiceModel'=>$receiptServiceModel,
                ]);
            }


            /**
             * Creates a new Slip model.
             * If creation is successful, the browser will be redirected to the 'view' page.
             * @return mixed
             */
            public function actionCreate()
            {
                $this->combineSectionsIntoSingleSlipItems();
                $model = new Slip();
                
                $monthName = Helper::currentMonthWords();
                $empId;
                $monthName;
                $year;
                
                if($urlMonth = Yii::$app->getRequest()->getQueryParam('payslip_month'))
                    $monthName = $urlMonth;
                $model->payslip_month = $monthName;
                
                $model->payslip_year = date("Y");
                
                $model->payment_mode = GlobalConstant::PAYMENT_MODE_ONLINE_CHEQUE;
                
                $model->start_date = Helper::firstDateOfMonth(date('Y-m-d'));
                $model->end_date = Helper::lastDateOfMonth(date('Y-m-d'));

                if ($model->loadAll($_POST)) {
                    $slipExists = Slip::find()->where(['employee_id' => $model->employee_id])->andWhere(['payslip_month' => $model->payslip_month])->andWhere(['payslip_year' => $model->payslip_year])->one();
                    if(!$slipExists)
                    {
                        $organisationModel = Organisation::find()->where(['user_id' => Yii::$app->user->identity->id])->one();
 
                        $model->organisation_id = $organisationModel->id;

                        if ($model->saveAll()) {
                            return $this->redirect(['index', 'id' => $model->employee_id]);
                        }
                    }
                    else
                    {
                        return $this->redirect(['index']);
                    }
                }
                return $this->render('create',
                    ['model' => $model,
                    ]);
            }

            /**
             * Action to load a tabular form grid
             * for SlipItem
             * @author Yohanes Candrajaya <moo.tensai@gmail.com>
             * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
             *
             * @return mixed
             */
            public function actionAddSlipItem($sectionId)
            {
                if (Yii::$app->request->isAjax) {
                    $row = Yii::$app->request->post('SlipItem-'.$sectionId);
                    if((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                        $row[] = ['section_id'=>$sectionId];
                    return $this->renderPartial('_formSlipItem', ['row' => $row, 'sectionId' => $sectionId]);
                } else {
                    throw new NotFoundHttpException('The requested page does not exist.');
                }
            }

            /**
             * Updates an existing Slip model.
             * If update is successful, the browser will be redirected to the 'view' page.
             * @param integer $id
             * @return mixed
             */
            public function actionUpdate($id)
            {
                $model = $this->findModel($id);

                $this->combineSectionsIntoSingleSlipItems();
                if ($model->loadAll($_POST) && $model->saveAll()) {
                    return $this->redirect(['index', 'id' => $model->employee_id]);
                } else {
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
            }

        public function actionGetSalary()
        {
            $empId = $_POST['empId'];
            $model = Employee::findOne($empId);
           return $model->salary;
        }





            /**
             * Deletes an existing Slip model.
             * If deletion is successful, the browser will be redirected to the 'index' page.
             * @param integer $id
             * @return mixed
             */
            public function actionDelete($id)
            {
                $this->findModel($id)->delete();

                return $this->redirect(['index']);
            }




            /**
             * Finds the Slip model based on its primary key value.
             * If the model is not found, a 404 HTTP exception will be thrown.
             * @param integer $id
             * @return Slip the loaded model
             * @throws NotFoundHttpException if the model cannot be found
             */
            protected function findModel($id)
            {
                if (($model = Slip::findOne($id)) !== null) {
                    return $model;
                } else {
                    throw new NotFoundHttpException('The requested page does not exist.');
                }
            }

            public function actionMonthsDates(){
                if(isset($_GET['monthName'])){
                    $monthName = $_GET['monthName'];
                    $monthYear = $_GET['monthYear'];

                    $date = date_parse($monthName);
                    $monthNumber = $date['month'];

                    $currentMonthsDate = "$monthYear-$monthNumber-01";
                    $return = array();
                    $payPeriodModel = PayrollPayPeriodSetting::find()->one();
                    if (empty($payPeriodModel)) {
                        $payPeriodModel = new PayrollPayPeriodSetting();
                        $payPeriodModel->start_date = "1";
                        $payPeriodModel->end_date = "Last Day";

                        $return['firstDate'] = Helper::firstDateOfMonth($currentMonthsDate);
                        $return['lastDate'] = Helper::lastDateOfMonth($currentMonthsDate);
                    } else {
                        $return['firstDate'] = date("d-m-Y", strtotime($payPeriodModel->start_date.'-'.$monthNumber.'-'.$monthYear));
                        if ($payPeriodModel->end_date == 'Last Day') {
                            $return['lastDate'] = date("t-m-Y", strtotime('01-'.$monthNumber.'-'.$monthYear));
                        } else {
                            $return['lastDate'] = date("d-m-Y", strtotime('+1 month', strtotime($payPeriodModel->end_date.'-'.$monthNumber.'-'.$monthYear)));
                        }
                    }

                    echo json_encode($return);
                }
            }

            public function actionSlipAlreadyExists(){
                $model = new Slip();
                $formData = Yii::$app->request->post();
                $model->load($formData);

                if(!empty($model->employee_id) && !empty($model->payslip_month) && !empty($model->payslip_year)){
                    $email = Employee::findOne($model->employee_id)->email;
                    $returnedModel = Slip::find()->where(['employee_id'=>$model->employee_id, 'payslip_month'=>$model->payslip_month,'payslip_year'=>$model->payslip_year ])->one();

                    $setting = PayrollPayPeriodSetting::find()->one();
                    if (empty($setting)) {
                        $setting = new PayrollPayPeriodSetting();
                        $setting->start_date = "1";
                        $setting->end_date = Helper::lastDateOfMonth($model->payslip_year. '-' . $model->payslip_month . '-01');
                    }
                    $startDate = date('Y-m-d', strtotime($setting->start_date . '-' . $model->payslip_month . '-' . $model->payslip_year));
                    $endDate = date('Y-m-d', strtotime("+1 month", strtotime($setting->end_date . '-' . $model->payslip_month . '-' . $model->payslip_year)));

                    $slipID = Yii::$app->request->post()['slipID'];

                    $rangeOverlap = $this->checkDateRangeOverlap($model->employee_id, $startDate, $endDate, $slipID); 

                    if(!empty($returnedModel) && $returnedModel->id != $slipID){
                        echo json_encode([
                            'status' => true,
                            'type' => "exists",
                            'employee_name' => $returnedModel->employee->name,
                            'month' => $model->payslip_month,
                            'year' => $model->payslip_year
                        ]);
                    }
                    elseif ($rangeOverlap) {
                        echo json_encode([
                            'status' => true,
                            'type' => 'overlap',
                            'employee_name' => Employee::findOne($model->employee_id)->name,
                            'startDate' => $startDate,
                            'endDate' => $endDate
                        ]);
                        die();
                    }
                    else {                     
                        $payrollRecord = $this->getPayrollRecord($email, $startDate, $endDate);  
                        if ($payrollRecord) {
                            echo $payrollRecord;
                        } else {
                            echo json_encode(['status'=>true,
                                'type' => "notfound",
                                'employee_name' => Employee::findOne($model->employee_id)->name,
                                'month' => $model->payslip_month,
                                'year' => $model->payslip_year
                            ]);
                        }
                    }
                } 
            }
            public function actionGetLeavesByDate() {
                if (Yii::$app->request->post()) {
                    $postData = Yii::$app->request->post();

                    if (!empty($postData['startDate']) && !empty($postData['endDate']) && !empty($postData['employeeID'])) {
                        $startDate = Yii::$app->request->post()["startDate"];
                        $endDate = Yii::$app->request->post()["endDate"];
    
                        //converting to SQL format
                        $startDate = date("Y-m-d", strtotime(str_replace('/', '-', $startDate)));
                        $endDate = date("Y-m-d", strtotime(str_replace('/', '-', $endDate)));
    
                        $employeeID = Yii::$app->request->post()["employeeID"];
                        $slipID = $postData['slipID'];

                        $rangeOverlap = $this->checkDateRangeOverlap($employeeID, $startDate, $endDate, $slipID);

                        if ($rangeOverlap) {
                            echo json_encode([
                                'status' => true,
                                'type' => 'overlap',
                                'employee_name' => Employee::findOne($employeeID)->name,
                                'startDate' => $startDate,
                                'endDate' => $endDate
                            ]);
                            die();
                        }

                        if (!empty($postData['month']) && !empty($postData['year']) && is_null($slipID)) {
                            $month = $postData['month'];
                            $year = $postData['year'];
                            $slip = Slip::find()->where([
                                'employee_id' => $employeeID, 
                                'payslip_month' => $month,
                                'payslip_year' => $year,
                            ])->one();

                            if (!empty($slip)) {
                                echo json_encode([
                                    'status' => true,
                                    'type' => "exists",
                                    'employee_name' => Employee::findOne($employeeID)->name,
                                    'month' => $month,
                                    'year' => $year
                                ]);
                                die();
                            }
                        }
    
                        $employeeEmail = Employee::findOne($employeeID)->email;
    
                        $payrollRecord = $this->getPayrollRecord($employeeEmail, $startDate, $endDate);

                        if ($payrollRecord) { 
                            echo $payrollRecord;
                        } else {
                            echo json_encode(['status'=>false,
                                'type' => "notfound",
                                'employee_name' => Employee::findOne($employeeID)->name,
                                'startDate' => $startDate,
                                'endDate' => $endDate
                            ]);
                        }
                    }
                }
            }

            private function getPayrollRecord($email, $startDate, $endDate)
            {   
                $returnedPayroll = Payroll::find()->where(['email' => $email])->andWhere("date >= :start_date and date <= :end_date", [
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ])->all();


                if($returnedPayroll)
                {
                    $fullAbsent = count(Payroll::find()->where(['email' => $email])->andWhere("date >= :start_date and date <= :end_date", [
                        'start_date' => $startDate,
                        'end_date' => $endDate
                    ])->andWhere(['status' => 'Absent'])->all());

                    $halfAbsent = count(Payroll::find()->where(['email' => $email])->andWhere("date >= :start_date and date <= :end_date", [
                        'start_date' => $startDate,
                        'end_date' => $endDate
                    ])->andWhere(['status' => '0.5 day Present, 0.5 day Absent'])->all());
                    
                    $absentDays = $fullAbsent +$halfAbsent/2;

                    return json_encode([
                        'status'=> false,
                        'absentDays' => $absentDays
                    ]);
                }
            }

            private function checkDateRangeOverlap($employeeID, $startDate, $endDate, $slipID = null) {
                if (!is_null($slipID)) {
                    $slips = Slip::find()->where(['employee_id' => $employeeID])->andWhere(['not in', 'id', $slipID])->all();
                } else {
                    $slips = Slip::find()->where(['employee_id' => $employeeID])->all();
                }

                $rangeOverlap = false;
                foreach($slips as $slip) {
                    $rangeMin = new DateTime(date("Y-m-d", strtotime($slip->start_date)));
                    $rangeMax = new DateTime(date("Y-m-d", strtotime($slip->end_date)));

                    $sDate = new DateTime($startDate);
                    $eDate = new DateTime($endDate);

                    if (($sDate >= $rangeMin && $eDate <= $rangeMax) || ($eDate >= $rangeMin && $sDate <= $rangeMax)) {
                        $rangeOverlap = true;
                        break;
                    }
                }

                return $rangeOverlap;
            }

            protected function combineSectionsIntoSingleSlipItems(){
                if(isset($_POST)){
                    $_POST['SlipItem'] = [];
                    $slipItemSections = SlipItemSection::find()->all();
                    foreach($slipItemSections as $section){
                        if(isset($_POST["SlipItem-$section->id"])){
                            foreach($_POST["SlipItem-$section->id"] as $item){
                                $_POST['SlipItem'][] = $item;
                            }
                        }
        
                    }
                    /*       echo "<pre>";
                           var_dump($_POST);
                           echo "</pre>";
        
                           echo 'ddddd';
        
                           echo "<pre>";
                           var_dump(Yii::$app->request->post());
                           echo "</pre>";
        
                           die();*/
                }
            }
            public function actionGetTotalPayout() {
                if (isset($_POST['month']) && isset($_POST['year'])) {
                    $month = $_POST['month'];
                    $year = $_POST['year'];
                    $slips = Slip::find()->where(['payslip_month' => $month, 'payslip_year' => $year])->all();
                    $totalPayout = 0;
                    foreach($slips as $slip) {
                        //getting conversion rate of each currency
                        $conversionRate = DynamicCurrency::findOne(Employee::findOne($slip->employee_id)->currency_id)->conversion_rate_to_SAR;
                        $totalPayout += ($slip->final_salary * $conversionRate);
                    }

                    echo json_encode(['totalPayout' => number_format((float)$totalPayout, 2, '.', '')]);
                }
            }
        }
