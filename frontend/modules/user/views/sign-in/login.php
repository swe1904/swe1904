<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \frontend\modules\user\models\LoginForm */

//$this->title = Yii::t('frontend', 'Login');
$this->params['breadcrumbs'][] = $this->title;

?>



<div class="login">
    <header class="login-header"><span class="text">LOGIN</span><span class="loader"></span></header>
    <?php $form = ActiveForm::begin(['id' => 'login-form','options' => ['class'=>'login-form']]); ?>
        <h3>Login</h3>
    <?php echo $form->field($model, 'identity')->textInput(['class'=>'login-input','placeholder'=>'Username'])->label(false) ?>
<!--        <input class="login-input" type="text" placeholder="Username"/>-->
    <?php echo $form->field($model, 'password')->passwordInput(['class'=>'login-input','placeholder'=>'Password'])->label(false); ?>
    <?php echo $form->field($model, 'rememberMe')->checkbox() ?>
<!--        <input class="login-input" type="password" placeholder="Password"/>-->
    <?php echo Html::submitButton(Yii::t('frontend', 'Login'), ['class' => 'login-btn', 'name' => 'login-button']) ?>
<!--        <button class="login-btn" type="submit">login</button>-->
    <?php ActiveForm::end(); ?>
</div>


<!-------login------->
<script>
    $('.login-input').on('focus', function() {
        $('.login').addClass('focused');
    });

    $('.login').on('submit', function(e) {
        e.preventDefault();
        $('.login').removeClass('focused').addClass('loading');
    });
</script>
