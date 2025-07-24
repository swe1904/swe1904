<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

$this->title = Yii::t('backend', 'Reset Password');
$this->params['breadcrumbs'][] = $this->title;
$this->params['body-class'] = 'login';

$user = Yii::$app->user->identity;
?>

<style>
    .login-form>* {
        margin: 15px auto !important;
    }

    .toggle-btn .form-group {
        position: relative;
        display: inline-block;
        padding-left: 49px;
        height: 20px;
        margin-bottom: 0px;
    }

    .form-control {
        border-radius: .5rem !important;
    }

    .input-group-append {
        display: inline;
        position: relative;
        float: right;
        top: -27px;
        z-index: 9;
        background-color: #fff0 !important;
        border: 0px !important;
    }

    .toggle-btn .form-group label {
        font-weight: 400;
    }

    .toggle-btn .form-group #remember-me {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
        width: 40px;
        height: 20px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        top: 50%;
        left: 2px;
        transform: translateY(-50%);
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    #remember-me:checked+.slider {
        border-color: rgba(58, 65, 111, .95);
        background-color: rgba(58, 65, 111, .95);
    }

    #remember-me:focus+.slider {
        box-shadow: 0 .3125rem .625rem 0 rgba(0, 0, 0, .12);
        top: 1px;
    }

    #remember-me:checked+.slider:before {
        -webkit-transform: translate(21px, -50%);
        -ms-transform: translate(21px, -50%);
        transform: translate(21px, -50%);

    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 04px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    /* Prevent default Bootstrap help box */
    .form-group .help-block {
        display: none;
    }

    /* Prevent focus color */
    .form-control:focus {
        border-color: initial;
        box-shadow: none;
    }

    /* Add padding to the input group */
    .input-group {
        position: relative;
        width: 100%;
    }

    .input-group-append {
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 10px;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-left: 0;
    }

    #toggle-password {
        cursor: pointer;
    }

    .go {
        width: 100% !important;
    }

    .wrap-login100 {
        font-family: "Poppins", sans-serif !important;
    }

    body {
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
        /* font-family: Poppins-Medium; */
        font-size: 12.5px;
        color: #fff;
        line-height: 1.2;
        margin: 0 auto;
        text-transform: capitalize;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0 35px;
        width: 100%;
        padding: 8px 8px !important;
        border-radius: 2px;
        background: linear-gradient(310deg, #2152ff, #21d4fd);
        position: relative;
        z-index: 1;
        color: #fff;
        -webkit-transition: all 0.4s;
        -o-transition: all 0.4s;
        -moz-transition: all 0.4s;
        transition: all 0.4s;
        border: 0px;
    }

    .alert-success {
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

    #toggle-password {
        cursor: pointer;
    }
</style>

<div class="limiter">
    <div class="container-login100"
        style="overflow: auto !important; background-position:top; height: 100vh; background-image: url('https://pangea.codetrolley.com/frontend/web/img/loginPageBackground.jpg');">
        <div class="container">
            <div class="row" style="">
                <div class="col-md-12">
                    <div class="logo" style="width:50%; margin:auto;">
                        <i class=""><img style="width:100%; margin:auto; display:block;"
                                src="https://portal.northmansterling.com/assets/img/logos/N&S%20FINAL%20LOGO%20-%202024.png"></i></img>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="wrap-login100 card-plain bg-light card"
                        style="border-radius: 12px !important; margin-top:20px; width: 50%; margin-left: auto; margin-right: auto;display: block; background-color:#e9ecef!important;">
                        <div class="form-wrap">
                            <div class="form-content">
                                <div class="site-request-password-reset">
                                    <h2><?= Html::encode($this->title) ?></h2>
                                    <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
                                    <div class="input-group" style="width: 100%;">
                                        <?php echo $form->field($model, 'password')->passwordInput(['class' => 'form-control', 'id' => 'password-field']); ?>
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="toggle-password">
                                                <i class="fa fa-eye-slash" id="eye-icon"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="container-login100-form-btn" style="text-align:center;">
                                        <?php echo Html::submitButton(Yii::t('frontend', 'Save'), [
                                            'class' => 'login100-form-btn',
                                            'style' => 'text-transform: capitalize;',
                                        ]) ?>
                                    </div>
                                    <?php ActiveForm::end(); ?>
                                    <?php if (Yii::$app->session->hasFlash('success')): ?>
                                        <?= Alert::widget([
                                            'options' => ['class' => 'alert-success'],
                                            'body' => Yii::$app->session->getFlash('success'),
                                        ]) ?>
                                    <?php endif; ?>
                                    <?php if ($model->hasErrors()): ?>
                                        <div class="alert alert-danger">
                                            <?php foreach ($model->getErrors() as $error): ?>
                                                <?php foreach ($error as $message): ?>
                                                    <p><?= Html::encode($message) ?></p>
                                                <?php endforeach; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
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

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Find the checkbox input element


        // Toggle password visibility
        var passwordField = document.getElementById("password-field");
        var togglePassword = document.getElementById("toggle-password");
        var eyeIcon = document.getElementById("eye-icon");

        togglePassword.addEventListener("click", function () {
            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            } else {
                passwordField.type = "password";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            }
        });
    });
</script>