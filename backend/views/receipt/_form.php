<?php

use app\components\GlobalConstant;
use backend\models\Receipt;
use kartik\datecontrol\DateControl;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use backend\models\Cases;
use backend\models\CaseType;
use common\models\Client;
use backend\models\ClientEntity;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\Receipt */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END,
    'viewParams' => [
        'value' => \yii\helpers\Json::encode($model->receiptItems),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
?>
<style>
    .select2-selection__arrow{
        display: none !important;
    }
    .select2-selection__rendered{
        padding-top: 6px !important;
    }
</style>
<?php
$dateFormat =  '';
if(empty($dateFormat)){
    $dateFormat =  \backend\models\Organisation::setDefaultDate();
}else{
    $dateFormat = 'dd-MM-yyyy';
}
?>

<?php /*if(!$model->isNewRecord): */?><!--
<?php
/*    echo
    Html::dropDownList('change-receipt-status',$model->is_receipt,['-1'=>'template1','0'=>'template2','1'=>'template2'], ['prompt' => '--- Select Status---','class'=>'myselect','id'=>'change-receipt-status' ,'onchange'=>'changeReceiptStatus("'.$model->id.'",this.value)'])
    */?>
<?php /*if($model->is_receipt==-1): */?>
    <?php /*echo Html::a('Change Status', ['receipt/change-receipt-status','Receipt[id]'=>$model->id, 'Receipt[quotes]' => true], ['title' => Yii::t('app', 'Change Status Quote To Invoice'),
                            'class'=>'btn btn-success',
                            'data-method'=>'post',
                            'data-confirm'=>'Are you sure you want to change status from Quotes to Invoice ?']); */?>
<?php /*elseif($model->is_receipt==0): */?>
    <?php /*echo Html::a('To Receipt', ['receipt/change-receipt-status','Receipt[id]'=>$model->id, 'Receipt[invoices]' => true], ['title' => Yii::t('app', 'Change Status Invoice To Receipt'),
        'class'=>'btn btn-success',
        'data-method'=>'post',
        'data-confirm'=>'Are you sure you want to change status from Invoice to Receipt ?']); */?>
<?php /*//else: */?>
<!-  --><?php /*//echo Html::a('Create Receipt', ['create'], ['class' => 'btn btn-success']); */?>
<?php /*endif; */?>
<?php /*endif; */?>
<div class="row clearfix">
    <div class="col-md-12">
        <div class="">
            <div class="">
                <div class="row clearfix">

    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true,
        'fieldConfig' => [
            'options' => [
                'options' => ['class' => 'form-group invisible']
            ],
        ],]); ?>

    <div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
        <div class="col-lg-2 col-xs-2 col-sm-2 col-md-2">
            <div class="form-group">
<!--            <label class="control-label custom-label" for="change-receipt-status">-->
<!--                --><?php //echo  Yii::t('backend','Status');?>
<!--            </label>-->
            <?php if(!$model->isNewRecord) {
                $receiptType=$model->is_receipt;
            }else{$receiptType=-1;
            }
//            echo
//            Html::dropDownList('change-receipt-status',$receiptType,Receipt::getReceiptType(), ['prompt' => '--- Select Status ---','id'=>'change-receipt-status' ,'onchange'=>'$("#receipt-is_receipt").val(this.value)']);
            echo $form->field($model, 'is_receipt')->dropDownList(Receipt::getReceiptType(), ['prompt' => '--- Select Type ---','id'=>'change-receipt-status' ,'onchange'=>'$("#receipt-is_receipt").val(this.value)'])->label('Type');
            ?>
            </div>
        </div>

        <div class="col-lg-2 col-xs-2 col-sm-2 col-md-2">
            <div class="form-group">
                <?= $form->field($model, 'receipt_increment_alphabetic_part')->textInput(['maxlength' => true,])->label('Quote Alphabetic', ['id' => 'receipt-alphabetic-label']); ?>
            </div>
        </div>

        <div class="col-lg-2 col-xs-2 col-sm-2 col-md-2">
            <div class="form-group">
                <?= $form->field($model, 'receipt_increment_number_part')->textInput([])->label('Quote Number', ['id' => 'receipt-number-label']); ?>
            </div>
        </div>

        <div class="col-lg-3 col-xs-3 col-sm-3 col-md-3">
        <?= $form->field($model, 'date')->textInput([
                                     'value' => $model->isNewRecord ? date('Y-m-d') : $model->date,
                                    'readonly' => true,
                                    'style' => 'width:250px;',
                                    'class' => 'form-control',
                                ])->label('Issue Date', ['class' => 'custom-label']); ?>
        </div>
    <div style="display:none">
        <?php
            echo $form->field($model, 'is_receipt')->hiddenInput([])->label(false);
        ?>
    </div>
