<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

$this->title = Yii::t('backend', 'Two Factor Authentication');
$this->params['breadcrumbs'][] = $this->title;
$this->params['body-class'] = 'login';

?>
<style>
    .go{
        width: 100% !important;
    }

    .wrap-login100 {
        font-family: "Poppins",sans-serif !important;
    }
    body{
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 14px;
        line-height: 1.42857143;
        color: #333;
    }
    /* .field-user-otp .help-block {
        color: #e08a9e;
        text-align: center;
        margin-top: 15px;
        font-style: italic;
        font-weight: 400;
    } */
    .form-wrap {
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 12px !important;
        /* height: 100vh;  */
        /* background-color: #f7f7f7; */
    }

    .form-content {
        width: 100%;
        /* max-width: 400px;  */
        padding: 20px;
        /* background-color: #fff;  */
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 12px !important;
    }

    .container-login100 {
        width: 100%;
        height: 100vh;
        /* display: -webkit-box;
        display: -webkit-flex;
        display: -moz-box;
        display: -ms-flexbox; */
        display: flex;
        flex-wrap: wrap;
        /*justify-content: center;*/
        align-items: center;
        padding: 100px 15px;

        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
        position: relative;
        z-index: 1;
    }

    .container-login100::before {
        content: "";
        display: block;
        position: absolute;
        z-index: -1;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        /* background-color: rgba(255,255,255,0.6); */
    }
    .login100-form-btn {
        font-family: Poppins-Medium;
        font-size: 12.5px;
        color: #fff;
        line-height: 1.2;
        margin: 0 auto;
        text-transform: uppercase;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0 35px;
        width: 100%;
        padding: 8px 8px !important;
        border-radius: 2px;
        background: linear-gradient(310deg,#2152ff,#21d4fd);
        position: relative;
        z-index: 1;
        color: #fff;
        -webkit-transition: all 0.4s;
        -o-transition: all 0.4s;
        -moz-transition: all 0.4s;
        transition: all 0.4s;
        border: 0px;
    }
    .alert-success{
        background: #22AF47 !important;
    }
    @media only screen and (max-width: 600px) {
        .logo {
            width: 100% !important;
        }
        .wrap-login100 {
            width: 100% !important;
        }
    }
</style>
<?php
$user = Yii::$app->user->identity;

?>

<div class="limiter">
    <div class="container-login100" style="overflow: auto !important; background-position:top; height: 100vh; background-image: url('https://pangea.codetrolley.com/frontend/web/img/loginPageBackground.jpg');">

    <?php $imageUrl =  getenv('BACKEND_URL').'images/Northman-logo.png'; ?>
    <div class="container">
        <div class="row" style="">
            <div class="col-md-12">
                    <div class="logo" style="width:50%; margin:auto;">
                        <i class=""><img style="width:100%; margin:auto; display:block;" src="<?php echo $imageUrl;?>"></i></img>
                    </div>
            </div>
            <div class="col-md-12">
            <div class="wrap-login100 card-plain bg-light card" style="border-radius: 12px !important; margin-top:20px; width: 50%; margin-left: auto; margin-right: auto;display: block; background-color:#e9ecef!important;">
            <div class="form-wrap">
                <div class="form-content">
                    <p class="text-start">Hi <?= $user->username ?></p>
                    <?php if (true): ?>
                        <?= Alert::widget([
                            'options' => ['class' => 'alert-success'],
                            'body' => 'Open Authenticator app to get code.',
                        ]) ?>
                    <?php endif; ?>
                        <?php if (isset($wrongOtp) && $wrongOtp): ?>
                            <div class="alert alert-danger text-center">
                                <?= Yii::t('backend', 'The Code you entered is incorrect. Please try again.') ?>
                            </div>
                        <?php endif; ?>
                        <!-- <p class="text-start">Enter the Security Code</p> -->
                        <div class="form-group has-search">
                            <?php if(isset($recover) && $recover == true): ?>
                                <h5 style="color: #9b0202;">
                                    <?= Yii::t('backend', 'We have sent an email containing the QR code and secret to your email:').'<br><strong>'.$toEmail.'</strong>' ?>
                                </h5>
                            <?php endif; ?>
                            <span class="form-control-feedback">
                                <img src="<?php echo Yii::getAlias('@backendUrl').'/dist/images/lock.svg'?>" alt="">
                            </span>
                        <?php $form = ActiveForm::begin(['id' => 'two-factor-auth-form']); ?>
                            <?= $form->field($verify, 'code')->textInput([
                                'autocomplete' => 'off',
                                'placeholder' => Yii::t('backend', 'Code'),
                                'class' => 'form-control',
                                'required' => true
                            ])->label('Enter the Code') ?>
                        </div>
                        <div class="text-center">
                            <?= Html::submitButton(Yii::t('backend', 'GO'), [
                                'class' => 'btn btn-primary go login100-form-btn',
                            ]) ?><br>
                        <?php ActiveForm::end(); ?>

                            <div >
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <a style="border-radius: 5px !important;color: #3c8dbc;" href="<?= getenv('BACKEND_URL')?>sign-in/logout" data-method="post" class=""><span>Logout</span></a>

                                <div style="color: #333;">
                                    
                                    <a style="order-radius: 5px !important;color: #3c8dbc;" href="<?= getenv('BACKEND_URL')?>default/generate-twofa" data-method="post" class=""><span>Scan QR code here.</span></a>
                                </div>
                            </div>
                            </div>
                            <br>
                            <div style="color:#999;margin:1em 0;text-align: center;">
                            <span class="type--fine-print block">
                                <?= Yii::t('backend', "Need help? Contact us at ") ?>
                                <?= \yii\helpers\Html::mailto('it@northmansterling.app') ?>
                            </span>
                            </div>
                        </div>
                </div>
            </div>
            </div>
        </div>
            </div>
        </div>
    </div>



    </div>
</div>
















