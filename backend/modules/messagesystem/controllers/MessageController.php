<?php

namespace backend\modules\messagesystem\controllers;

use app\components\GlobalConstant;
use backend\modules\messagesystem\models\MessageFileUpload;
use backend\modules\messagesystem\models\MessageInbox;
use backend\modules\messagesystem\models\MessageReadStatus;
use backend\modules\messagesystem\models\search\MessageInboxSearch;
use backend\modules\messagesystem\models\search\MessageOutboxSearch;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use Yii;
use yii\web\Response;
use backend\models\Cases;   //Nemanja
use backend\models\Applicant;   //Nemanja
use backend\models\Client;   //Nemanja
use backend\models\CaseType;   //Nemanja
use common\models\User;   //Nemanja
use backend\components\Helper;

class MessageController extends Controller
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
    public function beforeAction($action) {
        $this->enableCsrfValidation = false; return parent::beforeAction($action);
    }
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionInbox($id=null){
        $this->layout='@backend/modules/messagesystem/views/layouts/_final_layout';
        if(empty($id)){
            return $this->showAllInbox();
        }else{
            return $this->showOneInbox($id);
        }

    }
    private function showAllInbox(){
        $searchModel = new MessageInboxSearch();
        $dataProvider = $searchModel->search2(Yii::$app->request->queryParams);

        return $this->render('inbox_new', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    private function showOneInbox(){
        $id=Yii::$app->request->queryParams['id'];
        $searchModel = new MessageInboxSearch();


        $searchModel->thread_id=$id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        try{
            $model=MessageInbox::find()->with('messageFileUploads')->where('thread_id=:thread_id',[':thread_id'=>$id])->one();
            if($model->receiver_id==Yii::$app->user->id){
                $sender_id=$model->receiver_id;
                $receiver_id=$model->sender_id;
            }else{
                $receiver_id=$model->receiver_id;
                $sender_id=$model->sender_id;
            }
            $model->receiver_id=$receiver_id;
            $model->sender_id=$sender_id;

            // mark all messages read.
            // sender side
            $this->setMessageReadStatusEach(MessageInbox::READ,$id,Yii::$app->user->id);

            return $this->render('inbox_detail', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model'=>$model,
            ]);
        }catch(\Exception $exception){
            return $this->render('_error');
        }
    }

    public function actionSendMessage()
    {
        $model = new MessageInbox();
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->assetManager->bundles = [
                'yii\web\JqueryAsset' => false
            ];
            $model->setScenario("compose");
            $model->load($_POST);
            if(!$model->validate()){
                $html = $this->renderAjax('compose_message', [
                    'model' => $model
                ]);
                return [$html];
            }else{
                if($model->save()){

                    // save Message read status
                    $this->setMessageReadStatus($model);
                    $attachementModel = MessageFileUpload::find()->where(['message_id'=>$model->id])->one();
                    if(!empty($attachementModel)){
                        // \Yii::$app->mailer->compose()
                        //     ->setFrom(GlobalConstant::REPLY_FROM_EMAIL)
                        //     ->setTo($model->receiver->email)
                        //     ->setSubject($model->subject)
                        //     ->setHtmlBody($model->message)
                        //     ->attach($attachementModel->attachment, ['fileName'=>$attachementModel->name])
                        //     ->send();

                        $fromEmail = $model->sender->organisation->user->email;
                        $toEmail = $model->receiver->email;
                        $subject = $model->subject;
                        $htmlBody = $model->message;
                    
                        Helper::sendEmailViaSes($fromEmail, $toEmail, null, $subject, $htmlBody, null, $attachementModel->attachment, $attachementModel->name);


                    //                    $this->sendEmailOnMessage($model);
                    }

                    return [1];
                }
            }
        }else{
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                // save Message read status
                $this->setMessageReadStatus($model);

                //$this->sendEmailOnMessage($model);
                return $this->redirect(['message/inbox/'.$model->thread_id]);
            }
        }

    }
    
    /**
    * @author Nemanja
    * @since 2021-01-12
    * @return send message
    */
    public function actionSendMessageCasestep()
    {
        $model = new MessageInbox();
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->assetManager->bundles = [
                'yii\web\JqueryAsset' => false
            ];
            $model->setScenario("compose");
            $model->load($_POST);
            if(!$model->validate()){
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'validate' => $model,
                    'code' => 500,
                ];
            }else{
                if($model->save()){

                    // save Message read status
                    $this->setMessageReadStatus($model);
                    $attachementModel = MessageFileUpload::find()->where(['message_id'=>$model->id])->one();
                    if(!empty($attachementModel)){
                        // \Yii::$app->mailer->compose()
                        //     ->setFrom(GlobalConstant::REPLY_FROM_EMAIL)
                        //     ->setTo($model->receiver->email)
                        //     ->setSubject($model->subject)
                        //     ->setHtmlBody($model->message)
                        //     ->attach($attachementModel->attachment, ['fileName'=>$attachementModel->name])
                        //     ->send();

                            $fromEmail = $model->sender->organisation->user->email;
                            $toEmail = $model->receiver->email;
                            $subject = $model->subject;
                            $htmlBody = $model->message;
                        
                            Helper::sendEmailViaSes($fromEmail, $toEmail, null, $subject, $htmlBody, null, $attachementModel->attachment, $attachementModel->name);
                    //  $this->sendEmailOnMessage($model);
                    }

                    return $this->redirect(['message/inbox']);
                }
            }
        }else{
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                // save Message read status
                $this->setMessageReadStatus($model);

                //$this->sendEmailOnMessage($model);
                return $this->redirect(['message/inbox/'.$model->thread_id]);
            }
        }
    }
    
    private function sendEmailOnMessage(MessageInbox $messageInbox){
        if(!empty($messageInbox->roomListing)){
            // normal roomListing base message
            $email= Yii::$app->email->setUser($messageInbox->messageReadStatusReceiver->receiver)
                ->notifyUserOnMessageSend($messageInbox);
        }else{
            // Admin-User conversation
            // normal roomListing base message
            $email= Yii::$app->email->setUser($messageInbox->messageReadStatusReceiver->receiver)
                ->notifyAdminUserOnMessageSend($messageInbox);
        }

    }
    public function actionContactUser(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = new MessageInbox();

        $roomListingId=$_POST['roomListingId'];
        $receiverId=$_POST['receiverId'];
        $model->room_listing_id=$roomListingId;
        $model->sender_id=Yii::$app->user->id;
        $model->receiver_id=$receiverId;
        $model->thread_id=$model->returnThreadId();
        return ['status'=>1,'html'=>$this->renderPartial('contact_user',['model'=>$model])];
    }

    public function actionComposeMessage(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        Yii::$app->assetManager->bundles = [

            'yii\bootstrap\BootstrapPluginAsset' => false,

            'yii\bootstrap\BootstrapAsset' => false,

            'yii\web\JqueryAsset' => false

        ];

        $model = new MessageInbox();
        $model->sender_id=Yii::$app->user->id;
        $model->thread_id=$model->returnThreadId();
        return ['status'=>1,'html'=>$this->renderAjax('compose_message',['model'=>$model])];
    }
    
    /**
    * @author Nemanja
    * @since 2021-01-12
    * @return modal data
    */
    public function actionComposeMessageCasestep(){
        $caseID = $_POST['caseID'];

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        Yii::$app->assetManager->bundles = [

            'yii\bootstrap\BootstrapPluginAsset' => false,

            'yii\bootstrap\BootstrapAsset' => false,

            'yii\web\JqueryAsset' => false

        ];

        if (@$caseID) {
            $case = Cases::findOne(['id' => $caseID]);
            $applicant_id = $case->applicant_id;

            $applicant = Applicant::findOne(['id' => $applicant_id]);
            $applicant_email = $applicant->email;
            $client_id = $applicant->client_id;

            $case = Cases::findOne(['applicant_id' => $applicant_id]);
            $case_worker_id = $case->assigned_to;
            $case_manager_id = $case->case_manager_id;
            // $client_hr_id = $case->raised_by_id;

            $case_worker =  User::findOne(['id' =>  $case_worker_id]);
            $case_worker_email = $case_worker->email;

            $case_manager =  User::findOne(['id' =>  $case_manager_id]);
            $case_manager_email = $case_manager->email;

            $client = Client::findOne(['id' => $client_id]);
            $client_email = $client->email;

            $client_hr = User::findOne($case->raised_by_id);
            $client_hr_email = $client_hr->email;

            $user_id = $client->user_id;
            $user = User::findOne(['id' => $user_id]);
            $user_email = $user->email;

            $receiver = array(
                [
                    'id' => $applicant_id,
                    'email' => $applicant_email
                ], 
                [
                    'id' => $client_id,
                    'email' => $client_email
                ], 
                [
                    'id' => $case_worker_id,
                    'email' => $case_worker_email
                ], 
                [
                    'id' => $case_manager_id,
                    'email' => $case_manager_email
                ], 
                [
                    'id' => $client_hr->id,
                    'email' => $client_hr_email
                ], 
                // [
                //     'id' => $user_id,
                //     'email' => $user_email
                // ]
            );
            $filteredReceiver = array_filter($receiver, function ($entry) {
                return isset($entry['email']) && !empty($entry['email']);
            });

            $case_number = $case->case_number;
            $applicant_name = $case->applicant_first_name . " / " . $case->applicant_last_name;
            $last_status_update = $case->last_status_update;

            $caseInfor = [];
            $caseInfor['case_number'] = $case_number;
            $caseInfor['applicant_name'] = $applicant_name;
            $caseInfor['last_status_update'] = $last_status_update;
            $caseInfor['all'] = $case_number . " // " . $applicant_name . " // " . $last_status_update;
        }else{
            $filteredReceiver = [];
        }

        $model = new MessageInbox();
        $model->sender_id=Yii::$app->user->id;
        $model->thread_id=$model->returnThreadId();
        return ['status'=>1,'html'=>$this->renderAjax('compose_message_casestep', ['model' => $model, 'receiver' => $filteredReceiver, 'caseInfor' => $caseInfor])];
    }
    
    private function insertMessage(){

    }
    private function setMessageReadStatus(MessageInbox $messageInbox){

        // sender side
        $this->setMessageReadStatusEach(MessageInbox::READ,$messageInbox->thread_id,$messageInbox->sender_id);

        // receiver side
        $this->setMessageReadStatusEach(MessageInbox::UNREAD,$messageInbox->thread_id,$messageInbox->receiver_id);


    }
    private function setMessageReadStatusEach($status,$thread_id,$receiver_id ){
        // sender side
        $messageReadStatusModelSender=MessageReadStatus::find()->where('thread_id=:thread_id and receiver_id=:receiver_id',[':thread_id'=>$thread_id,':receiver_id'=>$receiver_id])->one();

        if(empty($messageReadStatusModelSender)){

            $messageReadStatusModelSender=new MessageReadStatus();
        }
        $messageReadStatusModelSender->status=$status;
        $messageReadStatusModelSender->thread_id=$thread_id;
        $messageReadStatusModelSender->receiver_id=$receiver_id;
        $messageReadStatusModelSender->save();
    }
    public function actionOutbox($id=null){
        if(empty($id)){
          return $this->showAllOutbox();
        }else{
            return $this->showOneOutbox($id);
        }


    }
    private function showAllOutbox(){
        $searchModel = new MessageOutboxSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('outbox', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    private function showOneOutbox($id){
        return $this->render('outbox_detail', [
        ]);
    }
    public function actionChangeReadStatus(){
        $receiverId=Yii::$app->user->id;
        $dataArray = json_decode($_POST['data']);
        $status=$_POST['status'];
        foreach ($dataArray as $data){
           $model=MessageReadStatus::find()->where('thread_id=:thread_id and receiver_id=:receiver_id',[':thread_id'=>$data,':receiver_id'=>$receiverId])->one();
           if(!empty($model)){
               $model->status=$status;
               $model->save();
           }
        }

    }
    public function actionDeleteThread(){
        $receiverId=Yii::$app->user->id;
        $dataArray = json_decode($_POST['data']);
        foreach ($dataArray as $data){
            $model=MessageReadStatus::find()->where('thread_id=:thread_id and receiver_id=:receiver_id',[':thread_id'=>$data,':receiver_id'=>$receiverId])->one();
            if(!empty($model)){
                $model->delete=MessageInbox::DELETE;
                $model->save();
            }
        }

    }
}
