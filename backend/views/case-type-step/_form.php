<?php

use backend\models\CaseTypeStep;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CaseTypeStep */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="col-md-12">
    <div class="panel panel-default card-view panel-refresh">
        <div class="panel-hading">

        </div>
        <div class="row clearfix">
            <?php $form = ActiveForm::begin(['fieldConfig' => [
                'options' => [
                    'options' => ['class' => 'form-group invisible']
                ],
            ],
            ]); ?>
            
            <div class="col-md-12">
                <?= $form->field($model, 'name', ['template' => '<label>{label}</label><div class="form-group border"><div class="form-line">{input}</div></div>'])->textInput(['maxlength' => true, 'placeholder' => 'Name'])->label(false) ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'number_of_days', ['template' => '<label>{label}</label><div class="form-group border"><div class="form-line">{input}</div></div>'])->textInput(['maxlength' => true, 'placeholder' => 'Number of days'])->label(false) ?>
            </div>

            <?php if ($model->isNewRecord) { ?>
                <div style="display: none">
                <label class="control-label custom-label" for="case_type_id">
                    <?php echo $model->getAttributeLabel('case_type_id'); ?>
                </label>
                <div class="col-md-12">
                    <?= $form->field($model, 'case_type_id')->dropDownList(\yii\helpers\ArrayHelper::map($caseType, 'id', 'name'), ['placeholder' => 'Select Case Type', 'class' => 'myselect', 'style' => 'display: none'])->label(false) ?>
                </div>
            </div>
            <?php 
                if (count($caseType) == 1) {
                    // if request for a single casetype get max order
                    $max_order = CaseTypeStep::find()->where(['case_type_id' => $caseType[0]->id])->limit(1)->orderBy('order DESC')->one();

                    if (empty($max_order)) {
                        // if no case-type-step-exist
                        echo $form->field($model, 'order')->hiddenInput(['value' => 1])->label(false);
                    } else {
                        // if case-type-steps exist
                        echo $form->field($model, 'order')->hiddenInput(['value' => $max_order->order + 1])->label(false);

                    }
                }
            }
            ?>
            <!-- --><?php /*echo $form->field($model, 'order')->textInput(['maxlength' => true,'class'=>'formInput']) */ ?>


            <div class="col-md-12">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'btn btn-rounded btn-success mr-10']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>