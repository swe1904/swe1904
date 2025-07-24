<?php

namespace backend\controllers;

use app\components\GlobalConstant;
use backend\models\Client;
use backend\models\InviteApplicant;
use backend\modules\polling\models\base\PollingQuiz;
use backend\modules\polling\models\EmailTemplate;
use common\models\User;
use Yii;

class InviteApplicantController extends CustomBaseController
{
    public function actionIndex()
    { $inviteApplicant = new InviteApplicant();
        if (Yii::$app->request->post()&& $inviteApplicant->load(Yii::$app->request->post())) {
           // $emailTemplate=Yii::$app->request->post('EmailTemplate');
            $applicantDetails=Yii::$app->request->post('InviteApplicant');
            $recepients=explode(",",$applicantDetails['to_email']);

            $client_name='';
            $client_id='';

            if(Yii::$app->user->can('organisation-admin')||Yii::$app->user->can(GlobalConstant::ROLE_CASE_WORKER)){
                /* selected client from dropdown */
                $client_id=$inviteApplicant->client_id;
            }else {
                /*-search current client in users -*/
                $user = User::find()->where(['id' => Yii::$app->user->id])->one();
                $client_id=$user->client_id;
            }
            $client = Client::findOne($client_id);
                if (!empty($client)) {
                    $client_name = $client->client_name;
                    $client_id = $client->id;
                }

            $emailTemplateModel = EmailTemplate::find()->where(['id' => $inviteApplicant->template_id])->one();
            $emailTemplateModel->body = EmailTemplate::replaceString("%ClientName%", $client_name, $emailTemplateModel->body);
            $emailTemplateModel->body = EmailTemplate::replaceString("%QuestionnaireId%",$inviteApplicant->polling_id, $emailTemplateModel->body);
            $emailTemplateModel->body = EmailTemplate::replaceString("%ClientId%", $client_id,$emailTemplateModel->body);

            // $inviteApplicant->from_email = GlobalConstant::REPLY_FROM_EMAIL;
            //  $inviteApplicant->from_name =  GlobalConstant::REPLY_FROM_NAME;

            $inviteApplicant->template_id;
            $inviteApplicant->created_at;
            $inviteApplicant->to_email; // csv of recepients
            $inviteApplicant->polling_id;
            $inviteApplicant->client_id=$client_id;
            $inviteApplicant->subject= $emailTemplateModel->subject;
            // $inviteApplicant->body=$emailTemplateModel->body;
            // to send email
            if($inviteApplicant->validate()){
                foreach ($recepients as $applicantEmail) {
                    $emailStatusResponse= $this->sendEmail($applicantEmail,$inviteApplicant->subject,$emailTemplateModel->body);
                }
                    if(!$inviteApplicant->save()){
                        echo '<pre>';
                        print_r($inviteApplicant->getErrors());
                        echo '<pre>';

                    }
                    Yii::$app->session->setFlash('success', "Mail Sent!");
                    return $this->redirect('index');

            }else {
                return $this->render('index', ['inviteApplicant' => $inviteApplicant]);
            }

        }

        return $this->render('index',['inviteApplicant'=> $inviteApplicant]);
    }

    public function sendEmail($to_email,$subject,$message)
    {
                return Yii::$app->mailer->compose()
                    ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
                    ->setTo($to_email)
                    ->setSubject($subject)
                    ->setHtmlBody($message)
                    ->send();
            }


}
