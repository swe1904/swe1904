<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\LoginForm */

?>

<div class="login-box">
    <div class="login-logo">
        Congratulations!
    </div><!-- /.login-logo -->
    <div class="header" style="text-align: center;">You have successfully created your account !</div>
    <div class="login-box-body">

        <div class="footer" style="margin-top:10px;">
            <a href="<?php echo Yii::$app->urlManager->createUrl('sign-in/login')?>"><span class="btn btn-primary btn-flat btn-block" >Go back to login</span></a>
        </div>

    </div>

</div>