<?php
/**
 * Created by PhpStorm.
 * User: rahulsinghmatharu
 * Date: 24/04/19
 * Time: 12:01 PM
 */

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\Controller;
use app\components\GlobalConstant;
use backend\models\Organisation;

class CustomBaseController extends Controller
{
    public function beforeAction($action)
    {
        $user = Yii::$app->user->identity;

        // Skip 2FA check if impersonating
        if (
            Yii::$app->session->get('isImpersonating', false) ||
            Yii::$app->session->has('user.idbeforeswitch') ||
            Yii::$app->session->has('user.oldId') ||
            Yii::$app->session->has('user.oldRole') ||
            Yii::$app->session->has('user.oldTwoId') ||
            Yii::$app->session->has('user.oldTwoRole')
        ) {
            return $this->redirectUser($action);
        }
        $backendUrl = Yii::getAlias('@backendUrl'); // Ensure this alias is set in your configuration
        // Check if the user needs to complete 2FA
        if ($user && !$user->check_auth_login) {
            Yii::$app->session->setFlash('warning', 'You need to complete the two-factor authentication.');
            if($user->auth_type=='google' && !$user->two_factor_auth_qr_token)
            return $this->redirect($backendUrl . '/default/generate-twofa');
            elseif($user->auth_type=='google')
            return $this->redirect($backendUrl . '/default/verify-twofa');
            else
            return $this->redirect($backendUrl . '/default/two-factor-auth');

        }

        // Additional logic to check if the organisation details are filled
        if (Yii::$app->controller->id != "organisation" && $action->id != 'create') {
            if (in_array($action->id, ['unimpersonate'])) {
                return parent::beforeAction($action);
            }

            $organisation = Organisation::find()->where(['user_id' => Yii::$app->user->id])->one();
            if ($user->hasRole(GlobalConstant::ROLE_ORGANISATION_ADMIN) && empty($organisation)) {
                Yii::$app->session->setFlash('warning', "Please fill out organisation details before updating other sections.");
                return $this->redirect(['organisation/create']);
            }
        }

        return parent::beforeAction($action);
    }

    public function redirectUser($action)
    {
        if (Yii::$app->controller->id != "organisation" && $action->id != 'create') {
            if (in_array($action->id, ['unimpersonate'])) {
                return parent::beforeAction($action);
            }
            $user = Yii::$app->user->identity;

            $organisation = Organisation::find()->where(['user_id' => Yii::$app->user->id])->one();
            if ($user->hasRole(GlobalConstant::ROLE_ORGANISATION_ADMIN) && empty($organisation)) {
                Yii::$app->session->setFlash('warning', "Please fill out organisation details before updating other sections.");
                return $this->redirect(['organisation/create']);
            }
        }
        return parent::beforeAction($action);
    }
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    $user = Yii::$app->user->identity;
                    if ($user && !$user->check_auth_login) {
                        return Yii::$app->response->redirect(['/default/two-factor-auth']);
                    }
                    throw new ForbiddenHttpException('You are not allowed to access this page.');
                },
            ],
        ];
    }
}
