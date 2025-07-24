<?php

use kartik\datecontrol\DateControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\GlobalConstant;

/* @var $this yii\web\View */
/* @var $model backend\models\CaseSteps */
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
            <!--    <label class="control-label custom-label" for="case_id">-->
            <!--        --><?php //echo  $model->getAttributeLabel('case_id');?>
            <!--    </label>-->
            <!--    --><?php //echo $form->field($model, 'case_id')->dropDownList(\yii\helpers\ArrayHelper::map(\backend\models\Cases::find()->all(),'id','case_number'),['placeholder' => 'Select Case Number','class'=>'myselect'])->label(false); ?>

            <!-- <div class="col-md-12"> -->
                <?php //echo $form->field($model, 'case_id', ['template' => '<label>{label}</label><div class="form-group border"><div class="form-line">{input}</div></div>'])->textInput(['maxlength' => true, 'value' => $_GET['CaseStepsSearch']['case_id']]) ?>
            <!-- </div> -->

            <?php //$caseType = \backend\models\Cases::findOne($_GET['CaseStepsSearch']['case_id'])->case_type_id; ?>

            <!-- <div class="col-md-12"> -->
                <!-- <label class="control-label custom-label" for="case_type_step_id"> -->
                    <?php //echo $model->getAttributeLabel('case_type_step_id'); ?>
                <!-- </label> -->

                <?php //echo $form->field($model, 'case_type_step_id')->dropDownList(\yii\helpers\ArrayHelper::map(\backend\models\CaseTypeStep::find()->where(['case_type_id' => $caseType])->all(), 'id', 'name'), ['placeholder' => 'Select Case Step', 'class' => 'border form-control'])->label(false); ?>
            <!-- </div> -->
            <?php if (Yii::$app->user->can(GlobalConstant::ROLE_ORGANISATION_MANAGER)||Yii::$app->user->can(GlobalConstant::ROLE_CASE_MANAGER)): ?>
                <div class="col-md-12" style="margin-bottom: 10px">
                    <label class="control-label custom-label" for="planned_completion_date">
                        <?php echo $model->getAttributeLabel('planned_completion_date'); ?>
                    </label>
                    <?= $form->field($model, 'planned_completion_date')->widget(DateControl::classname(), [
                        'options' => ['style' => 'width:250px;', 'class' => 'form-control'],
                        'type' => DateControl::FORMAT_DATE,
                        'displayFormat' => 'dd-MM-yyyy',
    //                'saveFormat' => 'Y-m-d',
                        'ajaxConversion' => false,
                        'widgetOptions' => [
                            'pluginOptions' => [
                                'autoclose' => true
                            ]
                        ]
                    ])->label(false); ?>
                </div>

            <?php endif; ?>
            <!--    Use check box to compe\let overwite this-->
            <!---->
            <!--    <label class="control-label custom-label" for="actual_completion_date">-->
            <!--        --><?php //echo  $model->getAttributeLabel('actual_completion_date');?>
            <!--    </label>-->
            <!--    --><?php //echo $form->field($model, 'actual_completion_date')->widget(DateControl::classname(), [
            //        'options'=>['style'=>'width:250px;', 'class'=>'form-control'],
            //        'type'=>DateControl::FORMAT_DATE,
            //        'displayFormat' => 'dd-MM-yyyy',
            ////                'saveFormat' => 'Y-m-d',
            //        'ajaxConversion'=>false,
            //        'widgetOptions' => [
            //            'pluginOptions' => [
            //                'autoclose' => true
            //            ]
            //        ]
            //    ])->label(false); ?>

            <div class="col-md-12">
                <div class="col-md-1" style="padding: 0px !important;">
                    <div class="form-group">
                        <?php if (!$model->isNewRecord && empty($model->status)): // allow only status 0 ?>
                            <label class="control-label custom-label" for="status">
                                <?php echo Yii::t('backend', 'Mark Completed:') ?>
                            </label>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!$model->isNewRecord && empty($model->status)): // allow only status 0 ?>
                    <div class="col-md-1" style="padding-left: 0px !important">
                        <?= $form->field($model, 'status')->checkbox(['label' => false]); ?>
                    </div>
                <?php endif; ?>

                <div class="col-md-1" style="padding-left: 0px !important;">
                    <div class="form-group">
                        <?php if (!$model->isNewRecord && empty($model->status)): // allow only status 0 ?>
                            <label class="control-label custom-label" for="status">
                                <?php echo Yii::t('backend', 'Send email Alert') ?>
                            </label>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if (!$model->isNewRecord && empty($model->status)): // allow only status 0 ?>
                    <div class="col-md-1" style="padding-left: 0px !important">
                        <?php echo Html::checkbox('send_email', false); ?>
                    </div>
                <?php endif; ?>
            </div>
        
            <div class="col-md-12">
                <?= $form->field($model, 'description', ['template' => '<label>{label}</label><div class="form-group border"><div class="form-line">{input}</div></div>'])->textarea(['style' => 'height: auto!important', 'rows' => '6', 'value' => $model->description]) ?>
            </div>

            <div class="col-md-12">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'btn btn-rounded btn-success mr-10']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>