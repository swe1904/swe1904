<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\i18n\models\I18nMessage */
/* @var $form yii\bootstrap\ActiveForm */
?>
<div class="col-md-6 col-md-offset-3">
<div class="i18n-message-form">

    <?php $form = ActiveForm::begin(); ?>
    <label class="control-label custom-label" for="id">
        <?php echo  Yii::t('backend','Message');?>
    </label>
    <?php echo $form->field($model, 'id')->dropDownlist(\yii\helpers\ArrayHelper::map(\backend\modules\i18n\models\I18nSourceMessage::find()->orderBy(['id'=>SORT_ASC])->all(),'id','message') ,['class'=>'myselect'])->label('Message') ?>

    <?php if (!$model->isNewRecord): ?>
        <label class="control-label custom-label" for="category">
            <?php echo  $model->getAttributeLabel('category');?>
        </label>
        <?php echo $form->field($model, 'category')->textInput(['disabled'=>true,'class'=>'formInput'])->label(false) ?>

        <label class="control-label custom-label" for="sourceMessage">
            <?php echo  $model->getAttributeLabel('sourceMessage');?>
        </label>
        <?php echo $form->field($model, 'sourceMessage')->textInput(['disabled'=>true,'class'=>'formInput'])->label(false) ?>
    <?php endif; ?>

    <label class="control-label custom-label" for="language">
        <?php echo  $model->getAttributeLabel('language');?>
    </label>
    <?php echo $form->field($model, 'language')->dropDownlist(Yii::$app->params['availableLocales'],['class'=>'myselect'])->label(false) ?>

    <?php echo $form->field($model, 'translation')->textarea(['rows' => 6,'class'=>'formInput']) ?>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
