<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\BloodGroup */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Create Blood Group';
$this->params['breadcrumbs'][] = ['label' => 'Blood Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="blood-group-create">

    <div class="ribbon">
        <span><?= Html::encode($this->title) ?></span>
    </div>

    <div class="form-container">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput([
    'maxlength' => true, 
    'placeholder' => 'Enter Blood Group Name', 
    'style' => 'text-transform: uppercase;'
])->label('Blood Group') ?>

        <div class="form-group">
            <?= Html::submitButton('Create', ['class' => 'btn btn-success btn-lg']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>

<!-- Add custom styles for ribbon and form -->
<style>
    .blood-group-create {
        margin: 10px;
    }

    .ribbon {
          width: 20%;
        position: relative;
        background-color:rgb(35, 36, 35);
        color: white;
        font-size: 18px;
        text-align: center;
        padding: 10px 10px;
        margin-bottom: 10px;
    }

    .ribbon::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 0;
        height: 0;
        border-left: 20px solid transparent;
        border-right: 20px solid transparent;
        border-bottom: 20px solidrgb(20, 20, 20);
    }

    .form-container {
        background-color: #f9f9f9;
        width: 50%;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(92, 235, 80, 0.1);
    }

    .form-group {
        margin-top: 20px;
    }

    .btn-success {
        width: 20%;
        padding: 10px;
        font-size: 18px;
        background-color:rgb(203, 209, 204);
        border-color:rgb(217, 223, 218);
        transition: background-color 0.3s ease;
    }

    .btn-success:hover {
        background-color:rgb(8, 201, 185);
    }
</style>
