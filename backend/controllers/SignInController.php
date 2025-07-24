<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 8/2/14
 * Time: 11:20 AM
 */

namespace backend\controllers;

use app\components\GlobalConstant;
use backend\models\LoginForm;
use backend\models\AccountForm;
use Intervention\Image\ImageManagerStatic;
use trntv\filekit\actions\DeleteAction;
use trntv\filekit\actions\UploadAction;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\imagine\Image;
use yii\web\Controller;

class SignInController extends Controller
{

    public $defaultAction = 'login';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post']
                ]
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [

                    [
                        'actions' => ['profile','account','avatar-upload','avatar-delete' ],
                        'allow' => true,
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
        ];
    }

    public function actions()
    {
        return [
            'avatar-upload' => [
                'class' => UploadAction::className(),
                'deleteRoute' => 'avatar-delete',
                'on afterSave' => function ($event) {
                    /* @var $file \League\Flysystem\File */
                    $file = $event->file;
                    $img = ImageManagerStatic::make($file->read())->fit(215, 215);
                    $file->put($img->encode());
                }
            ],
            'avatar-delete' => [
                'class' => DeleteAction::className()
            ]
        ];
    }


    public function actionLogin()
    {
        $this->layout = 'base';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model
            ]);
        }
    }



    public function actionLogout()
    {
        Yii::$app->user->logout();
        //return $this->goHome();
        return $this->redirect('@frontendUrl'.'/user/sign-in/login');
    }

    public function actionProfile()
    {
        $model = Yii::$app->user->identity->userProfile;
        if (isset($_POST) && !empty($_POST)) {
            $profile_data = $_POST['UserProfile'];
          //  $model->paypal_email = $profile_data['paypal_email'];
        }
        if ($model->load($_POST) && $model->save()) {
            Yii::$app->session->setFlash('alert', [
                'options' => ['class' => 'alert-success'],
                'body' => Yii::t('backend', 'Your account has been successfully saved')
            ]);
            return $this->refresh();
        }
        return $this->render('profile', ['model' => $model]);
    }

    public function actionAccount()
    {
        $user = Yii::$app->user->identity;
        $model = new AccountForm();
        $model->email = $user->email;
        $model->username = $user->username;
        if ($model->load($_POST) && $model->validate()) {
            $user->username = $model->username;
            $user->email = $model->email;
            $user->setPassword($model->password);
            if($user->save()){
                Yii::$app->session->setFlash('alert', [
                    'options' => ['class' => 'alert-success'],
                    'body' => Yii::t('backend', 'Your profile has been successfully saved')
                ]);
            }else{
                Yii::$app->session->setFlash('alert', [
                    'options' => ['class' => 'alert-error'],
                    'body' => Yii::t('backend', 'Your profile could not be saved ')
                ]);
            }

            return $this->refresh();
        }
        return $this->render('account', ['model' => $model]);
    }
}
