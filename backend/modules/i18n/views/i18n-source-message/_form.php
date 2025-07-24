<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\i18n\models\I18nSourceMessage */
/* @var $form yii\bootstrap\ActiveForm */
?>
<div class="row clearfix">
    <div class="col-md-12">
        <div class="">
            <div class="">
                <div class="row clearfix">
<?php if($this->context->action->id=='index'||$this->context->action->id=='create'){
    $action='i18n-source-message/create';
}else if(isset($_GET['id'])){
    $action='i18n-source-message/update?id='.$_GET['id'];
}else{
     $action='';
}?>
                    <?php $form = ActiveForm::begin(['action' => [$action], 'fieldConfig' => [
                        'options' => [
                            'options' => ['class' => 'form-group invisible']
                        ],
                    ],]); ?>
<!--                    <div class="col-md-12">-->
                        <?php echo $form->field($model, 'category')->hiddenInput(['maxlength' => 32, 'class' => 'formInput', 'value' => 'backend'])->label(false) ?>
<!--                    </div>-->
                    <div class="col-md-4">
                        <?php echo $form->field($model, 'message')->textarea(['rows' => 6, 'class' => 'form-control border']) ?>
                    </div>
                    <div class="col-md-4">
                    <?php echo $form->field($model, 'arabicLanguage')->textarea(['rows' => 6, 'class' => 'form-control border']) ?>
                </div>
                <div class="col-md-4">
                    <?php echo $form->field($model, 'espanolLanguage')->textarea(['rows' => 6, 'class' => 'form-control border']) ?>
                </div>
                <div class="col-md-12">
                    <?php echo Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'btn btn-rounded btn-success mt-10 mr-10']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
</div>
