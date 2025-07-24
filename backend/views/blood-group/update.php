<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\BloodGroup */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Update Blood Group: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Blood Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="blood-group-update container mt-5">

    <!-- Ribbon -->
    <div class="ribbon">
        <span><?= Html::encode($this->title) ?></span>
    </div>

    <!-- Update Form -->
    <div class="form-container">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput([
            'maxlength' => true, 
            'placeholder' => 'Enter Blood Group Name'
        ])->label('Blood Group') ?>

        <div class="form-group">
            <?= Html::submitButton('Update', ['class' => 'btn btn-primary btn-lg w-100 mb-3']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>

<!-- Custom Styling -->
<style>
    .blood-group-update {
        margin-top: 10px;
    }

    .ribbon {
        position: relative;
        width: 30%;
        background-color: rgb(35, 36, 35);
        color: white;
        font-size: 18px;
        text-align: center;
        padding: 10px 10px;
        margin-bottom: 10px;
    }

    .ribbon::before {
        content: "";
        width: 50%;
        position: absolute;
        top: 0;
        left: 0;
        width: 0;
        height: 0;
        border-left: 20px solid transparent;
        border-right: 20px solid transparent;
        border-bottom: 20px solid rgb(20, 20, 20);
    }

    .form-container {
        background-color: #f9f9f9;
        width: 40%;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-top: 20px;
    }

    .btn-primary {
        background-color: #4CAF50;
        border-color: #4CAF50;
        font-size: 16px;
        padding: 10px;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #45a049;
    }

    .btn-lg {
        font-size: 18px;
        padding: 12px;
    }

    .w-100 {
        width: 40%;
    }

    .mb-3 {
        margin-bottom: 15px;
    }
</style>
