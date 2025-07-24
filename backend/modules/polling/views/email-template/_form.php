<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model backend\modules\polling\models\EmailTemplate */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    form div.required label.control-label:after {
        content: " * ";
        color: red;
    }
</style>
<div class="show-tags" onclick="showTags()" style="padding-left: 20px;
padding-right: 20px;float: right"><span class="fa fa-tags" style="color:#fc7d07a6 "></span></div>
<div class="pull-right tags" style="display: none;background-color: #fc7d07a6;padding: 12px;">
    <h5 style="color: #fff">Available Tags</h5>
    <p style="color: #fff">Client name:= %ClientName%</p>
    <p style="color: #fff">Questionnaire Id:= %QuestionnaireId%</p>
    <p style="color: #fff">Client Id:= %ClientId%</p>
</div>
<div class="row clearfix">
    <div class="col-md-12">

        <div class="">
            <div class="">
                <div class="row clearfix">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'fieldConfig' => [
            'options' => [
                'options' => ['class' => 'form-group invisible']
            ],
        ],
    ]); ?>

<!--    --><?php //echo $form->errorSummary($model); ?>

    <div class="col-sm-6">
        <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-6">
        <?php echo $form->field($model, 'subject')->textInput(['maxlength' => true,]) ?>
    </div>
  <!--  <div class="col-sm-6">
        <?php /*echo $form->field($model, 'status_id')->dropDownList(\yii\helpers\ArrayHelper::map(\backend\modules\handyrecruiter\models\Status::find()->all(), 'id', 'name'),['prompt'=>'']) */?>
    </div>-->

<!--    <div class="col-sm-6">-->
<!--        --><?php //echo $form->field($model, 'to_email')->textInput(['maxlength' => true,'class'=>'formInput']) ?>
<!--    </div>-->
    <div class="col-sm-12">
        <?= $form->field($model, 'body')->widget(\vova07\imperavi\Widget::className(), [
            'settings' => [
                'lang' => 'en',
                'minHeight' => 200,
                'plugins' => [
                    'clips',
                    'fullscreen'
                ]
            ]
        ]);
        ?>
    </div>
 <!--   <div class="col-md-12">
        <?php
/*        echo $form->field($model, 'attachment')->hiddenInput(['value' => 'empty'])->label(false);
        echo \kato\DropZone::widget([
                'id'=>'email_template',
            'dropzoneContainer'=>'dzEmail',
            'options' => [
                'url' => \yii\helpers\Url::to(['email-template/upload','id'=>$model->id]),
                'paramName' => 'attachment',
                'maxFilesize' => '10',
                'addRemoveLinks' => true,
            ],
            'clientEvents' => [
                'complete' => "function(file){console.log(file)}",
                'removedfile' => "function(file){alert(file.name + ' is removed')}"
            ],
        ]);
        */?>
    </div>-->

    <div class="col-sm-12" style="margin-top:10px;">
        <div class="form-group">
            <?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-rounded btn-success mr-10' : 'btn btn-rounded btn-success mr-10']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
            </div>
        </div>
    </div>
</div>
<style>
    .btn-success,.btn-primary,.btn-success:hover,.btn-primary:hover{
        background-color: #fc7d07a6;
    }
</style>
<script>
    function showTags(){
        $(".tags").toggle();
    }
</script>