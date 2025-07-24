<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Service */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .field-service-id label{
        display: none;
    }
</style>
<div class="service-form">

    <?php $form = ActiveForm::begin(['action'=>'service-update-model']); ?>

    <?= $form->field($model, 'name')->textInput() ?>
    <?= $form->field($model, 'id')->hiddenInput() ?>
    <input type="hidden" value="<?php echo $org_id ?>" name="org_id">
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