<!--     <div style="display:none">-->
<!--        --><?php
//            echo $form->field($model, 'vat_rate')->hiddenInput(['value' => 15])->label(false);
//        ?>
<!--    </div> -->

        <div class="col-lg-3 col-xs-3 col-sm-3 col-md-3">
            <div class="form-group">
                <?= $form->field($model, 'due_date')->widget(DateControl::classname(), [
                    'options'=>['style'=>'width:250px;', 'class'=>'form-control'],
                    'type'=>DateControl::FORMAT_DATE,
                    'displayFormat' => $dateFormat,
    //                'saveFormat' => 'Y-m-d',
                    'ajaxConversion'=>false,
                    'widgetOptions' => [
                        'pluginOptions' => [
                            'autoclose' => true
                        ]
                    ]
                ])->label('Due Date',['class'=>'custom-label']); ?>
                <?php
               // $role = array('1' => 'Cash', '2' => 'Cheque', '3' => 'Draft', '4'=>'Online Payment');
                //$role = array('5'=>'Credit Card','6'=>'Bank Transfer','7'=>'Paypal','8'=>'Cash');
                //echo $form->field($model, 'payment_mode')->dropDownList($role, ['prompt' => '- Select Payment Mode -',/*'onChange' => 'checkPaymentMode()'*/])->label('Payment Mode',['class'=>'custom-label']);
                ?>
            </div>
        </div>
    </div>
    <div class="quotes-field">
                            <div class="col-lg-12 col-xs-12 col-sm-12 col-md-12" style="padding: 0px;">

                                <div class="col-lg-3 col-xs-3 col-sm-3 col-md-3">
                                    <div class="form-group">
                                        <?= $form->field($model, 'potential_client_name')
                                            ->textInput(['maxlength' => true, 'placeholder' => 'Potential Client Name'])
                                            ->label('Potential Client Name *'); ?>
                                        <p class="help-block help-block-error"></p>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-xs-3 col-sm-3 col-md-3">
                                    <div class="form-group">
                                        <?= $form->field($model, 'potential_client_address')
                                            ->textarea(['rows' => 3, 'placeholder' => 'Address'])
                                            ->label('Address'); ?>
                                        <p class="help-block help-block-error"></p>
                                    </div>
                                </div>
      
                                <div class="col-lg-3 col-xs-3 col-sm-3 col-md-3">
                                    <div class="form-group">
                                        <?= $form->field($model, 'potential_client_email')
                                            ->textarea(['rows' => 3, 'placeholder' => 'Email'])
                                            ->label('Email *'); ?>
                                        <p class="help-block help-block-error"></p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-xs-3 col-sm-3 col-md-3">
                                    <div class="form-group">
                                    <?= $form->field($model, 'potential_client_currency')->label('Currency')->widget(Select2::className(), [
                                              'data' => $currencyArray,                                            
                                              'language' => 'en',

                                              'options' => ['placeholder' => 'Select currency',
                                                          'class'=>'multiple',
                                                          'style'=>"height:250px",
                                                        
                                                      ],
                                              'pluginOptions' => [
                                                      'allowClear' => true,
                                                     
                                                      'label' => false,
                                                  ],
                                                
                                                  ])
                                                  ?>
                                   
                                        <p class="help-block help-block-error"></p>
                                    </div>
                                </div>

                            </div>
                        </div>
<div class="other-field" style="display: none;">
    <div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
<!--        <div class="col-lg-3 col-xs-3 col-sm-3 col-md-3">-->
<!--            <div class="form-group">-->
<!--                --><?php
//                /*if($model->isNewRecord){
//                    $model->currency_id = $organisationModel->currency_id;
//                }*/
//
//                if ($model->isNewRecord && $organisationModel !== null) {
//                    $model->currency_id = $organisationModel->currency_id;
//                }
//
//
//
//
//
//
//
//
//
//
//    //            echo $form->field($model, 'currency_id')->widget(\kartik\select2\Select2::classname(), [
//    //                'data' => $currencyArray,
//    //                'options' => ['placeholder' => 'Select Currency'],
//    //                'pluginOptions' => [
//    //                    'allowClear' => true
//    //                ],
//    //            ]);
//
//                echo $form->field($model, 'currency_id')->dropDownList($currencyArray, ['prompt' => '- Select Currency -'])->label('Currency',['class'=>'custom-label']);
//                ?>
<!--            </div>-->
<!--        </div>-->
<!--        <div class="col-lg-3 col-xs-3 col-sm-3 col-md-3">-->
<!--            <div class="form-group">-->
<!--                --><?php
//                    if ($model->isNewRecord) {
//                        $model->vat_rate = 15;
//                    }
                //    echo $form->field($model, 'vat_rate')->textInput(['type' => 'number', 'min' => '0', 'max' => '100'])->label('Vat (in percent)', ['class' => 'custom-label']);
