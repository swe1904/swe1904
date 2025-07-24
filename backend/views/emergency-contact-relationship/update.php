<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\EmergencyContactRelationship */
/* @var $form yii\widgets\ActiveForm */

$this->title = $model->isNewRecord ? 'Create Emergency Contact Relationship' : 'Update Emergency Contact Relationship';
$this->params['breadcrumbs'][] = ['label' => 'Emergency Contact Relationships', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="emergency-contact-form container mt-5">

    <!-- Form Title Section -->
    <div class="text-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <!-- Form Section -->
    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'relationship_name')->textInput(['maxlength' => true, 'placeholder' => 'Enter Relationship Name'])->label('Relationship Name') ?>

            <div class="form-group">
                <?= Html::submitButton(
                    '<i class="fas fa-save"></i> ' . ($model->isNewRecord ? 'Create' : 'Update'),
                    ['class' => 'btn btn-success btn-lg w-100']
                ) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
