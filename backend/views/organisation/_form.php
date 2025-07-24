<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use app\components\GlobalConstant;


/* @var $this yii\web\View */
/* @var $model common\models\Organisation */
/* @var $form yii\widgets\ActiveForm */
?>

<?php Pjax::begin(['id' => 'service']) ?>

<div class="col-md-12">
    <div class="panel panel-default border-panel card-view">
        <div class="panel-heading">
            <div class="pull-left">
                <h6 class="panel-title txt-dark">Edit Profile</h6>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="panel-wrapper collapse in">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-wrap">
                            <?php $form = ActiveForm::begin([
                                'id' => 'org-form',
                                'options' => ['enctype' => 'multipart/form-data'],
                                'fieldConfig' => [
                                    'options' => [
                                        'options' => ['class' => 'form-group invisible']
                                    ],
                                ],
                            ]); ?>

                            <div class="row">
                                <div class="col-lg-6 col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <?= $form->field($model, 'tagline')->textInput(['maxlength' => true]) ?>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <?= $form->field($model, 'trn')->textInput(['maxlength' => true])->label('Company Registration Number') ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                               
                                <div class="col-lg-6 col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <?= $form->field($model, 'receipt_increment_alpahabetic_part')->textInput(['maxlength' => true]) ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <?= $form->field($model, 'landline')->textInput(['maxlength' => true]) ?>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <?= $form->field($model, 'receipt_increment_number_part')->textInput(['maxlength' => true]) ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <?= $form->field($model, 'website')->textInput(['maxlength' => true]) ?>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-xs-12 col-sm-6 col-md-6">
                                    <label class="control-label" for="organisation-picture">Logo</label>
                                    <?php
                                    
                                    echo $form->field($model, 'picture')->widget(\trntv\filekit\widget\Upload::classname(), [
                                        'url' => ['avatar-upload']
                                    ])->label(false);
                                    ?>
                                </div>
                                <div class="col-lg-6 col-xs-12 col-sm-6 col-md-6">
                                    <?php echo $form->field($model, 'country_code')->textInput(); ?>
                                </div>
                            </div>

                            <div class="row">
                             
                                <div class="col-lg-6 col-xs-12 col-sm-6 col-md-6 ">
                                    <label class="control-label custom-label" for="logo_to_be_printed">
                                        <?php echo $model->getAttributeLabel('logo_to_be_printed'); ?>
                                    </label>
                                    <?php
                                    $logo_to_be_printed = array('1' => 'Yes', '0' => 'No');
                                    echo $form->field($model, 'logo_to_be_printed')->dropDownList($logo_to_be_printed, ['prompt' => 'Select'])->label(false);
                                    ?>
                                </div>
                               
                                <div class="col-lg-6 col-xs-6 col-sm-6 col-md-6">
                                    <label class="control-label" for="organisation-name">
                                        <?php echo $model->getAttributeLabel('receipt_note'); ?>
                                    </label>

                                   
                                    <?php echo $form->field($model, 'receipt_note')->textarea(['rows' => 12, 'style' => 'max-height: 42px;'])->label(false); ?>
                                </div>
                                <div class="col-lg-6 col-xs-6 col-sm-6 col-md-6">
                                        <?php echo $form->field($model, 'company_id')->textInput() ?>
                                </div>
                               
                               
                            </div>

                            <div class="form-group text-center">
                                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['id'=>'vat-rate-display-input','class' => 'btn btn-rounded btn-success mr-10']) ?>
                            </div>

                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

  
<?php
/* This modal is used to show the course details*/
Modal::begin([
    'header' => 'Update',
    'id' => 'course_details_modal',
    'size' => 'model-lg',
]);
echo "<div id='course_details_view' >
    </div>";
Modal::end();
/*course modal end here*/
?>
    <script>
        function addService() {
            $('#error_div').hide();
            $("#success_div").hide();
            $('#other_error').hide();
            var textVal = $('.service-text').val();
            if (textVal == '') {
                $('#error_div').show();
            } else {
                $.ajax({
                    url: '<?php echo Yii::$app->urlManager->createUrl('organisation/add-service') ?>',
                    type: 'post',
                    data: {serviceVal: textVal},
                    success: function (data) {
                        if (data == '1') {
                            $("#success_div").show();
                            $('#service-grid').yiiGridView('applyFilter');
                            $(".service-text").val("");
                        }
                    }
                });
                return false;
            }
        }
        function deleteService($modelId) {
            var r = confirm("Are you want to sure delete!");
            if (r == true) {
                $.ajax({
                    url: '<?php echo Yii::$app->urlManager->createUrl('organisation/delete-service') ?>',
                    type: 'post',
                    data: {id: $modelId},
                    success: function (data) {
                        console.log(data);
                        $('#service-grid').yiiGridView('applyFilter');
                    }
                });
                return false;
            }
        }
    </script>
<?php
$numberUrl = Yii::$app->urlManager->createUrl("receipt/get-receipt-number");
$alphabeticUrl = Yii::$app->urlManager->createUrl("receipt/get-receipt-alphabetic");
$id = $model->id;
$script = <<< JS
   $(function(){
   $('.service-details').on('click', function (e) {
    e.preventDefault();
    $.ajax({
            url: $(this).attr('href'),
            data: {id: $(this).attr('id')},
            success: function (data) {
                $('#course_details_modal').modal('show')
                $('#course_details_view').html(data);
            }
        })
   })
    })
    
    $('#org-form').on('beforeSubmit', function (event) {
       
        var form = $(this);

        // Get form data
        var picture = form.find('#organisation-picture').val();
        var logoToBePrinted = form.find('#organisation-logo_to_be_printed').val();
       
        if(logoToBePrinted == 1 && !picture){ //checking if logo to be printed is true and logo attached is attached 
                toastr.error('Attach Logo or choose "No" for "Logo To Be Printed on Bill"');
                return false;
        }        
        return true; //else condition
    });

    $('#vat-type-dropdown').on('change', function (e) {
        let vatType = $(this).val();
        if(vatType != 1)//vat_type is not standard rate then disable vat_value and set it to 0.00
        {
            $('#vat-rate-input').val(0.00).change();
            $('#vat-rate-div').hide();
            $('#vat-rate-display-div').show();
        }
        else
        {
            // $('#vat-rate-input').val(0.00).change();
            $('#vat-rate-display-div').hide();
            $('#vat-rate-div').show();
        }
            
   })

JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>
<?php Pjax::end() ?>