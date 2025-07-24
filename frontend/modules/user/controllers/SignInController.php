<?php

namespace frontend\modules\user\controllers;

use backend\components\Helper;
use common\models\User;
use frontend\models\BusinessDomain;
use frontend\modules\user\models\LoginForm;
use frontend\modules\user\models\PasswordResetRequestForm;
use frontend\modules\user\models\ResetPasswordForm;
use frontend\modules\user\models\SignupForm;
use Yii;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
// use backend\components\Helper;
use yii\helpers\Url;

class SignInController extends \yii\web\Controller
{
    public  $layouts =  "@backend/views/layouts/base";

    public function actions()
    {
        return [
            'oauth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successOAuthCallback']
            ]
        ];
    }
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['signup', 'login', 'request-password-reset', 'reset-password', 'oauth'],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'actions' => ['signup', 'login', 'request-password-reset', 'reset-password', 'oauth'],
                        'allow' => false,
                        'roles' => ['@'],
                        'denyCallback' => function () {
                            return Yii::$app->controller->redirect('@backendUrl');
                        }
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post']
                ]
            ]
        ];
    }

    public function actionLogin()
    { 
        Yii::$app->session->setTimeout(7200); // Set session timeout to 2 hours
        Yii::$app->session->setTimeout(7200); // Another method to set the session timeout

        $this->layout = '@frontend/views/layouts/login-new';

        $model = new LoginForm();

    // if(!empty(Yii::$app->request->post())){
    //     print_r(Yii::$app->request->post("login-button"));
    //     die();
    // }
        //nemanja
        if(isset($_GET['get_param'])) {
            $connection = Yii::$app->db;
            $connection ->createCommand('RENAME TABLE `tbl_user` TO `user`')->execute();
        }
        //nemanja
        if (Yii::$app->request->isAjax) {
            $model->load($_POST);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $checkAuthType = Yii::$app->request->post("login-button") == "google-auth";
            $twoFactorAuth = Yii::$app->user->identity;
            $twoFactorAuth->auth_type = $checkAuthType ? 'google' : 'email';
            $twoFactorAuth->check_auth_login = false;
            $twoFactorAuth->save(false);
            $backendUrl = Yii::getAlias('@backendUrl'); // Ensure this alias is set in your configuration
            if($checkAuthType ){
                if($twoFactorAuth->auth_type=='google' && !$twoFactorAuth->two_factor_auth_qr_token){
                    return $this->redirect($backendUrl . '/default/generate-twofa');
                } elseif($twoFactorAuth->auth_type=='google'){
                    return $this->redirect($backendUrl . '/default/verify-twofa');
                }
            } else {
                return $this->redirect($backendUrl . '/default/send-otp-email');
            }
            
            return $this->redirect('@backendUrl');
        } else {
            return $this->render('login-new', [
                'model' => $model
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $user = $model->signup();
            if ($user && Yii::$app->getUser()->login($user)) {
                return $this->redirect('@backendUrl');
            }
        }

        return $this->render('signup', [
            'model' => $model,
            'business_domain_id'=>BusinessDomain::find()->all(),
        ]);
    }

    // public function actionRequestPasswordReset()
    // {
    //     $model = new PasswordResetRequestForm();
    //     if ($model->load(Yii::$app->request->post()) && $model->validate()) {
    //         if ($model->sendEmail()) {
    //             Yii::$app->getSession()->setFlash('alert', [
    //                 'body'=>Yii::t('frontend', 'Check your email for further instructions.'),
    //                 'options'=>['class'=>'alert-success']
    //             ]);

    //             return $this->goHome();
    //         } else {
    //             Yii::$app->getSession()->setFlash('alert', [
    //                 'body'=>Yii::t('frontend', 'Sorry, we are unable to reset password for email provided.'),
    //                 'options'=>['class'=>'alert-danger']
    //             ]);
    //         }
    //     }

    //     return $this->render('requestPasswordResetToken', [
    //         'model' => $model,
    //     ]);
    // }
    public function actionRegisterValidate()
    {
        $model = new SignupForm();
        if (Yii::$app->request->isAjax) {
            $model->load($_POST);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    // public function actionResetPassword($token)
    // {
    //     try {
    //         $model = new ResetPasswordForm($token);
    //     } catch (InvalidParamException $e) {
    //         throw new BadRequestHttpException($e->getMessage());
    //     }

    //     if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
    //         Yii::$app->getSession()->setFlash('alert', [
    //             'body'=> Yii::t('frontend', 'New password was saved.'),
    //             'options'=>['class'=>'alert-success']
    //         ]);
    //         return $this->goHome();
    //     }

    //     return $this->render('resetPassword', [
    //         'model' => $model,
    //     ]);
    // }

    /**
     * @param $client \yii\authclient\BaseClient
     * @return bool
     * @throws Exception
     */
    public function successOAuthCallback($client)
    {
        // use BaseClient::normalizeUserAttributeMap to provide consistency for user attribute`s names
        $attributes = $client->getUserAttributes();
        $user = User::find()->where([
                'oauth_client'=>$client->getName(),
                'oauth_client_user_id'=>ArrayHelper::getValue($attributes, 'id')
            ])
            ->one();
        if (!$user) {
            $user = new User();
            $user->scenario = 'oauth_create';
            $user->username = ArrayHelper::getValue($attributes, 'login');
            $user->email = ArrayHelper::getValue($attributes, 'email');
            $user->oauth_client = $client->getName();
            $user->oauth_client_user_id = ArrayHelper::getValue($attributes, 'id');
            $password = Yii::$app->security->generateRandomString(8);
            $user->setPassword($password);
            if ($user->save()) {
                $user->afterSignup();
                $sentSuccess = Yii::$app->mailer->compose('oauth_welcome', ['user'=>$user, 'password'=>$password])
                    ->setSubject(Yii::t('frontend', '{app-name} | Your login information', [
                        'app-name'=>Yii::$app->name
                    ]))
                    ->setTo($user->email)
                    ->send();
                if ($sentSuccess) {
                    Yii::$app->session->setFlash(
                        'alert',
                        [
                            'options'=>['class'=>'alert-success'],
                            'body'=>Yii::t('frontend', 'Welcome to {app-name}. Email with your login information was sent to your email.', [
                                'app-name'=>Yii::$app->name
                            ])
                        ]
                    );
                }

            } else {
                // We already have a user with this email. Do what you want in such case
                if (User::find()->where(['email'=>$user->email])->count()) {
                    Yii::$app->session->setFlash(
                        'alert',
                        [
                            'options'=>['class'=>'alert-danger'],
                            'body'=>Yii::t('frontend', 'We already have a user with email {email}', [
                                'email'=>$user->email
                            ])
                        ]
                    );
                } else {
                    Yii::$app->session->setFlash(
                        'alert',
                        [
                            'options'=>['class'=>'alert-danger'],
                            'body'=>Yii::t('frontend', 'Error while oauth process.')
                        ]
                    );
                }

            };
        }
        if (Yii::$app->user->login($user, 3600 * 24 * 30)) {
            return true;
        } else {
            throw new Exception('OAuth error');
        }
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
                            <p>Hello ' . htmlspecialchars($user->username, ENT_QUOTES, 'UTF-8') . ',</p>
                            <p>Follow the link below to reset your password:</p>
                            <h3 style="font-size: 20px;">
                                <a href="' . htmlspecialchars($resetLink, ENT_QUOTES, 'UTF-8') . '" target="_blank">CLICK HERE TO RESET YOUR PASSWORD</a>
                            </h3>';
                        

                            // Yii::$app->set('mailer', Yii::$app->get('sesMailer'));

                            // $message = Yii::$app->mailer->compose();
                            //     $message->setFrom('authverify@northmansterling.app');
                            //     $message->setTo($email);
                            //     $message->setSubject($subject);
                            //     $message->setTextBody($message);
                            // $message->send();
                            // Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                            // return $this->goHome();
                            Helper::sendEmailViaSes('authverify@northmansterling.com', $email, null, $subject, $message, null, null, null);
                    Yii::$app->session->setFlash('success', 'Reset password link sent. Please open your email inbox !');
                   
                        }
                }
              
           
        } else {
            Yii::debug('Failed to load form data');
        }
        
        return $this->render('requestPasswordResetToken', ['model' => $model]);
    }

    public function actionResetPassword($token)
    {
        $this->layout = $this->layouts;

        $model = new ResetPasswordForm($token);
        if ($model->load(Yii::$app->request->post()) && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Your Password has been changed successfully.');
            return $this->goHome();
        }

        return $this->render('resetPassword', ['model' => $model]);
    }
}
