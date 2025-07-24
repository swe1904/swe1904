<?php

namespace backend\controllers;

use app\components\GlobalConstant;
use backend\components\Helper;
use common\components\constant\GlobalConstants;
use Yii;
use app\models\EnableTwoFactorForm;
use app\models\VerifyTwoFactorForm;
use common\models\User;
use yii\web\Controller;
use frontend\modules\user\models\LoginForm;
use frontend\modules\user\models\PasswordResetRequestForm;
use frontend\modules\user\models\ResetPasswordForm;
use frontend\modules\user\models\SignupForm;
class DefaultController extends Controller
{
    public  $layouts =  "@backend/views/layouts/base";

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $model = \Yii::$app->user->identity;

        $googleAutheticator = new GoogleAuthenticator();
        if ($model->enable_two_factor_auth == true && (isset($model->two_factor_auth_google_token) && !empty($model->two_factor_auth_google_tocken))) {
            $secret =  $model->two_factor_auth_google_token;
        } else {
            $secret = $googleAutheticator->createSecret();
            $model->two_factor_auth_google_token = $secret;
            $model->check_auth_login = true;
            if ($model->save()) {
                Yii::$app->ssoHelper->updateGoogleAuthGG($model);
                //Yii::$app->ssoHelper->updateGoogleAuthLC($model);
            }
        }
        $qrCodeUrl = $googleAutheticator->getQRCodeGoogleUrl(GlobalConstants::GOOGLE_APP_AUTH_NAME, $secret);
        $oneTimeCode = $googleAutheticator->getCode($secret);
        return $this->render('enable', [
            'model' => $model,
            'secret' => $secret,
            'qrCodeUrl' => $qrCodeUrl,
            'oneTimeCode' => $oneTimeCode
        ]);
    }

    public function actionSaveAuth()
    {
        $model = \Yii::$app->user->identity;
        //        print_r($_POST);
        $model->enable_two_factor_auth = $_POST['enable'];
        $model->check_auth_login = true;
        if ($model->save()) {
            // Yii::$app->ssoHelper->updateGoogleAuthGG($model);
            //Yii::$app->ssoHelper->updateGoogleAuthLC($model);
        }
    }

    public function actionRefreshQr()
    {
        if (isset($_POST['refresh'])) {
            $model = \Yii::$app->user->identity;
            $googleAuthenticator = new GoogleAuthenticator();

            $secret = $googleAuthenticator->createSecret();
            $model->two_factor_auth_google_token = $secret;
            $model->check_auth_login = true;
            if ($model->save()) {
                Yii::$app->ssoHelper->updateGoogleAuthGG($model);
                //Yii::$app->ssoHelper->updateGoogleAuthLC($model);
            }
        }
    }
    public function actionTwoFactorAuth()
    {
        $this->layout = $this->layouts;
        $wrongOtp = '';
        $model =  \Yii::$app->user->identity;
        if ($model->check_auth_login == false) {
            if (isset($_POST['User']['otp'])) {
                //                $chkl = $_POST['User']['otp'];
                $googleAuthenticator = new GoogleAuthenticator();
                $secret = $model->two_factor_auth_google_token;
                $oneTimeOtp = $_POST['User']['otp'];
                $checkResult = $googleAuthenticator->verifyCode($secret, $oneTimeOtp, 10); /*  2 = 2*30sec clock tolerance*/
                if ($checkResult) {
                    $model->check_auth_login = true;
                    $model->save(false);
                    // return $this->goBack();
                    return $this->redirectUserBasedOnRole();
                } else {
                    $wrongOtp = 'otp_validation';
                }
            }
            $frontendUrl = Yii::getAlias('@frontendUrl'); // Ensure this alias is set in your configuration
            if (!\Yii::$app->user->identity) {
                return $this->redirect($frontendUrl . '/user/sign-in/login');
            }
            $toEmail = $model->email;
            return $this->render('two-factor-auth', [
                'model' => $model,
                'recover' => isset($_GET['recover']) ? true : false,
                'toEmail' => $toEmail,
                'wrongOtp' => $wrongOtp
            ]);
        }
        return $this->goBack();
    }

    public function actionRecover()
    {


        $model = \Yii::$app->user->identity;

        $googleAuthentication = new \backend\controllers\GoogleAuthenticator();
        $secret = $googleAuthentication->createSecret();
        $model->two_factor_auth_google_token = $secret;
        $model->check_auth_login = false;
        if ($model->save()) {
            // Yii::$app->ssoHelper->updateGoogleAuthGG($model);
            //Yii::$app->ssoHelper->updateGoogleAuthLC($model);
        }

        $qrCodeUrl = $googleAuthentication->getQRCodeGoogleUrl(GlobalConstant::GOOGLE_APP_AUTH_NAME, $secret);
        $oneTimeOtpCode = $googleAuthentication->getCode($secret);
        $auth_dashboard_url = \Yii::$app->urlManager->createAbsoluteUrl('auth/default/index');

        //$email='ravipratap.handysolver@gmail.com';
        $email = $model->email;
        $subject = 'Reset Auth Token';
        $message = '<h2>Google Authenticator reset</h2><h3>You requested for resetting Auth token for your 2-factor auth.
        Here is your QR code, scan this code with google authenticator app.</h3><div><img src="' . $qrCodeUrl . '" align="center"></div><h3>If you are unable to scan then enter this secret in Authenticator App: <br>' . $secret . '</h3><br><br><h3>Or you can enter this code: </h3>' . $oneTimeOtpCode . '<h3>on the verification screen, and visit: <a href="' . $auth_dashboard_url . '"> dashboard->Profile->Auth Manager</a> and click on get new QR code button to change code.</h3>';
        sendGridEmail($email, $subject, $message);
        return $this->redirect(['/auth/default/two-factor-auth', 'recover' => true]);
    }

    public function actionResendOtpEmail()
    {
        $model = Yii::$app->user->identity;
    
        if (!$model) {
            $frontendUrl = Yii::getAlias('@frontendUrl');
            return $this->redirect($frontendUrl . '/user/sign-in/login');
        }
    
        // Generate new secret and OTP
        $googleAuthentication = new \backend\controllers\GoogleAuthenticator();
        $secret = $googleAuthentication->createSecret();
        $oneTimeOtpCode = $googleAuthentication->getCode($secret);
    
        // Save the new secret
        $model->two_factor_auth_google_token = $secret;
        $model->save(false);
    
        // Email content
        $email = $model->email;
        $subject = 'Your Two-Factor Authentication Code';
        $message = '
            <p>Dear ' . $model->username . ',</p>
            <p>Your verification code, essential for accessing your account, is:</p>
            <h3 style="font-size: 20px;"><b>' . $oneTimeOtpCode . '</b></h3>
            <p>This code is only valid for 5 minutes. Please refrain from sharing it if this login attempt wasn\'t initiated by you. Kindly use it promptly to complete your login. If you require any assistance, please don\'t hesitate to contact us at <a href="mailto:it@northmansterling.app">it@northmansterling.app</a>.</p>
            <p>Yours sincerely,<br>Team Northman Sterling</p>
        ';
    
        // Send using Yii mailer (same as your working method)
        $sent = Yii::$app->mailer->compose()
            ->setFrom(['authverify@reports.northmansterling.app' => 'Login OTP - N&S HR Portal'])
            ->setTo($email)
            ->setSubject($subject)
            ->setHtmlBody($message)
            ->send();
    
        if ($sent) {
            Yii::$app->session->setFlash('success', "The authentication code has been resent to your email address.");
        } else {
            Yii::$app->session->setFlash('error', "Failed to resend the authentication code. Please try again later.");
        }
    
        return $this->redirect(['/default/two-factor-auth']);
    }
    
    // public function actionResendOtpEmail()
    // {
    //     $model = Yii::$app->user->identity;
    //     $frontendUrl = Yii::getAlias('@frontendUrl'); // Ensure this alias is set in your configuration
    //     if (!\Yii::$app->user->identity) {
    //         return $this->redirect($frontendUrl . '/user/sign-in/login');
    //     }
    //     // Initialize Google Authenticator
    //     $googleAuthentication = new GoogleAuthenticator();
    //     $secret = $googleAuthentication->createSecret();
    //     $oneTimeOtpCode = $googleAuthentication->getCode($secret);

    //     // Set the new token in the database
    //     $model->two_factor_auth_google_token = $secret; // Update the token
    //     $model->save(false); // Save changes to the database


    //     $email = $model->email;
    //     $subject = 'Your Two-Factor Authentication Code';
    //     $message = '
    //     <p>Dear ' . $model->username . ' ,</p>
    //     <p>Your verification code, essential for accessing your account, is:</p>
    //     <h3 style="font-size: 20px;"><b>' . $oneTimeOtpCode . '</b></h3>
    //     <p>This code is only valid for 5 minutes. Please refrain from sharing it if this login attempt wasn\'t initiated by you. Kindly use it promptly to complete your login. If you require any assistance, please don\'t hesitate to contact us at <a href="mailto:it@northmansterling.app">it@northmansterling.app</a></p>
    //     <p>Yours sincerely,<br>Team Northman Sterling</p>';
    //     // \Yii::$app->mailer->compose()
    //     // ->setFrom(['info@northmansterling.app' => 'Northman Sterling'])
    //     // ->setTo($email)
    //     // ->setSubject($subject)
    //     // ->setHtmlBody($message)
    //     // ->send();
    //     Helper::sendEmailViaSes('authverify@northmansterling.app', $email, null, $subject, $message, null, null, null);
    //     Yii::$app->session->setFlash('success', "The authentication code has been sent to your email address.");
    //     return $this->redirect('/backend/web/default/two-factor-auth');
    //     // return $this->redirect(Url::to(['backend/web/default/send-otp-email'], true));


    // }


    // public function actionSendOtpEmail()
    // {
    //     $model = \Yii::$app->user->identity;
    //     $googleAuthentication = new \backend\controllers\GoogleAuthenticator();
    //     $secret = $model->two_factor_auth_google_token;
    //     $oneTimeOtpCode = $googleAuthentication->getCode($secret);

    //     //      Your PIN is 451956. For the next 5 minutes, you can use this for access to your Training Pipeline account. Please do not reply. For additional assistance, please contact us at support@trainingpipeline.com" Subject Line - "Two Factor Authentication Pin for Training Pipeline"

    //     //        $email='ravipratap.handysolver@gmail.com';
    //     $email = $model->email;
    //     $subject = 'Your Two-Factor Authentication Code';
    //     $message = '
    // <p>Dear ' . $model->username . ' ,</p>
    // <p>Your verification code, essential for accessing your account, is:</p>
    // <h3 style="font-size: 20px;"><b>' . $oneTimeOtpCode . '</b></h3>
    // <p>This code is only valid for 5 minutes. Please refrain from sharing it if this login attempt wasn\'t initiated by you. Kindly use it promptly to complete your login. If you require any assistance, please don\'t hesitate to contact us at <a href="mailto:it@northmansterling.app">it@northmansterling.app</a>.</p>
    // <p>Yours sincerely,<br>Team Northman Sterling</p>';
    //     // \Yii::$app->mailer->compose()
    //     // ->setFrom(['info@northmansterling.app' => 'Northman Sterling'])
    //     // ->setTo($email)
    //     // ->setSubject($subject)
    //     // ->setHtmlBody($message)
    //     // ->send();
    //     Helper::sendEmailViaSes('authverify@reports.northmansterling.app', $email, null, $subject, $message, null, null, null);
    //     Yii::$app->session->setFlash('success', "The authentication code has been sent to your email address.");
    //     return $this->redirect('/backend/web/default/two-factor-auth');
    //     // return $this->redirect(Url::to(['backend/web/default/send-otp-email'], true));


    // }

    public function actionSendOtpEmail()
    {
        $model = \Yii::$app->user->identity;
        $googleAuthentication = new \backend\controllers\GoogleAuthenticator();
        $secret = $model->two_factor_auth_google_token;
        $oneTimeOtpCode = $googleAuthentication->getCode($secret);
    
        $email = $model->email;
        $subject = 'Your Two-Factor Authentication Code';
        $message = '
            <p>Dear ' . $model->username . ',</p>
            <p>Your verification code, essential for accessing your account, is:</p>
            <h3 style="font-size: 20px;"><b>' . $oneTimeOtpCode . '</b></h3>
            <p>This code is only valid for 5 minutes. Please refrain from sharing it if this login attempt wasn\'t initiated by you. Kindly use it promptly to complete your login. If you require any assistance, please don\'t hesitate to contact us at <a href="mailto:it@northmansterling.app">it@northmansterling.app</a>.</p>
            <p>Yours sincerely,<br>Team Northman Sterling</p>
        ';
    
        // ✅ Use Yii2 mailer directly instead of Helper
        $sent = Yii::$app->mailer->compose()
            ->setFrom(['authverify@reports.northmansterling.app' => 'Login OTP - N&S HR Portal'])
            ->setTo($email)
            ->setSubject($subject)
            ->setHtmlBody($message)
            ->send();
    
        if ($sent) {
            Yii::$app->session->setFlash('success', "The authentication code has been sent to your email address.");
        } else {
            Yii::$app->session->setFlash('error', "Failed to send authentication code. Please try again later.");
        }
    
        return $this->redirect(['/default/two-factor-auth']);
    }
    
