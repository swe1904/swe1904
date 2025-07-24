<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UserProfile */
/* @var $form yii\bootstrap\ActiveForm */
$this->title = Yii::t('backend', 'Edit account')
?>
<div class="col-md-12">
    <div class="panel panel-default card-view panel">
        <div class="">
        <div class="ribbon">
        <span><b><?php echo  $this->title; ?>:</b></span>
    </div>
            <div class="row clearfix">

                <?php $form = ActiveForm::begin([
                    'fieldConfig' => [
                        'options' => [
                            'options' => ['class' => 'form-group']
                        ],
                    ],
                ]); ?>

                <div class="col-md-6">
                    <?php echo $form->field($model, 'username', ['template' => '<label>{label}</label><div class="form-group"><div class="form-line">{input}</div>{error}</div>'])->textInput(['maxlength' => 255]) ?>
                </div>

                <div class="col-md-6">
    <?php echo $form->field($model, 'email', [
        'template' => '<label>{label}</label><div class="form-group"><div class="form-line">{input}</div>{error}</div>'
    ])->textInput(['maxlength' => 255, 'readonly' => TRUE]) ?>
</div>


                <div class="col-md-6">
                    <?php echo $form->field($model, 'password', ['template' => '<label>{label}</label><div class="form-group"><div class="form-line">{input}</div>{error}</div>'])->passwordInput(['maxlength' => 255])->label('New Password') ?>
                </div>

                <div class="col-md-6">
                    <?php echo $form->field($model, 'password_confirm', ['template' => '<label>{label}</label><div class="form-group"><div class="form-line">{input}</div>{error}</div>'])->passwordInput(['maxlength' => 255])->label('Confirm Password') ?>
                </div>

                <div class="col-md-6">
                    <?php echo Html::submitButton(Yii::t('backend', 'Update'), ['class' => 'btn btn-rounded btn-success mr-10']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerCss(" 
    .ribbon {
        display: flex;
        justify-content: space-between;
        background: rgb(78, 77, 77);
        color: white;
        padding: 10px;
        border-radius: 5px;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: space-between;
    }

    .form-group {
        flex: 1 1 22%;
        margin-bottom: 15px;
    }

    .form-control {
        width: 100%;
        padding: 8px;
    }

    .btn {
        width: auto;
    }

    .text-danger {
        color: red;
    }

    @media (max-width: 767px) {
        .form-group {
            flex: 1 1 100%;
        }
        .ribbon {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
    }
");
?>
