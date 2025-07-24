<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\Client;
/* @var $this yii\web\View */
/* @var $model frontend\models\Client */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="client-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'date_1528809715690')->widget(\kartik\date\DatePicker::classname(), [
                                                                'model' => $model,

                                                                'options' => ['placeholder' => ''],
                                                                'pluginOptions' => [
                                                                    'todayHighlight' => true,
                                                                    'todayBtn' => true,
                                                                    'format' => 'yyyy-mm-dd',
                                                                    'autoclose' => true,
                                                                ]
                                                            ]) ?>

    <?= $form->field($model, 'text_1528808645886')->textInput() ?>

    <?= $form->field($model, 'first_name_fixed')->textInput() ?>

    <?= $form->field($model, 'select_1528809495736')->dropDownList(Client::select_1528809495736(),['prompt'=>"Select"]) ?>

    <?= $form->field($model, 'phone_fixed')->textInput() ?>

    <?= $form->field($model, 'address_fixed')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'last_name_fixed')->textInput() ?>

    <?= $form->field($model, 'date_1528810280939')->widget(\kartik\date\DatePicker::classname(), [
                                                                'model' => $model,

                                                                'options' => ['placeholder' => ''],
                                                                'pluginOptions' => [
                                                                    'todayHighlight' => true,
                                                                    'todayBtn' => true,
                                                                    'format' => 'yyyy-mm-dd',
                                                                    'autoclose' => true,
                                                                ]
                                                            ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '' : '', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
