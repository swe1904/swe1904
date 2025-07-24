<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Alert;

$this->title = "HR & Employee Matters Portal";
$this->params['breadcrumbs'][] = $this->title;

$imageUrl = getenv('BACKEND_URL') . 'images/Northman-logo.png';
$bgImage = Yii::$app->getUrlManager()->baseUrl . '/img/loginPageBackground.jpg';
?>

<!-- Mobile Viewport -->
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Optional Font Awesome for Eye Icon -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
body {
    margin: 0;
    padding: 0;
    font-family: "Segoe UI", sans-serif;
}

.container-login100 {
    min-height: 100vh;
    background-image: url('<?= $bgImage ?>');
    background-size: cover;
    background-position: top;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.logo img {
    max-width: 200px;
    margin: 0 auto 20px;
    display: block;
}

.login-card {
    background: #ffffffdd;
    padding: 30px 25px;
    border-radius: 10px;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
}

.login100-form-btn {
    width: 100%;
    padding: 10px;
    background-color: #3c8dbc;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-weight: 600;
    margin-bottom: 10px;
    transition: 0.3s;
}
.login100-form-btn:hover {
    background-color: #367fa9;
}

.input-group {
    position: relative;
}

.input-group-append {
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    cursor: pointer;
    z-index: 2;
    height: 100%;
    display: flex;
    align-items: center;
    padding-left: 5px;
    padding-right: 5px;
    background: none;
    border: none;
}

.alert-success {
    background: #22AF47;
    color: white;
}

a.forgot-link {
    display: block;
    text-align: right;
    font-size: 14px;
    margin-bottom: 15px;
}

@media (max-width: 480px) {
    .login-card {
        padding: 20px;
    }
}
</style>

<div class="container-login100">
    <div class="login-card">
        <div class="logo">
            <img src="<?= $imageUrl ?>" alt="Logo">
        </div>

        <!--<h4 class="text-center mb-3" style="color:#3c3c3c;">Sign in to your account</h4>-->
        <div class="card-header pb-0"><p style="color:#67748e; font-size:16px; "class="mb-0">Enter your username and password to sign in</p></div>

        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <?= Alert::widget([
                'options' => ['class' => 'alert-success'],
                'body' => Yii::$app->session->getFlash('success'),
            ]) ?>
        <?php endif; ?>

        <?php if (Yii::$app->session->hasFlash('error')): ?>
            <?= Alert::widget([
                'options' => ['class' => 'alert-danger'],
                'body' => Yii::$app->session->getFlash('error'),
            ]) ?>
        <?php endif; ?>

        <?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => ['class' => 'login-form']]); ?>

        <?= $form->field($model, 'identity')->textInput([
            'class' => 'form-control',
            'placeholder' => 'Username'
        ]) ?>

      <div class="form-group">
    <label for="password-field">Password</label>
    <div class="input-group">
        <?= Html::activePasswordInput($model, 'password', [
            'class' => 'form-control',
            'id' => 'password-field',
            'placeholder' => 'Password'
        ]) ?>
        <div class="input-group-append" id="toggle-password">
            <i class="fa fa-eye-slash" id="eye-icon"></i>
        </div>
    </div>
    <?= Html::error($model, 'password', ['class' => 'help-block']) ?>
</div>

        <a href="<?= getenv('FRONTEND_URL') ?>user/sign-in/request-password-reset" class="forgot-link">Forgot Password?</a>

        <?= Html::submitButton('Sign in Using Email Code', [
            'class' => 'login100-form-btn',
            'name' => 'login-button',
            'value' => 'Email-login'
        ]) ?>

        <div class="text-center my-2">— OR —</div>

        <?= Html::submitButton('Sign in Using Google Authenticator App', [
            'class' => 'login100-form-btn',
            'name' => 'login-button',
            'value' => 'google-auth'
        ]) ?>

        <?php ActiveForm::end(); ?>

        <p class="text-center mt-3" style="font-size:13px; color:#888;">
            Need help? <?= Html::mailto('it@northmansterling.app') ?>
        </p>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const passwordField = document.getElementById("password-field");
    const togglePassword = document.getElementById("toggle-password");
    const eyeIcon = document.getElementById("eye-icon");

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
