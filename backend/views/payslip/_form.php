<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use backend\models\Employee; // Ensure this model exists and is correctly referenced

/** @var yii\web\View $this */
/** @var app\models\Payslip $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="payslip-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'employee_id')->widget(Select2::class, [
        'data' => ArrayHelper::map(Employee::find()->orderBy('first_name')->all(), 'id', function ($employee) {
            return $employee->first_name . ' ' . $employee->last_name . ' (' . $employee->employee_id . ')';
        }),
        'options' => ['placeholder' => 'Select Employee'],
        'pluginOptions' => [
            'allowClear' => true,
            'width' => '100%',
        ],
    ]) ?>

    <?= $form->field($model, 'pay_period')->input('date') ?>
    <?= $form->field($model, 'basic_salary')->textInput(['type' => 'number', 'step' => '0.01']) ?>
    <?= $form->field($model, 'allowances')->textInput(['type' => 'number', 'step' => '0.01']) ?>
    <?= $form->field($model, 'deductions')->textInput(['type' => 'number', 'step' => '0.01']) ?>
    <?= $form->field($model, 'net_salary')->textInput(['type' => 'number', 'step' => '0.01']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