//                ?>
<!--            </div>-->
<!--        </div>-->
        <div class="col-lg-4 col-xs-4 col-sm-4 col-md-4">
            <div class="form-group">
                <?php
               echo $form->field($model, 'client_id')->widget(\kartik\select2\Select2::classname(), [
                   'data' => $clientArray,
                   'options' => [
                                    'placeholder' => 'Select Client',
                                    'onchange'=>'clientChange()'
                            ],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
               ]);
                // echo $form->field($model, 'client_id')->dropDownList($clientArray, ['prompt' => '- Select Client -', 'disabled' => $model->isNewRecord ? false : true])->label('Client',['class'=>'custom-label']);
                        
                ?>
            </div>
        </div>
        <div class="col-lg-4 col-xs-4 col-sm-4 col-md-4">
            <div class="form-group">
                <?php
                $clientEntityArr = [];
            
                if(!$model->isNewRecord)
                {
                    $clientEntityArr = ArrayHelper::map(ClientEntity::find()->where(['client_id'=>$model->client_id])->all(),'id','name');
                    $model->client_entity = $model->case->clientEntity->id;
                }
                
               echo $form->field($model, 'client_entity')->widget(\kartik\select2\Select2::classname(), [
                   'data' => $clientEntityArr,
                   'options' => [
                            'placeholder' => 'Select Client Entity',
                            'onchange'=>'clientEntityChange()'

                        ],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
               ]);
                // echo $form->field($model, 'client_entity')->dropDownList([], ['prompt' => '- Select Client Entity-', 'disabled' => $model->isNewRecord ? false : true])->label('Client Entity',['class'=>'custom-label']);
                ?>
            </div>
            <div class="fa fa-circle-o-notch fa-spin" id="loading-div-client_entity" style="display:none;" ></div>
        </div>
        <div class="col-lg-4 col-xs-4 col-sm-4 col-md-4">
            <div class="form-group">
                <?php
                $casesArr = [];
                if(!$model->isNewRecord)
                {
                                        
                        $cases = Cases::find()->where([
                            'client_entity' => $model->client_entity,
                            'case_status' => GlobalConstant::CASE_STATUS_SENT_FOR_BILLING,
                        ]);
                  
                    if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_MANAGER)
                        $cases->andWhere(['case_manager_id' => Yii::$app->user->identity->id]);
                    else if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_WORKER)
                        $cases->andWhere(['assigned_to' => Yii::$app->user->identity->id]);
                    else //roles other than Case Manager and worker can create bills for their organisation's cases only
                        $cases->andWhere(['organisation_id' => Yii::$app->user->identity->organisation_id]);
                    $casesArr = ArrayHelper::map($cases->all(),'id','case_number');

                }
               echo $form->field($model, 'case_id')->widget(\kartik\select2\Select2::classname(),[
                   'data' => $casesArr,
                   'options' => [
                                'placeholder' => 'Select Case',
                                'onchange'=>'caseChange()'
                            ],
                   'pluginOptions' => [
                       'allowClear' => true
                   ],
               ]);
                // echo $form->field($model, 'client_entity')->dropDownList([], ['prompt' => '- Select Client Entity-', 'disabled' => $model->isNewRecord ? false : true])->label('Client Entity',['class'=>'custom-label']);
                ?>
            </div>
            <div class="fa fa-circle-o-notch fa-spin" id="loading-div-case_id" style="display:none;" ></div>
        </div>
    </div>
       <br>
        <br>
    <div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
        <!--Commented-pangea-->
       <!-- <div class="col-lg-6 col-xs-6 col-sm-6 col-md-6">
            <?/*= $form->field($model, 'description')->textarea(['rows'=>5]) */?>
        </div>-->
        <!--Commented-pangea end-->
        <!--Commented-pangea-->
        <!--     <div class="col-lg-6 col-xs-6 col-sm-6 col-md-6">
            <?php
        /*            if (isset($receiptServiceModel)) {
                        $receiptArray = array();
                        foreach ($receiptServiceModel as $value) {
                            array_push($receiptArray, $value['service_id']);
                        }
                    } else {
                        $receiptArray = array();
                    }
                    $model->service_id=$receiptArray;
                    echo $form->field($model, 'service_id')->widget(\kartik\select2\Select2::classname(), [
                        'data' => $serviceArray,
                        'options' => ['placeholder' => 'Select Service'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'multiple' => true,
                        ],
                    ]);
                    */?>
        </div>-->
        <!--Commented-pangea end-->
        <div class="col-lg-6 col-xs-6 col-sm-6 col-md-6">


            <!--Commented-pangea end-->
           <!-- <div class="col-lg-6 col-xs-6 col-sm-6 col-md-6">
                <?/*= $form->field($model, 'amount')->textInput(['maxlength' => true]) */?>
            </div>-->
            <!--Commented-pangea end-->
            <!--Commented-pangea -->
            <!--<div class="col-lg-6 col-xs-6 col-sm-6 col-md-6">
            <?/*= $form->field($model, 'actual_amount_received')->textInput()->label() */?>
            </div>-->
            <!--Commented-pangea end-->
            <!--Commented-pangea-->
      <!--      <div class="col-lg-6 col-xs-6 col-sm-6 col-md-6">
                <?php /*echo $form->field($model, 'date_received')->widget(DateControl::classname(), [
                    'type'=>DateControl::FORMAT_DATE,
                    'displayFormat' => $dateFormat,
//                'saveFormat' => 'Y-m-d',
                    'ajaxConversion'=>false,
                    'widgetOptions' => [
                        'pluginOptions' => [
                            'autoclose' => true
                        ]
                    ]
                ]); */?>
            </div>-->
            <!--Commented-pangea end-->
        </div>
    </div>
    <div class="col-lg-12 col-xs-12 col-sm-12 col-md-12" id ="cheque_div" style="display: none">
        <div class="col-lg-6 col-xs-6 col-sm-6 col-md-6">
            <!--            --><?php
            //            echo $form->field($model, 'drawn_on')->widget(\kartik\select2\Select2::classname(), [
            //                'model' => $model,
            //                'data' => $drawnArray,
            //                'options' => ['placeholder' => 'Select Drawn','onChange' => 'drawnOn()'],
            //                'pluginOptions' => [
            //                    'allowClear' => true
            //                ],
            //            ]);
            //            ?>
            <?php
            echo $form->field($model, 'drawn_on')->dropDownList($drawnArray, ['prompt' => '- Select Drawn -','class'=>'form-control','onChange' => 'drawnOn()'])->label('Drawn On',['class'=>'custom-label']);
            ?>
            <div class="col-lg-6 col-xs-6 col-sm-6 col-md-6" id="other_bank" style="display: none;">
                <?= $form->field($model, 'other_bank')->textInput(['class'=>'form-control']) ?>
            </div>
        </div>
        <div class="col-lg-6 col-xs-6 col-sm-6 col-md-6">
            <div id="payment_mode_cheque" style="display: none;">
                <?= $form->field($model, 'cheque_number')->textInput(['class'=>'form-control']) ?>
            </div>
            <div id="payment_mode_draft" style="display: none;">
                <?= $form->field($model, 'draft_number')->textInput(['class'=>'form-control']) ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
