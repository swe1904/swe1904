<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\CaseType;

/** @var yii\web\View $this */
/** @var backend\models\KnowledgeModule $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="knowledge-module-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'case_type_id')->hiddenInput([ 'value' => $caseTypeModel->id ])->label(''); ?>

    <?= $form->field($model, 'query')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'notes')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::$app->controller->action->id == 'create' ? 'Add' : 'Update', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
