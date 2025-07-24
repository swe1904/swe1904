<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \frontend\modules\user\models\SignupForm */

$this->title = Yii::t('frontend', 'Signup');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?php echo Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup','enableAjaxValidation' => true]); ?>
            <?php echo $form->field($model, 'email') ?>
            <?php echo $form->field($model, 'password')->passwordInput() ?>
            <?php echo $form->field($model, 'password_confirm')->passwordInput() ?>
            <?php
            $role = array('Small' => 'Small', 'Medium' => 'Medium', 'Large' => 'Large');
            echo $form->field($model, 'organisation_size')->dropDownList($role, ['prompt' => 'Select Size']);
            ?>
            <?php echo $form->field($model, 'firstname') ?>
            <?php echo $form->field($model, 'lastname') ?>
            <?php echo $form->field($model, 'business_domain_id')->dropDownList(\yii\helpers\ArrayHelper::map(
                $business_domain_id,
                'id',
                'name'
            ), ['prompt' => 'Select','onChange' => 'checkRoleOrganization();']) ?>
            <div id="role_other_div" style="display: none;padding-top: 5px;">
            <?php echo $form->field($model, 'business_domain_other')->textInput(['class' => 'form-control', 'placeholder' => 'If Other, please specify']); ?>
                </div>

                <div class="form-group">
                    <?php echo Html::submitButton(Yii::t('frontend', 'Signup'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
                <!--<h2><?php /*echo Yii::t('frontend', 'Sign up with')  */?>:</h2>
                <div class="form-group">
                    <?php /*echo yii\authclient\widgets\AuthChoice::widget([
                        'baseAuthUrl' => ['/user/sign-in/oauth']
                    ]) */?>
                </div>-->
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<script>
    function checkRoleOrganization() {
        $("#role_other_div").hide();
        var $domain = $("#signupform-business_domain_id").val();
        if ($domain == '6') {
            $("#role_other_div").show();
        }
        else {
            $("#role_other_div").hide();
        }
    }
</script>