<!-- 
    Case selection dropdown field (Hidden initially)
    - Displays only when a case is available for selection
    - Includes a loading spinner while fetching cases
-->
<!-- <div class="col-lg-6 col-xs-6 col-sm-6 col-md-6">
    <div class="form-group">
      Loading spinner (Hidden by default) 
        <div class="fa fa-circle-o-notch fa-spin loading-div" style="display:none;"></div>

     Case Label (Hidden initially) 
        <label id="case-id-label" class="custom-label" style="display:none;">Case</label>

        <?php
        // Check if the model is a new record
        // - If new, display an empty dropdown (hidden by default)
        // - If not new, fetch client cases and populate the dropdown

        // if ($model->isNewRecord) {
        //     echo $form->field($model, 'case_id')->dropDownList([], ['prompt' => 'Select Case', 'style' => 'display: none;'])->label(false);
        // } else {
        //     // Get client name based on model's client_id
        //     $clientName = Client::findOne($model->client_id);

        //     // Fetch cases associated with the client
        //     $cases = Cases::find()
        //         ->where(['client_name' => $clientName])
        //         ->orderBy(['created_at' => SORT_DESC])
        //         ->select(['id', 'case_number'])
        //         ->asArray()
        //         ->all();

        //     // Generate dropdown list with case numbers
        //     echo $form->field($model, 'case_id')
        //         ->dropDownList(ArrayHelper::map($cases, 'id', 'case_number'), ['prompt' => 'Select Case'])
        //         ->label('Case', ['class'=>'custom-label']);
        // }
        ?>

       Error message when no cases are found for the selected client -->
        <!-- Displayed only when necessary -->
        <!-- <p id="no-cases-error" class="has-error help-block help-block-error" style="display:none; color: #f83f37 !important;">
            <?PHP
            // Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_MANAGER || 
            // Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_WORKER 
            // ? 'No case found/is assigned to you under the selected client' 
            // : 'No cases found for the selected client' 
            ?>
        </p> 

    </div>
