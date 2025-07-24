<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Service */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .field-receiptitem-id label{
        display: none;
    }
</style>
<div class="service-form">

    <?php $form = ActiveForm::begin(['action'=>'receipt-item-update']); ?>

    <?= $form->field($model, 'price')->textInput() ?>
    <?= $form->field($model, 'description')->textarea(['cols'=>4]) ?>
    <?= $form->field($model, 'id')->hiddenInput() ?>
    <input type="hidden" value="<?php echo $receipt_id ?>" name="receipt_id">
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>