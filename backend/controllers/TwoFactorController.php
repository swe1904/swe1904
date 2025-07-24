<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use app\models\EnableTwoFactorForm;
use app\models\VerifyTwoFactorForm;
use backend\models\User;

class TwoFactorController extends Controller
{
    public function actionEnable()
    {
        $user = Yii::$app->user->identity;
        $googleAuthenticator = new GoogleAuthenticator();

        if (empty($user->two_factor_auth_secret)) {
            $user->two_factor_auth_secret = $googleAuthenticator->createSecret();
            $user->save();
        }

        $qrCodeUrl = $googleAuthenticator->getQRCodeGoogleUrl($user->username, $user->two_factor_auth_secret, 'N&S');
        $model = new EnableTwoFactorForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($googleAuthenticator->verifyCode($user->two_factor_auth_secret, $model->code)) {
                $user->is_two_factor_enabled = 1;
                $user->save();
                Yii::$app->session->setFlash('success', 'Two-factor authentication enabled successfully.');
                return $this->redirect(['site/index']);
            } else {
                Yii::$app->session->setFlash('error', 'Invalid verification code.');
            }
        }

        return $this->render('enable', [
            'model' => $model,
            'qrCodeUrl' => $qrCodeUrl,
        ]);
    }

    public function actionVerify()
    {
        $user = Yii::$app->user->identity;
        $googleAuthenticator = new GoogleAuthenticator();
        $model = new VerifyTwoFactorForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($googleAuthenticator->verifyCode($user->two_factor_auth_secret, $model->code)) {
                Yii::$app->session->setFlash('success', 'Verification successful.');
                return $this->redirect(['site/index']);
            } else {
                Yii::$app->session->setFlash('error', 'Invalid verification code.');
            }
        }

        return $this->render('verify', [
            'model' => $model,
        ]);
    }
}
