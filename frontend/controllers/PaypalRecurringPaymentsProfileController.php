<?php

namespace frontend\controllers;
// require(__DIR__ . '/../../vendor/paypal/merchant-sdk-php/samples/Configuration.php');
use frontend\models\Plan;
use PayPal\EBLBaseComponents\ActivationDetailsType;
use Yii;
use frontend\models\PaypalRecurringPaymentsProfile;
use frontend\models\PaypalRecurringPaymentsProfileSearch;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use PayPal\CoreComponentTypes\BasicAmountType;
use PayPal\EBLBaseComponents\BillingAgreementDetailsType;
use PayPal\EBLBaseComponents\BillingPeriodDetailsType;
use PayPal\EBLBaseComponents\PaymentDetailsType;
use PayPal\EBLBaseComponents\RecurringPaymentsProfileDetailsType;
use PayPal\EBLBaseComponents\SetExpressCheckoutRequestDetailsType;
use PayPal\PayPalAPI\SetExpressCheckoutReq;
use PayPal\PayPalAPI\SetExpressCheckoutRequestType;
use PayPal\Service\PayPalAPIInterfaceServiceService;
use yii\helpers\Url;
use PayPal\EBLBaseComponents\CreateRecurringPaymentsProfileRequestDetailsType;
use PayPal\EBLBaseComponents\ScheduleDetailsType;
use PayPal\PayPalAPI\CreateRecurringPaymentsProfileReq;
use PayPal\PayPalAPI\CreateRecurringPaymentsProfileRequestType;

use PayPal\EBLBaseComponents\DoExpressCheckoutPaymentRequestDetailsType;
use PayPal\PayPalAPI\DoExpressCheckoutPaymentReq;
use PayPal\PayPalAPI\DoExpressCheckoutPaymentRequestType;
use PayPal\PayPalAPI\GetExpressCheckoutDetailsReq;
use PayPal\PayPalAPI\GetExpressCheckoutDetailsRequestType;

/**
 * PaypalRecurringPaymentsProfileController implements the CRUD actions for PaypalRecurringPaymentsProfile model.
 */
