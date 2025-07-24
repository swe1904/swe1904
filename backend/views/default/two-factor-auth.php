<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;

$this->title = Yii::t('backend', 'Two Factor Authentication');
$this->params['breadcrumbs'][] = $this->title;
$this->params['body-class'] = 'login';

$imageUrl = getenv('BACKEND_URL') . 'images/Northman-logo.png';
$bgImage = 'https://pangea.codetrolley.com/frontend/web/img/loginPageBackground.jpg';
$user = Yii::$app->user->identity;
?>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
    body {
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 14px;
        margin: 0;
        padding: 0;
        color: #333;
    }

    .container-login100 {
        min-height: 100vh;
        background-image: url('<?= $bgImage ?>');
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px;
    }

    .login-box {
        background: rgba(255, 255, 255, 0.95);
        padding: 30px 25px;
        border-radius: 12px;
        width: 100%;
        max-width: 420px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }

    .logo img {
        max-width: 200px;
        display: block;
        margin: 0 auto 20px;
    }

    .login100-form-btn {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: none;
        color: #fff;
        background: linear-gradient(310deg,#2152ff,#21d4fd);
        text-transform: uppercase;
        margin-bottom: 15px;
        font-weight: bold;
    }

    .alert-success {
        background: #22AF47;
        color: #fff;
        text-align: center;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: 600;
    }

    .form-control-feedback img {
        height: 20px;
        margin-right: 8px;
    }

    .action-links {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
    }

    @media (max-width: 480px) {
        .login-box {
            padding: 20px;
        }

        .action-links {
            flex-direction: column;
            gap: 8px;
            text-align: center;
        }
    }
</style>

<div class="container-login100">
    <div class="login-box">
        <div class="logo">
            <img src="<?= $imageUrl ?>" alt="Logo">
        </div>

        <p class="text-center mb-3">Hi <?= Html::encode($user->username) ?></p>

        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <?= Alert::widget([
                'options' => ['class' => 'alert-success'],
                'body' => Yii::$app->session->getFlash('success'),
            ]) ?>
        <?php endif; ?>

        <?php if (isset($wrongOtp) && $wrongOtp): ?>
            <div class="alert alert-danger text-center">
                <?= Yii::t('backend', 'The Code you entered is incorrect. Please try again.') ?>
            </div>
        <?php endif; ?>

        <?php $form = ActiveForm::begin(['id' => 'two-factor-auth-form']); ?>

        <div class="form-group">
            <?php if (isset($recover) && $recover == true): ?>
                <h5 style="color: #9b0202;">
                    <?= Yii::t('backend', 'We have sent an email containing the QR code and secret to your email:') ?><br>
                    <strong><?= Html::encode($toEmail) ?></strong>
                </h5>
            <?php endif; ?>

            <?= $form->field($model, 'otp')->textInput([
                'autocomplete' => 'off',
                'placeholder' => Yii::t('backend', 'Code'),
                'class' => 'form-control',
                'required' => true
            ])->label(Yii::t('backend', 'Enter the Security Code')) ?>
        </div>

        <?= Html::submitButton(Yii::t('backend', 'GO'), ['class' => 'login100-form-btn']) ?>

        <div class="action-links mt-2">
            <a href="<?= getenv('BACKEND_URL') ?>sign-in/logout" data-method="post">Logout</a>
            <span>
                Didn't receive a code?
                <a href="<?= getenv('BACKEND_URL') ?>default/resend-otp-email" data-method="post">Resend Code</a>
            </span>
        </div>

        <p class="text-center mt-3" style="font-size:13px; color:#888;">
            <?= Yii::t('backend', "Need help? Contact us at ") ?>
            <?= Html::mailto('it@northmansterling.app') ?>
        </p>

        <?php ActiveForm::end(); ?>
    </div>
</div>