//     private function redirectUserBasedOnRole()
// {
//     $user = Yii::$app->user->identity;

//     if ($user->role == 'HR Manager') {
//         return $this->redirect(['/employee/index']);
//     } elseif ($user->role == 'Employee') {
//         return $this->redirect(['/leave-request/index']);
//     } else {
//         return $this->redirect(['/user/index']);
//     }
// }
private function redirectUserBasedOnRole()
{
    $user = Yii::$app->user->identity;

    if ($user->role == 'HR Manager') {
        return $this->redirect(['/employee/index']);
    } elseif ($user->role == 'Employee') {
        return $this->redirect(['/leave-request/index']);
    } elseif ($user->role == 'Team Manager') {
        return $this->redirect(['/leave-request/approve']);
    }else {
        return $this->redirect(['/user/index']);
    }
}

    public function actionEnable()
    {
        $user = Yii::$app->user->identity;
        $googleAuthenticator = new GoogleAuthenticator();

        if (empty($user->two_factor_auth_google_token)) {
            $user->two_factor_auth_google_token = $googleAuthenticator->createSecret();
            $user->save();
        }

        $qrCodeUrl = $googleAuthenticator->getQRCodeGoogleUrl($user->username, $user->two_factor_auth_google_token, 'N&S');
        $model = new EnableTwoFactorForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($googleAuthenticator->verifyCode($user->two_factor_auth_google_token, $model->code)) {
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
            if ($googleAuthenticator->verifyCode($user->two_factor_auth_google_token, $model->code)) {
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

    public function actionGenerateTwofa()
    {
        $this->layout = $this->layouts;

        $user = Yii::$app->user->identity;
        if ($user !== null && !$user->two_factor_auth_qr_token) {
            $user->two_factor_auth_qr_token = Yii::$app->google2fa->generateSecretKey(16); // Ensure 16 characters
            $secretKey = $user->two_factor_auth_qr_token;
        } else {
            // Handle the case where $user is null or two_factor_auth_qr_token already exists
            Yii::error('User identity is null or two_factor_auth_qr_token already exists.');
            $secretKey = null; // or any appropriate default action
        }

        $user->save(false);

        $qrCodeUrl = Yii::$app->google2fa->getQRCodeUrl($user->username, 'N&S', $user->two_factor_auth_qr_token);
        $qrCodeSvg = Yii::$app->google2fa->generateQRCode($qrCodeUrl);

        return $this->render('generate-2fa', [
            'secretKey' => $user->two_factor_auth_qr_token,
            'qrCodeSvg' => $qrCodeSvg,
        ]);
    }

    // public function actionVerifyTwofa()
    // {
    //     $this->layout = $this->layouts;
    //     $wrongOtp = '';
    //     $model =  \Yii::$app->user->identity;
    //     if ($model->check_auth_login == false) {
    //         $user = Yii::$app->user->identity;
    //         $verify  = new VerifyTwoFactorForm();
    //         if ($verify->load(Yii::$app->request->post()) && $verify->validate()) {
    //             if (Yii::$app->google2fa->verifyKey($user->two_factor_auth_qr_token, $verify->code)) {
    //                 $user->check_auth_login = true;
    //                 $user->save(false);
    //                 return $this->goBack();
    //             } else {
    //                 $wrongOtp = 'Verification failed!';
    //             }
    //         }
    //         $frontendUrl = Yii::getAlias('@frontendUrl'); // Ensure this alias is set in your configuration
    //         if (!\Yii::$app->user->identity) {
    //             return $this->redirect($frontendUrl . '/user/sign-in/login');
    //         }
    //         return $this->render('verify-2fa', [
    //             'verify' => $verify,
    //             'wrongOtp' => $wrongOtp,
    //         ]);
    //     }
    //     return $this->goBack();
    // }

    public function actionVerifyTwofa()
{
    $this->layout = $this->layouts;
    $wrongOtp = '';

    $user = Yii::$app->user->identity;

    // Check if user is logged in
    if ($user === null) {
        $frontendUrl = Yii::getAlias('@frontendUrl');
        return $this->redirect($frontendUrl . '/user/sign-in/login');
    }

    // Check if already verified
    if ($user->check_auth_login == false) {
        $verify  = new VerifyTwoFactorForm();

        if ($verify->load(Yii::$app->request->post()) && $verify->validate()) {
            if (Yii::$app->google2fa->verifyKey($user->two_factor_auth_qr_token, $verify->code)) {
                $user->check_auth_login = true;
                $user->save(false);
                return $this->goBack();
            } else {
                $wrongOtp = 'Verification failed!';
            }
        }

        return $this->render('verify-2fa', [
            'verify' => $verify,
            'wrongOtp' => $wrongOtp,
        ]);
    }

    // Already verified, go back
    return $this->goBack();
}


    public function actionRequestPasswordReset()
    {
        $this->layout = $this->layouts;
        $model = new PasswordResetRequestForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            Yii::debug('Form data loaded successfully');
            $user = User::findOne([
                'status' => User::STATUS_ACTIVE,
                'email' => $model->email,
            ]);
                if ($user) {
                    $user->generatePasswordResetToken();
                    if ($user->save()) {
                        $resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/user/sign-in/reset-password', 'token' => $user->password_reset_token]);
                            $email = $user->email;
                            $subject = 'Reset Password Link.';
                            $message = '
                            <p>Hello'.$user->username.',</p>
                            <p>Follow the link below to reset your password::</p>
                            <h3 style="font-size: 20px;"><a href="'.$resetLink.'" target="_blank"></h3>';

                            // Yii::$app->set('mailer', Yii::$app->get('sesMailer'));

                            $message = Yii::$app->mailer->compose();
                                $message->setFrom('authverify@northmansterling.app');
                                $message->setTo($email);
                                $message->setSubject($subject);
                                $message->setTextBody($message);
                            $message->send();
                            Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                            return $this->goHome();
                            //Helper::sendEmailViaSes('authverify@northmansterling.app', $email, null, $subject, $message, null, null, null);
                    }
                }
              
           
        } else {
            Yii::debug('Failed to load form data');
        }
        
        return $this->render('requestPasswordResetToken', ['model' => $model]);
    }

    public function actionResetPassword($token)
    {
        $model = new ResetPasswordForm($token);
        if ($model->load(Yii::$app->request->post()) && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');
            return $this->goHome();
        }

        return $this->render('resetPassword', ['model' => $model]);
    }
}
