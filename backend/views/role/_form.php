<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>
 <div class="card-header bg-white border-0">
                    <h3 class="mb-0 fw-bold"><b><?= $model->isNewRecord ? 'Create Role' : 'Update Role' ?></b></h3>
                </div>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8"> <!-- Adjust width here -->
            <div class="card shadow-sm border-0">
               

                <div class="card-body pt-0">
                    <?php $form = ActiveForm::begin(); ?>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <?= $form->field($model, 'role_name')->textInput([
                                'maxlength' => true,
                                'placeholder' => 'Enter Role Name',
                                'class' => 'form-control'
                            ]) ?>
                        </div>

                        <div class="col-md-12 mb-3">
                            <?= $form->field($model, 'description')->textarea([
                                'placeholder' => 'Enter Role Description',
                                'rows' => 4,
                                'class' => 'form-control'
                            ]) ?>
                        </div>
                    </div>

                    <div class="text-end">
                        <?= Html::submitButton(
                            $model->isNewRecord ? 'Submit' : 'Update',
                            ['class' => 'btn btn-dark']
                        ) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
