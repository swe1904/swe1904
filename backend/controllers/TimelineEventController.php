<?php

namespace backend\controllers;

use backend\models\Organisation;
use Yii;
use yii\web\Controller;
use yii\helpers\Url;

/**
 * Application timeline controller
 */
class TimelineEventController extends Controller
{
    public $layout = 'common';
    /**
     * Lists all TimelineEvent models.
     * @return mixed
     */
    public function actionIndex()
    { if(!Yii::$app->user->isGuest){
        $twoFactorAuth = Yii::$app->user->identity;
        if (
            Yii::$app->session->get('isImpersonating', false) ||
            Yii::$app->session->has('user.idbeforeswitch') ||
            Yii::$app->session->has('user.oldId') ||
            Yii::$app->session->has('user.oldRole') ||
            Yii::$app->session->has('user.oldTwoId') ||
            Yii::$app->session->has('user.oldTwoRole')
        ) {
            $backendUrl = Yii::getAlias('@backendUrl'); // Ensure this alias is set in your configuration
            // Check if the user needs to complete 2FA
            if ($twoFactorAuth && !$twoFactorAuth->check_auth_login) {
                Yii::$app->session->setFlash('warning', 'You need to complete the two-factor authentication.');
                if($twoFactorAuth->auth_type=='google' && !$twoFactorAuth->two_factor_auth_qr_token)
                return $this->redirect($backendUrl . '/default/generate-twofa');
                elseif($twoFactorAuth->auth_type=='google')
                return $this->redirect($backendUrl . '/default/verify-twofa');
                else
                return $this->redirect($backendUrl . '/default/two-factor-auth');
    
            }
        }
       
        if(Yii::$app->user->can('organisation-admin')&& !Yii::$app->user->can('administrator')){
            $organisationModels =   Organisation::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
            if(!empty($organisationModels)){
                return $this->redirect(['organisation/update','id'=>$organisationModels->id]);
            }else{
                return $this->redirect(['organisation/create']);
            }
        }
       // else  return $this->redirect(['/sign-in/profile']);
    else  return $this->redirect(['/cases/index']);


    }
    else{
        return $this->redirect('@frontendUrl');
    }

    }
}
