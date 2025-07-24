<?php

use backend\models\CaseType;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use backend\models\Applicant;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\CaseTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Quick Add');
$this->params['breadcrumbs'][] = ['label' => 'Cases / ' . $this->title];
?>

<?php
$data = Applicant::instance()->attributeLabels(); 
unset($data["id"]);
unset($data["client_id"]);



$dataCase = ArrayHelper::map( ArrayHelper::toArray(CaseType::find()->all()),'id','name');

?>
<!--WorkOnProgress-->
<div class="row">
<div class="col-md-12">
        <div class="panel panel-default card-view border-panel panel-refresh">
            <div class="refresh-container">
                <div class="la-anim-1"></div>
            </div>
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">Quick Add Case Types</h6>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(['fieldConfig' => [
                        'options' => [
                            'class' => 'form-group',
                            'id'=>'case-type-form',
                        ],
                    ],
                    ]); ?>
                    <div class="col-md-12 pl-0">
                        <div class="col-md-6">
                            <?= $form->field($model, 'name')->label('Case Types')->widget(Select2::className(), [
                                'data' => $dataCase,
                                'model' => $model,
                                
                                // 'attribute' => 'categories',
                                'language' => 'en',
                                'options' => ['placeholder' => 'Select Case Types', 'id' =>'case_type', 'required' => "required"],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'multiple' => true,
                                    'closeOnSelect' => false,
                                ],
                                ]) 
                            ?>
                        </div>
                        <div class="col-md-6">
                            <span id="err_blank" class="pt" style="color: red; display:none;">Please select case type</span>
                        </div>
                    </div>

                    <div class="col-md-12 pl-0">
                        <div class="col-md-6">
                            <?= $form->field($modelCase, 'applicant_field_key')->label('Required fields')->widget(Select2::className(), [
                                'data' => $data,
                                'model' => $model,
                                // 'attribute' => 'categories',
                                
                                'language' => 'en',
                                
                                'options' => ['placeholder' => 'Select required fields','class'=>'multiple','style'=>"height:250px",  'id'=> 'multiselect'],
                                
                                'pluginOptions' => [
                                        'allowClear' => true,
                                        'multiple' => true,
                                        'closeOnSelect' => false,
                                        'label' => false,
                                    ],
                                    
                                    ])
                                    ?>
                                    
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($modelCase, 'applicant_field_value')->label('Optional fields')->widget(Select2::className(), [
                                'data' => $data,
                                'model' => $model,
                                
                                // 'attribute' => 'categories',
                                'language' => 'en',
                                
                                'options' => ['placeholder' => 'Select non-required fields','class'=>'multiple','style'=>"height:250px", 'id'=> 'multiselect2'],
                                
                                'pluginOptions' => [
                                        'allowClear' => true,
                                        'multiple' => true,
                                        'closeOnSelect' => false,
                                        'label' => false,
                                        'language' => [
                                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                        ],
                                    ],
                                    
                                    ])
                                    ?>
                                    
                        </div>
                        </div>
                        <div class = "col-md-12 pl-2">
                            ** If no field is selected in any dropdown then all the fields will be optional    
                        </div>
                    <!-- <div class="col-md-6 mt-20">
                        <? //$this->render('multidrop')?>
                    </div> -->
                    <div class="col-md-6">
                        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'btn btn-sm btn-rounded btn-success mt-20', 'id' => 'btn_submit']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
    <style>
        .select2-selection{
            height:auto;
        }
        .select2-selection--multiple{
            height:auto;
        }
        .select2-selection__rendered{
            height:auto;
        }
    </style>
    <script>
        // $('input[placeholder="Select Case Types"]').attr('required', true);
        // $('input[placeholder="Select Case Types"]').attr('id', 'check_id');
        // var el = $('input[placeholder="Select Case Types"]');
        // el.attr('id','case_type_input');
        // $('.field-case_type').find('input').attr("required", "true");
        // $("#case_type").each(function() {
        //                         $(this).attr("required", "true");
        //                     })
        // $( window ).on( "load", function(){ 
        //     $('input[placeholder="Select Case Types"]').attr('id', "case_type_input"); 
        //     $('input[placeholder="Select Case Types"]').attr('required', true);})
        $("#btn_submit").on('click', function(e) 
        {
            $('input[placeholder="Select Case Types"]').attr('id', "case_type_input"); 
            $('input[placeholder="Select Case Types"]').attr('required', true);
        })
        
        $("#case_type").change(function() {
            if($('#case_type').val().length)
                $('#case_type_input').attr('required', false);
            else
                $('#case_type_input').attr('required', true);
        
        })
        // $( document ).ready(function() {
        //     $('input[placeholder="Select Case Types"]').attr('required', true);
        //     });
        // $("#btn_submit").on('click', function(e) {
        //     if(!$('#case_type').val().length)
        //     {
        //         $('input[placeholder="Select Case Types"]').attr('required', true);
        //         // e.preventDefault();
        //         //  $("#err_blank").each(function() {
        //         //     $(this).prop('style', 'color: red; display: block;');
        //         // });
        //     }
        //     let value = $('#case_type').val().length;
        //     console.log("Value : ", value);
        //     return;
            
            
            
            
        // });
        $('#multiselect, #multiselect2').change(function(){
            let list1 = $('#multiselect').val();
            let list2 = $('#multiselect2').val();
            $('#multiselect2').children().each(function () {
                                
                                        ($(this).attr("disabled",false));
            })
            $('#multiselect2').children().each(function () {
                                if(list1.includes($(this).attr('value')))
                                        ($(this).attr("disabled","disabled"));
            })
            $('#multiselect').children().each(function () {
                                
                                        ($(this).attr("disabled",false));
            })
            $('#multiselect').children().each(function () {
                                if(list2.includes($(this).attr('value')))
                                        ($(this).attr("disabled","disabled"));
            })
        })

    </script>
<!--DisplayCases-->


<!--/WorkOnProgress-->
