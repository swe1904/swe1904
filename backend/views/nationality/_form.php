<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? 'Create Nationality' : 'Update Nationality';
?>
   
<div class="nationality-form container-fluid mt-5">
    <div class="row justify-content-start"> <!-- Align to the left -->
        <div class="col-12">
            <!-- Left-aligned Ribbon -->
            <div class="ribbon-wrapper">
                <div class="ribbon"><?= Html::encode($this->title) ?></div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card shadow-sm border-light">
                <div class="card-body">
                    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>

                    <div class="form-group">
                        <?= $form->field($model, 'name')->textInput([
                            'maxlength' => true, 
                            'class' => 'form-control', 
                            'placeholder' => 'Enter Nationality Name'
                        ])->label(false) ?>
                    </div>

                    <div class="form-group text-center">
                        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-secondary btn-lg w-100']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add this CSS to style the form professionally -->
<style>
    .card {
        border-radius: 10px;
        border: none;
        background-color: #ffffff;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        padding: 30px;
        background-color: #f9f9f9;
    }

    .form-group input {
        font-size: 16px;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #ccc;
        box-shadow: none;
    }

    .form-group input:focus {
        border-color: #bbb;
        box-shadow: 0 0 8px rgba(187, 187, 187, 0.5);
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        padding: 12px;
        font-size: 16px;
        border-radius: 8px;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }

    .container-fluid {
        padding-left: 0;
        padding-right: 0;
        width: 100%;
    }

    /* Left-aligned Ribbon */
    .ribbon-wrapper {
        width: 100%;
        display: flex;
        justify-content: flex-start; /* Aligns to the left */
        margin-bottom: 20px;
    }

    .ribbon {
        padding: 15px 30px;
        background-color:rgb(27, 27, 27);
        color: #fff;
        font-weight: bold;
        font-size: 20px;
        text-align: center;
        border-radius: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .col-12 {
        padding-left: 15px;
        padding-right: 15px;
    }
</style>
