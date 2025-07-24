<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use backend\models\Applicant;
use yii\helpers\ArrayHelper;
// use backend\models\CaseTypeApplicantField;

$data= Applicant::instance()->attributeLabels(); 
unset($data["id"]);
unset($data["client_id"]);
// $requiredFields=  CaseTypeApplicantField::find()->where(['case_type_id' => $model->id, 'is_required' => 1])->select('case_type_id','applicant_field_key')->asArray()->all();
// $optionalFields=  CaseTypeApplicantField::find()->where(['case_type_id' => $model->id, 'is_required' => 0])->select('case_type_id','applicant_field_key')->asArray()->all();
// $requiredArr= [];
// $optionalArr= [];

// if (!empty($requiredFields)) {
//     foreach($requiredFields as $field)
//     {
//         array_push($requiredArr, $field['case_type_id']);
//     }
//     $model_case->applicant_field_key = $requiredArr;
// }

// if (!empty($optionalFields)) {
//     foreach($optionalFields as $field)
//     {
//         array_push($optionalArr, $field['case_type_id']);
//     }
//     $model_case->applicant_field_value = $optionalArr;
// }

/* @var $this yii\web\View */
/* @var $model backend\models\CaseType */
/* @var $model_case backend\models\CaseTypeApplicantField */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row clearfix">
    <div class="col-md-12">

                <div class="row clearfix">
               

                    <?php $form = ActiveForm::begin(['fieldConfig' => [
                        'options' => [
                            'options' => ['class' => 'form-group invisible']
                        ],
                    ],
                    ]); ?>
                    <div class="col-md-12 pl-0">
                            <div class="col-md-6">
                                <?= $form->field($model, 'name',
                                    ['template' =>'
                                    <div class="form-group">
                                    <label class="control-label"> Case Type </label>
                                    <div class="form-line border">{input}</div>
                                    </div>'
                                    ])->textInput(['maxlength' => true, 'placeholder' => 'Create Case Type', 'label' => 'Case Type'])
                                    ?>
                            </div>
                            <div class="col-md-2">
                        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'btn btn-rounded btn-success mt-30  m-2']) ?>
                    </div>
                        </div>
                        <!-- <div class="col-md-12 pl-0">
                        <div class="col-md-6">
                         
                                    
                            </div>
                            <div class="col-md-6">
                          
                                        
                            
                                    </div>
                        </div>
                        <div class = "col-md-12 p-2">
                            ** If no field is selected in any dropdown then all the fields will be optional    
                        </div> -->
                   

                    <?php ActiveForm::end(); ?>

         
    </div>
</div>

<style>
    form .col-md-12 {
    padding: 15px !important;
}
</style>