<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DocumentRequest */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="document-request-form">

    <?php $form = ActiveForm::begin(); 
  
    ?>

   

    <?= $form->field($model, 'document_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'language_of_document')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