</div> -->

        <div class="col-lg-4 col-xs-4 col-sm-4 col-md-4">
            <div class="form-group">
                
                <?php 
                    if ($model->isNewRecord) {
                        // echo '<label id="case-type-label" class="custom-label" for="case-type" style="display:none;">Case Type</label>';
                        // echo Html::dropDownList('case-type', null, [], ['id' => 'case-type-dropdown', 'class' => 'form-control','disabled' => true, 'style' => 'display:none;']); 
                        echo $form->field($model, 'case_type')->widget(\kartik\select2\Select2::classname(), [
                            'data' => [],
                            'options' => [
                                'placeholder' => 'Case Type',
                                'disabled' => true
                        ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                         
                    } else {
                        $caseID = $model->case_id;
                        $case = Cases::findOne($caseID);
                        $caseTypeID = $case->case_type_id;
                        $caseType = CaseType::findOne($caseTypeID);

                        echo '<label id="case-type-label" class="custom-label" for="case-type">Case Type</label>';
                        echo Html::dropDownList('case-type', null, [$caseTypeID => $caseType->name], ['id' => 'case-type-dropdown', 'class' => 'form-control','disabled' => true, 'style' => 'display:block;']); 
                    }
                ?>
            </div>
            <div id="loading-div-case_type" class="fa fa-circle-o-notch fa-spin" style="display:none;"></div>
        </div>
        <div class="col-lg-4 col-xs-4 col-sm-4 col-md-4">
            <div class="form-group">
                <div id="currency-id-div" style="display:none;">
                <?php 
                        if(!$model->isNewRecord)
                        {
                            $model->currency_id_display = $model->currency_id;
                        }
                
                        echo $form->field($model, 'currency_id')->widget(\kartik\select2\Select2::classname(), [
                            'data' => $currencyArray,
                            
                            'options' => [
                                'placeholder' => 'Currency',
                                // 'disabled' => true,
                                'style' =>"display:none;"
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);?>
                        </div>
                        <div id="currency-id-display-div">
                        <?php  echo $form->field($model, 'currency_id_display')->widget(\kartik\select2\Select2::classname(), [
                            'data' => $currencyArray,

                            'options' => [
                                'placeholder' => 'Currency',
                                'disabled' => true,
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);

                ?>
                </div>
            </div>
            <div id="loading-div-currency_id" class="fa fa-circle-o-notch fa-spin" style="display:none;"></div>
        </div>
        <!-- PO NUMBER -->
        <div class="col-lg-4 col-xs-4 col-sm-4 col-md-4">
    <div class="form-group">
        <?php
            echo $form->field($model, 'po_number', [
                'template' => "{label}\n{input}\n{hint}\n{error}",  
            ])->textInput([
                
                'readonly' => false, // Set as read-only since it's for display
                'placeholder' => 'PO Number'
            ])->label('PO Number', ['class' => 'custom-label']);
        ?>
    </div>
</div>
        <div class="col-lg-4 col-xs-4 col-sm-4 col-md-4">
            <div class="form-group" style="display:none">

                <?php
                    if ($model->isNewRecord) {
                        // echo '<label id="case-type-label" class="custom-label" for="case-type" style="display:none;">Case Type</label>';
                        // echo Html::dropDownList('case-type', null, [], ['id' => 'case-type-dropdown', 'class' => 'form-control','disabled' => true, 'style' => 'display:none;']);
                        // echo $form->field($model, 'case_type')->widget(\kartik\select2\Select2::classname(), [
                        //     'data' => [],
                        //     'options' => [
                        //         'placeholder' => 'Case Type',
                        //         'disabled' => true
                        // ],
                        //     'pluginOptions' => [
                        //         'allowClear' => true
                        //     ],
                        // ]);

                        echo $form->field($model, 'vat_rate_display')->textInput(['disabled'=>true,'placeholder'=>'VAT Rate','id'=>'vat-type-display-input'])->label('Vat Rate');
                        echo $form->field($model, 'vat_type')->hiddenInput(['id'=>'vat-type-input'])->label(false);
                        echo $form->field($model, 'vat_rate')->hiddenInput(['id'=>'vat-rate-input'])->label(false);

                    }
                    else
                    {
                        $vatType = '';
                        $vatRate = '';
                        $vatDisplay = 'NA';

                        if($model->vat_type && $model->vat_rate)
                        {
                            $vatType = $model->vat_type;
                            $vatRate = $model->vat_rate;
                            $vatDisplay = $model->vat_rate;

                        }
                        else
                        {
                            $organisation = $model->case->organisation;

                            if($organisation->vat_type && $organisation->vat_rate)
                            {
                                $vatType = $organisation->vat_type;
                                $vatRate = $organisation->vat_rate;
                                $vatDisplay = $organisation->vat_rate;

                            }
                            // else
                            //     Yii::$app->session->setFlash('error', 'VAT type or rate is not configured. Kindly '.(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN?'configure vat type and rate in organisation setup.':'contact admin of organisation "'.$organisation->name.'"'));


                            // echo $form->field($model, 'vat_rate_display')->textInput(['disabled'=>true,'value' => $vatDisplay])->label('Vat Rate');
                        }

                        echo $form->field($model, 'vat_rate_display')->textInput(['disabled'=>true,'value' => $vatDisplay,'id'=>'vat-type-display-input'])->label('Vat Rate');
                        echo $form->field($model, 'vat_type')->hiddenInput(['value' => $vatType, 'id'=>'vat-type-input'])->label(false);
                        echo $form->field($model, 'vat_rate')->hiddenInput(['value' => $vatRate, 'id'=>'vat-rate-input'])->label(false);
                    }
                ?>
            </div>
            <div id="loading-div-vat_rate" class="fa fa-circle-o-notch fa-spin" style="display:none;"></div>
        </div>
    </div>
    </div>

    <div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
        <?php
        echo $this->render('_formReceiptItem', [
            'row' => \yii\helpers\ArrayHelper::toArray($model->receiptItems),
            // 'row' => []
            'orgVatRate' => $organisationModel->vat_rate,
        ]);
        ?>
    </div>
    <div class="col-lg-12 col-xs-12 col-sm-12 col-md-12 mb-3">
        <?php
        echo $form->field($model, 'note')->textarea([
            'id' => 'receipt-note',
            'rows' => 3,
            'class' => 'form-control',
            'placeholder' => 'Enter note here...',
            'style' => 'resize: none; height: 100px; width: 100%;'
        ])->label('Note', ['class' => 'font-weight-bold']);
        ?>
    </div>
        <!-- <div class="col-lg-6 col-xs-6 col-sm-6 col-md-6"></div> -->
        <div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
            <div style="padding-right: 10px; padding-left:10px;">
                <div style="float: right; margin-right: 20%; margin-top: 3px;"><strong>Grand Total</strong>: <input type="text" id="receipt-grand-total" value="" disabled></div>
            </div>    
        </div>

    <div class="form-group text-center">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-rounded btn-success mr-10' : 'btn btn-rounded btn-success mr-10']) ?>
    </div>

    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if(Yii::$app->controller->action->id=='create'){
    $numberUrl = Yii::$app->urlManager->createUrl("receipt/get-receipt-number");
    $alphabeticUrl = Yii::$app->urlManager->createUrl("receipt/get-receipt-alphabetic");
    $script = <<< JS
   $(function(){
    // $('#receipt-date').datepicker().datepicker('setDate', 'today');
    $.ajax({
                    url: "$numberUrl",
                    type:"GET",
                    success: function(data){
                    $('#receipt-receipt_increment_number_part').val(data);
                     $('#receipt-receipt_increment_number_part').focus();
                    }
                })
                $.ajax({
                    url: "$alphabeticUrl",
                    type:"GET",
                    success: function(data){
                    $('#receipt-receipt_increment_alphabetic_part').val(data);
                     $('#receipt-receipt_increment_alphabetic_part').focus();

                    }
                })
   })

JS;
    $this->registerJs($script, yii\web\View::POS_READY);
}
/*if payment mode is cheque*/
if(Yii::$app->controller->action->id=='update'){
    $script2 = <<< JS
   $(function(){
   var receipt_mode = $("#receipt-payment_mode").val();
   var bankId = $("#receipt-drawn_on").val();
     if(receipt_mode=='2'){
     $("#cheque_div").show();
     $("#payment_mode_cheque").show();
     $("#payment_mode_draft").hide();
      }
    if(receipt_mode=='3'){
    $("#cheque_div").show();
     $("#payment_mode_draft").show();
     $("#payment_mode_cheque").hide();
      }
    if(bankId=='8'){
      $("#other_bank").show();
      }
   })
JS;
    $this->registerJs($script2, yii\web\View::POS_READY);
}

?>
<script>
    $('#change-receipt-status').on('change', function() {
        $('#receipt-alphabetic-label').text($(this).find('option:selected').text() + ' Alphabetic');
        $('#receipt-number-label').text($(this).find('option:selected').text() + ' Number');
    })


    $(document).on('beforeSubmit', function() {
        $('#receipt-client_id').attr('disabled', false);
        $('.section-quantity').each(function() {
            if (!$(this).val()) {
                $(this).val(1);
            }
        })
        $('.section-price').each(function() {
            if (!$(this).val()) {
                $(this).val(0);
            }
        })
    })


    function clientChange1() {

        var clientId = $('#receipt-client_id').val();
        emptyDropDown(['client_entity','case_id','case_type']);
        resetCurrencyDropdown();
        resetVatInputs();
        resetServicesSection();
        enableLoading('client_entity');
        alert(clientId);
        if(!clientId)
        {
            disableLoading('client_entity');
            return;
        }

        $.ajax({
                url: '../helper/get-client-entities',
                data: "clientId="+clientId,
                type: 'GET',
                dataType: 'json',
                success: function(data, textStatus) {
                    // console.log("Client Enttities : ",data);

                    if(data)
                    {

                        var jsondata = JSON.parse(JSON.stringify(data));

                        var keys= Object.keys(jsondata);

                        $('#receipt-client_entity').append(`<option value="" selected disabled>Select Client Entity</option>`);
                        keys.forEach((key)=>{
                            $('#receipt-client_entity').append(`<option value="${key}">
                                            ${jsondata[key]}</option>`);
                        });
                    }
                    disableLoading('client_entity');
                },
        });
        
    }
    function clientChange() {
    var clientId = $('#receipt-client_id').val();
    
    emptyDropDown(['client_entity', 'case_id', 'case_type']);
    resetCurrencyDropdown();
    resetVatInputs();
    resetServicesSection();
    enableLoading('client_entity');

    if (!clientId) {
        disableLoading('client_entity');
        return;
    }

    $.ajax({
        url: '<?= Yii::$app->urlManager->createUrl(["receipt/get-client-entities"]) ?>', // Use Yii2 URL Manager
        data: { clientId: clientId }, // Send data as an object
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data) {
                $('#receipt-client_entity').empty().append('<option value="" selected disabled>Select Client Entity</option>');
                data.forEach((entity) => {
                    $('#receipt-client_entity').append(`<option value="${entity.id}">${entity.name}</option>`);
                });
            }
            disableLoading('client_entity');
        },
        error: function(xhr, status, error) {
            console.error("Error fetching client entities:", xhr.responseText);
            disableLoading('client_entity');
        }
    });
}


    function clientEntityChange() {

        var clientEntityId = $('#receipt-client_entity').val();
        emptyDropDown(['case_id','case_type']);
        resetCurrencyDropdown()
        resetVatInputs();
        resetServicesSection()
        enableLoading('case_id');
        
        // alert(clientEntityId);
        // if(!clientEntityId)
        // {
        //     disableLoading('case_id');
        //     return;
        // }

        $.ajax({
                url: '<?= Yii::$app->urlManager->createUrl(["receipt/get-client-entity-cases"]) ?>',
                data: "clientEntityId="+clientEntityId,
                type: 'GET',
                dataType: 'json',
                success: function(data, textStatus) {
                    
                    if(data)
                    {
                           
                        var jsondata = JSON.parse(JSON.stringify(data));
                       
                        var keys= Object.keys(jsondata);
                        
                        $('#receipt-case_id').append(`<option value="" selected disabled>Select Case</option>`);
                        keys.forEach((key)=>{
                            $('#receipt-case_id').append(`<option value="${key}">
                                            ${jsondata[key]}</option>`); 
                        });
                    }
                    disableLoading('case_id');
                },
        });
        
    }
    var vatRateValue=null;

    function caseChange() {

        var caseId = $('#receipt-case_id').val();
        var vatRate =    $('#hiddenVatRate').val();
        
        resetCurrencyDropdown();
        resetVatInputs();
        resetServicesSection()
        emptyDropDown(['case_type']);
        enableLoading('case_type');
        $('#loading-div-currency_id').show();
        $('#loading-div-vat_rate').show();
        if(!caseId)
        {
            disableLoading('case_type');
            $('#loading-div-currency_id').hide();
            $('#loading-div-vat_rate').hide();
            return;
        }
        // get vat_type and vat_rate of organisation by which the case is created
        $.ajax({
            url: '<?= Yii::$app->urlManager->createUrl(["receipt/get-vat-type-and-vat-rate-of-org-of-case"]) ?>',

data: 'caseId='+caseId,
type: 'GET',
dataType: 'json',
// beforeSend: function() {
//     $('.loading-div-case-type').attr('style', 'display:inline-block;');
// },
success: function(response) {

    if(response.code == 1)
    {
        let vatData = response.vatData;
        $('#vat-type-display-input').val(vatData.vatRate).change();
        
        $('#vat-type-input').val(vatData.vatType).change();
        $('#vat-rate-input').val(vatData.vatRate).change();
        $('#hiddenVatRate').val(vatData.vatRate); 
        vatRateValue =  vatData.vatRate;
    }
    else
        toastr.error(response.message);
    $('#loading-div-vat_rate').hide();
}
})


        $.ajax({
            url: '<?= Yii::$app->urlManager->createUrl(["receipt/get-case-type-of-case"]) ?>',
            data: 'caseId='+caseId,
            type: 'GET',
            dataType: 'json',
            // beforeSend: function() {
            //     $('.loading-div-case-type').attr('style', 'display:inline-block;');
            // },
            success: function(data) {
               
                // $('.loading-div-case-type').attr('style', 'display:none;');
                disableLoading('case_type');
                $('#receipt-case_type').html('<option value=' + data.id + '>' + data.name + '</option>');
                $('#receipt-case_type').attr('disabled', true);
                
                
                var clientId = $('#receipt-client_id').val(); 
                var clientEntityId = $('#receipt-client_entity').val();
                var caseTypeId = data.id;
                data = {
                            caseId:caseId,
                            clientId: clientId,
                            clientEntityId:clientEntityId,
                            caseTypeId:caseTypeId
                        };
                        
                $.ajax({
                    url: '<?= Yii::$app->urlManager->createUrl(["receipt/get-services"]) ?>',
                    data: data,
                    type: 'GET',
                    dataType: 'json',
                    // beforeSend: function() {
                    //     $('.loading-div-case-type').attr('style', 'display:inline-block;');
                    // },

                    success: function(response) {
                        $('#loading-div-currency_id').hide();
                        if(response.code == 1)
                        {
                            // console.log("Services : ",response.services);
                            // console.log("Case Type Price :",response.caseTypePrice);
                            $("#receipt-currency_id").val(response.currency.id).change();
                            $("#receipt-currency_id_display").val(response.currency.id).change();

                            //console.log("Currency Value after: ",$("#receipt-currency_id").val());
                            var services = response.services;
                            var existingservicesArr = [];
                            index = 0;
                            if(services)
                            {
                                services.forEach(function(service) {
                                    temp = [
                                        {name: 'ReceiptItem-1['+index+'][id]', value: ''},
                                        {name: 'ReceiptItem-1['+index+'][section_id]', value: 1},
                                        {name: 'ReceiptItem-1['+index+'][description]', value: service.service_name},
                                        {name: 'ReceiptItem-1['+index+'][quantity]', value: 1},
                                        {name: 'ReceiptItem-1['+index+'][price]', value:service.price},
                                        {name: 'ReceiptItem-1['+index+'][vat_rate]', value:vatRateValue},
                                        {name: 'Children['+index+'][id]', value: ''}
                                    ];
                                    existingservicesArr = [...existingservicesArr.slice(), ...temp];

                                        index++;
                                });
                                //  Passing all the services components as array in the format being received by the function.
                            //  This function can be used with same parameters if services are to be loaded in the beginning. The function behaves differently when parameters are passed differently
                            addRowReceiptItem(1,vatRate, null, 1, null,existingservicesArr);
                            }
                            else
                            {
                                $("#receipt-currency_id").val(response.currency.id).change();
                                $("#receipt-currency_id_display").val(response.currency.id).change();
                                toastr.error(response.message);   
                            }
                        }
                        else
                        {
                            toastr.error(response.message);
                            // $("#receipt-currency_id").attr('disabled',false);
                            $("#currency-id-display-div").hide();    
                            $("#currency-id-div").show();    
                        }
                    }
                })
                
            }
        })

        

        


    }
     $('.vat-type').each(function() {
                let vatRate = $('#hiddenVatRate').val(); // Get the hidden VAT rate value
                let vatType = $(this).data('selected-vat-type'); // Custom attribute to identify the selected type
                if (vatType) {
                    $(this).val(vatType);
                    updateDropdownDisplay($(this), vatType, vatRate);
                }
            });
 // Function to update dropdown text display
 function updateDropdownDisplay(dropdown, vatType, vatRate) {
            let vatTypeLabel = "";
            if (vatType == '0') {
                vatTypeLabel = "STANDARD RATE";
            } else if (vatType == '1') {
                vatTypeLabel = "ZERO-RATED";
                vatRate = "0.00";
            } else if (vatType == '2') {
                vatTypeLabel = "EXEMPTED";
                vatRate = "0.00";
            }
            vatRate = parseFloat(vatRate).toFixed(2);
            let displayText = vatTypeLabel + " - " + vatRate + " %";
            dropdown.find('option:selected').text(displayText);
        }


    function enableLoading(inputType){
        $('#receipt-'+inputType).html("");
        $('#receipt-'+inputType).prop('disabled', true);
        $('#loading-div-'+inputType).show();
    } 
    function disableLoading(inputType){

        $('#receipt-'+inputType).prop('disabled', false);
        $('#loading-div-'+inputType).hide();
    } 
    function emptyDropDown(dropdownArr){
        dropdownArr.forEach(function(item) {
            $('#receipt-'+item).html("");
            })
    }

    function resetCurrencyDropdown(){
        $('#receipt-currency_id').val('').change();
        $('#receipt-currency_id_display').val('').change();
        // $('#receipt-currency_id').attr('disabled', true);
        $("#currency-id-div").hide();    
        $("#currency-id-display-div").show();
    }

    function resetVatInputs(){
        $('#vat-type-display-input').val('');
        $('#vat-type-input').val('');
        $('#vat-rate-input').val('');
    }

    function resetServicesSection(){
        $('#section-1-container').html('No data found');
    }
    

    function checkPaymentMode() {
        $("#cheque_div").hide();
        $("#payment_mode_cheque").hide();
        $("#payment_mode_draft").hide();
        var $receipt_mode = $("#receipt-payment_mode").val();
        if ($receipt_mode == '2') {
            $("#cheque_div").show();
            $("#payment_mode_cheque").show();
        }else if($receipt_mode == '3'){
            $("#cheque_div").show();
            $("#payment_mode_draft").show();
        }
        else {
            $("#cheque_div").hide();
        }
    }
    function drawnOn(){
        $("#other_bank").hide();
        var $bankId = $("#receipt-drawn_on").val();
        if ($bankId == '8') {
            $("#other_bank").show();
        }
    }
    function toggleFields(selectedValue) {
            // console.log('selectedValue',selectedValue);
            const dueDateLabel = document.querySelector('label[for="receipt-due_date"]');
            // Reset fields to be hidden initially
            $('.quotes-field').hide();
            $('.other-field').hide();

            // Check for selected value and perform actions accordingly
            if (selectedValue == -1) {
                // For "quote" type
                dueDateLabel.textContent = 'Expiry Date';
                $('#selected-receipt-type').val('quote');

                // Show quotes-field and hide other-field
                $('.quotes-field').show();

                $('.other-field').hide();

            } else {
                if (selectedValue == 1) {
                    // For "invoice" type
                    $('#selected-receipt-type').val('receipt');
                    // Show other-field and hide quotes-field if needed
                    $('.quotes-field').hide();

                    $('.other-field').show();
                } else {
                    // For "receipt" type
                    dueDateLabel.textContent = 'Due Date';
                    $('#selected-receipt-type').val('invoice');
                    // Show other-field and hide quotes-field
                    $('.quotes-field').hide();
                    $('.other-field').show();
                }
            }
        }
     

         $(document).ready(function() {
            var typeValue = $('#change-receipt-status').val();

            if(typeValue == 0 || typeValue == 1) {

                $('.quotes-field').hide();

                    $('.other-field').show();
            }
            else{
                $('.quotes-field').show();

                    $('.other-field').hide();
            }
            toggleFields(typeValue);
              
            $('#change-receipt-status').on('change', function() {
                {
                    var selectedValue = $(this).val();
                    console.log('selectedValue' ,selectedValue);
                    $('#receipt-is_receipt').val($(this).val());
                   
                    $('#receipt-is_receipt').val(selectedValue);

                    toggleFields(selectedValue);
                }
            });
            // var selectedValue = $('#change-receipt-status').val();
            // $('#change-receipt-status').on('change' , function(){
            //     alert('arguments passed' , selectedValue);

            // });

         });
</script>
<?php  $changeStatusUrl = Yii::$app->urlManager->createUrl("receipt/change-receipt-status");?>
<script>
    $(function(){
        setTotalSums();
    });

    //function changeReceiptStatus(id,value){
//    $.ajax({
//        url: "<?//=$changeStatusUrl;?>//",
//        type:"post",
//        data: {
//            'id': id,
//            'value': value
//        },
//        success: function(data){
//            if(data.status==1){
//                alert('Status changed.');
//            }
//            else  alert('Something went wrong.');
//
//        }
//    })
//}

</script>