class PaypalRecurringPaymentsProfileController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all PaypalRecurringPaymentsProfile models.
     * @return mixed
     */


    /*monthly subscribe*/
    public function actionCreateRecurringProfile(){
        ini_set('max_execution_time', 5 * 60); // 5 minutes
        /*if user is guest*/
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('alert', [
                'options' => ['class' => 'alert-warning'],
                'body' => Yii::t('backend', 'For activate plan, please login first..!')
            ]);
            return $this->redirect(['user/sign-in/login']);
        }
        /*if user is not guest*/
        if (!Yii::$app->user->isGuest) {
            if(isset($_POST['amt'])){
                $amount = $_POST['amt'];
                /*if free subscription*/
                if($_POST['amt']=='0.00'){
                    $paypalRecurringProfileModel = new PaypalRecurringPaymentsProfile();
                    $paypalRecurringProfileModel->plan_id = Plan::FREE_PLAN;
                    $paypalRecurringProfileModel->user_id = Yii::$app->user->identity->id;
                    $paypalRecurringProfileModel->timestamp = date("Y-m-d H:i:s");
                    $paypalRecurringProfileModel->amount = $amount;
                    $paypalRecurringProfileModel->initial_amount = $amount;
                    $paypalRecurringProfileModel->billing_start_date = date("Y-m-d");
                    $paypalRecurringProfileModel->billing_end_date = date("Y-m-d", strtotime("+10 day"));
                    $paypalRecurringProfileModel->is_cancelled = Plan::ACTIVE_PLAN;
                    if($paypalRecurringProfileModel->save()){
                        Yii::$app->session->setFlash('alert', [
                            'options' => ['class' => 'alert-success'],
                            'body' => Yii::t('backend', 'Your subscription has been activated..!')
                        ]);
                        return $this->redirect(['site/index']);
                    }
                }
                /* if user select monthly / yearly */
                if($amount=='300.00' || $amount=='200.00') {
                        $client_secret = md5(time());
                        $paypalRecurringProfileModel = new PaypalRecurringPaymentsProfile();
                    if($amount=='300.00'){
                        $paypalRecurringProfileModel->plan_id = Plan::MONTHLY_PLAN;
                    }
                    if($amount=='200.00'){
                        $paypalRecurringProfileModel->plan_id = Plan::YEARLY_PLAN;
                    }
                        $paypalRecurringProfileModel->user_id = Yii::$app->user->identity->id;
                        $paypalRecurringProfileModel->timestamp = date("Y-m-d H:i:s");
                        $paypalRecurringProfileModel->amount = $amount;
                        $paypalRecurringProfileModel->initial_amount = $amount;
                        $paypalRecurringProfileModel->billing_start_date = date("Y-m-d");
                    if($amount=='300.00'){
                        $paypalRecurringProfileModel->billing_end_date = date("Y-m-d", strtotime("+1 months"));
                    }
                    if($amount=='200.00'){
                        $paypalRecurringProfileModel->billing_end_date = date("Y-m-d", strtotime("+1 year"));
                    }
                        $paypalRecurringProfileModel->is_cancelled = Plan::INACTIVE_PLAN;
                        $paypalRecurringProfileModel->client_secret = $client_secret;
                        if($paypalRecurringProfileModel->save()){
                            $returnUrl = Url::to(['recurring-payment-ec', 'client-secret'=>$paypalRecurringProfileModel->client_secret, 'success'=>'true'],true);
                            $cancelUrl = Url::to(['recurring-payment-ec', 'client-secret'=>$paypalRecurringProfileModel->client_secret, 'success'=>'false'],true);
                            $config = array (
                                'mode' => 'sandbox' ,
                                'acct1.UserName' => '',
                                'acct1.Password' => '',
                                'acct1.Signature' => ''
                            );
                            $paypalService = new PayPalAPIInterfaceServiceService($config);
                            $paymentDetails= new PaymentDetailsType();

                            $orderTotal = new BasicAmountType();
                            $orderTotal->currencyID = 'USD';
                            $orderTotal->value = $paypalRecurringProfileModel->amount;


                            $paymentDetails->OrderTotal = $orderTotal;
                            $paymentDetails->PaymentAction = 'Sale';


                            $setECReqDetails = new SetExpressCheckoutRequestDetailsType();
                            $setECReqDetails->PaymentDetails[0] = $paymentDetails;
                            $setECReqDetails->CancelURL = $cancelUrl;
                            $setECReqDetails->ReturnURL = $returnUrl;

                            $billingAgreementDetails = new BillingAgreementDetailsType('RecurringPayments');
                            $billingAgreementDetails->BillingAgreementDescription = 'recurringbilling';
                            $setECReqDetails->BillingAgreementDetails = array($billingAgreementDetails);

                            $setECReqType = new SetExpressCheckoutRequestType();
                            $setECReqType->Version = '104.0';
                            $setECReqType->SetExpressCheckoutRequestDetails = $setECReqDetails;

                            $setECReq = new SetExpressCheckoutReq();
                            $setECReq->SetExpressCheckoutRequest = $setECReqType;


                            $setECResponse = $paypalService->SetExpressCheckout($setECReq);

                            if($setECResponse->Ack=='Success')
                            {
                                $this->redirect('https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$setECResponse->Token.'');
                            }
                        }
                }else{
                    Yii::$app->session->setFlash('alert', [
                        'options' => ['class' => 'alert-danger'],
                        'body' => Yii::t('backend', 'Sorry something happen wrong.')
                    ]);
                    return $this->redirect(['site/pricing']);
                }
            }
        }
    }

    /* Monthly Recurring payments activated result*/
    public function actionRecurringPaymentEc(){
        $recurringPaymentsModel = PaypalRecurringPaymentsProfile::find()
            ->where(['user_id'=>Yii::$app->user->identity->id])
            ->andWhere(['client_secret'=>$_GET['client-secret']])
            ->andWhere(['is_cancelled'=>0])
            ->one();
        if(!isset($recurringPaymentsModel)){
            Yii::$app->session->setFlash('alert', [
                'options' => ['class' => 'alert-danger'],
                'body' => Yii::t('backend', 'Sorry your request was not found.')
            ]);
            return $this->redirect(['site/pricing']);
        }
        /*do express checkout*/
        $token = urlencode($_REQUEST['token']);
        $profileDetails = new RecurringPaymentsProfileDetailsType();
        $profileDetails->BillingStartDate = date("Y-m-d H:i:s");

        $paymentBillingPeriod = new BillingPeriodDetailsType();
        if($recurringPaymentsModel->plan_id == Plan::MONTHLY_PLAN){
            $paymentBillingPeriod->BillingFrequency = 1;
            $paymentBillingPeriod->BillingPeriod = "Month";
        }
        if($recurringPaymentsModel->plan_id == Plan::YEARLY_PLAN) {
            $paymentBillingPeriod->BillingFrequency = 12;
            $paymentBillingPeriod->BillingPeriod = "Month";
        }
        $paymentBillingPeriod->Amount = new BasicAmountType("USD",   $recurringPaymentsModel->amount);

        /*$activationDetails = new ActivationDetailsType();
        $activationDetails->InitialAmount = new BasicAmountType("USD", $recurringPaymentsModel->amount);*/

        $scheduleDetails = new ScheduleDetailsType();
        $scheduleDetails->Description = "recurringbilling";
        $scheduleDetails->PaymentPeriod = $paymentBillingPeriod;
        //$scheduleDetails->ActivationDetails = $activationDetails;

        $createRPProfileRequestDetails = new CreateRecurringPaymentsProfileRequestDetailsType();
        $createRPProfileRequestDetails->Token = $token;

        $createRPProfileRequestDetails->ScheduleDetails = $scheduleDetails;
        $createRPProfileRequestDetails->RecurringPaymentsProfileDetails = $profileDetails;

        $createRPProfileRequest = new CreateRecurringPaymentsProfileRequestType();
        $createRPProfileRequest->CreateRecurringPaymentsProfileRequestDetails = $createRPProfileRequestDetails;

        $createRPProfileReq = new CreateRecurringPaymentsProfileReq();
        $createRPProfileReq->CreateRecurringPaymentsProfileRequest = $createRPProfileRequest;

        $paypalService = new PayPalAPIInterfaceServiceService(\Configuration::getAcctAndConfig());
        $createRPProfileResponse = $paypalService->CreateRecurringPaymentsProfile($createRPProfileReq);
        /*if acknowledge has been success */
        if($createRPProfileResponse->Ack=='Success'){
            $recurringPaymentsModel->profileId = $createRPProfileResponse->CreateRecurringPaymentsProfileResponseDetails->ProfileID;
            $recurringPaymentsModel->profileStatus = $createRPProfileResponse->CreateRecurringPaymentsProfileResponseDetails->ProfileStatus;
            $recurringPaymentsModel->ack = $createRPProfileResponse->Ack;
            $recurringPaymentsModel->is_cancelled = Plan::ACTIVE_PLAN;
            $recurringPaymentsModel->timestamp = $createRPProfileResponse->Timestamp;
            $recurringPaymentsModel->token = $token;
            $recurringPaymentsModel->payerId = $_GET['PayerID'];
            //$recurringPaymentsModel->transaction_id = $DoECResponse->DoExpressCheckoutPaymentResponseDetails->PaymentInfo[0]->TransactionID;
            $recurringPaymentsModel->save();
        }
        return $this->render('createRecurringProfile', ['createRPProfileResponse'=>$createRPProfileResponse]);
    }

    public function actionIndex()
    {
        $searchModel = new PaypalRecurringPaymentsProfileSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PaypalRecurringPaymentsProfile model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PaypalRecurringPaymentsProfile model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PaypalRecurringPaymentsProfile();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PaypalRecurringPaymentsProfile model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PaypalRecurringPaymentsProfile model.
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
     * Finds the PaypalRecurringPaymentsProfile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PaypalRecurringPaymentsProfile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PaypalRecurringPaymentsProfile::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionPaypalTest()
    {
        $returnUrl = Url::to(['recurring-payment-ec', 'success'=>'true'],true);
        $cancelUrl = Url::to(['recurring-payment-ec',  'success'=>'false'],true);
        $config = array (
            'mode' => 'live' ,
            'acct1.UserName' => '',
            'acct1.Password' => '',
            'acct1.Signature' => ''
        );

        $paypalService = new PayPalAPIInterfaceServiceService($config);
        $paymentDetails= new PaymentDetailsType();

        $orderTotal = new BasicAmountType();
        $orderTotal->currencyID = 'USD';
        $orderTotal->value = 3;

        $paymentDetails->OrderTotal = $orderTotal;
        $paymentDetails->PaymentAction = 'Sale';

        $setECReqDetails = new SetExpressCheckoutRequestDetailsType();
        $setECReqDetails->PaymentDetails[0] = $paymentDetails;
        $setECReqDetails->CancelURL = $cancelUrl;
        $setECReqDetails->ReturnURL = $returnUrl;

        $billingAgreementDetails = new BillingAgreementDetailsType('RecurringPayments');
        $billingAgreementDetails->BillingAgreementDescription = 'recurringbilling';
        $setECReqDetails->BillingAgreementDetails = array($billingAgreementDetails);

        $setECReqType = new SetExpressCheckoutRequestType();
        $setECReqType->Version = '104.0';
        $setECReqType->SetExpressCheckoutRequestDetails = $setECReqDetails;

        $setECReq = new SetExpressCheckoutReq();
        $setECReq->SetExpressCheckoutRequest = $setECReqType;

        $setECResponse = $paypalService->SetExpressCheckout($setECReq);
        if($setECResponse->Ack=='Success')
        {
            $this->redirect('https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$setECResponse->Token.'');
        }
    }

/*    public function actionRecTest(){
        $profileDetails = new RecurringPaymentsProfileDetailsType();
        $profileDetails->BillingStartDate = "2016-05-13T00:00:00:000Z";

        $paymentBillingPeriod = new BillingPeriodDetailsType();
        $paymentBillingPeriod->BillingFrequency = 10;
        $paymentBillingPeriod->BillingPeriod = "Day";
        $paymentBillingPeriod->Amount = new BasicAmountType("USD", "3.0");

        $scheduleDetails = new ScheduleDetailsType();
        $scheduleDetails->Description = "recurringbilling";
        $scheduleDetails->PaymentPeriod = $paymentBillingPeriod;

        $createRPProfileRequestDetails = new CreateRecurringPaymentsProfileRequestDetailsType();
        $createRPProfileRequestDetails->Token = "EC-37E24652MG3625335";

        $createRPProfileRequestDetails->ScheduleDetails = $scheduleDetails;
        $createRPProfileRequestDetails->RecurringPaymentsProfileDetails = $profileDetails;

        $createRPProfileRequest = new CreateRecurringPaymentsProfileRequestType();
        $createRPProfileRequest->CreateRecurringPaymentsProfileRequestDetails = $createRPProfileRequestDetails;

        $createRPProfileReq = new CreateRecurringPaymentsProfileReq();
        $createRPProfileReq->CreateRecurringPaymentsProfileRequest = $createRPProfileRequest;

        $config = array (
            'mode' => 'sandbox' ,
            'acct1.UserName' => '',
            'acct1.Password' => '',
            'acct1.Signature' => ''
        );

        $paypalService = new PayPalAPIInterfaceServiceService($config);
        $createRPProfileResponse = $paypalService->CreateRecurringPaymentsProfile($createRPProfileReq);
    }*/
}
