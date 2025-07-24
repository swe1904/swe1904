<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Employee; // Assuming Employee model exists

$this->title = $this->context->action->id === 'create' ? 'Create Attendance' : 'Update Attendance';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card shadow p-4 mb-5 bg-white rounded">
    <h3 class="mb-4"><?= Html::encode($this->title) ?></h3>

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'needs-validation'],
        'enableClientValidation' => true,
    ]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'employee_id')->dropDownList(
                ArrayHelper::map(\backend\models\Employee::find()->all(), 'id', 'full_name'),
                ['prompt' => '-- Select Employee --']
            ) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'date')->input('date') ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'clock_in_time')->input('datetime-local') ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'clock_out_time')->input('datetime-local') ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'worked_minutes')->textInput(['type' => 'number', 'min' => 0]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'location_status')->dropDownList([
                'inside_geofence' => 'Inside Geofence',
                'outside' => 'Outside'
            ], ['prompt' => '-- Select Status --']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'ip_address')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'device_type')->dropDownList([
                'desktop' => 'Desktop',
                'mobile' => 'Mobile',
                'tablet' => 'Tablet',
                'other' => 'Other'
            ], ['prompt' => '-- Select Device --']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'manual_override')->checkbox() ?>
        </div>

        <div class="col-md-12">
            <?= $form->field($model, 'notes')->textarea(['rows' => 3, 'placeholder' => 'Reason or extra details (if any)...']) ?>
        </div>
    </div>

    <div class="form-group mt-4">
        <?= Html::submitButton('Save Attendance', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-secondary ml-2']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
