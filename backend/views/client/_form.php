<?php
use app\components\GlobalConstant;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Client;
use backend\models\Country;
use backend\models\FileUpload;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use kartik\select2\Select2;

/* @var $this yii\web\View */

/* @var $model backend\models\Client */
/* @var $form yii\widgets\ActiveForm */
// var_dump($organisations);
//$model->organisations = [41,42];
if(!$model->isNewRecord)
{
    $model->selectedOrganisations = array_map(fn($org) => $org->organisation_id, $model->organisations);
}
?>

<style>
    .select2-selection__arrow{
        display: none !important;
    }
    .select2-selection__rendered{
        padding-top: 6px !important;
    }
</style>

<!-- <div class="col-md-6 col-md-offset-3"> -->
<!-- <div class="col-md-6 col-md-offset-3"> -->

<!-- <div class="client-form"> -->
    
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'selectedOrganisations')->label('Northman Entities')->widget(Select2::className(), [
                                    'data' => $organisations,
//                                    'model' => $model,
                                    // 'attribute' => 'categories',
                                
                                    'language' => 'en',
                                
                                    'options' => ['placeholder' => 'Select northman entities','class'=>'multiple','style'=>"height:250px",  'id'=> 'multiselect', 'onchange'=>'dropDownChange()'],
                                    'pluginOptions' => [
                                            'allowClear' => true,
                                            'multiple' => true,
                                            'closeOnSelect' => false,
                                            'label' => false,
                                        ],
                                    
                                        ])->error(['message' => 'Please select at least one northman entity.'])
                                        ?>

<?= $form->field($model, 'client_name')->textInput(['class'=>'form-control border']); ?>

<?php 
// Uncomment this line to enable the country field with a basic text input field
// $form->field($model, 'country')->textInput(['class'=>'form-control border']); 

// The following code is for selecting a country from a dropdown using Select2 widget
// Uncomment it to enable this feature
/*
$form->field($model, 'country')->label('Country')->widget(Select2::className(), [
    'data' => ArrayHelper::map(Country::find()->all(), 'country_name', 'country_name'),
    'language' => 'en',
    'options' => [
        'placeholder' => 'Select country',
        'class' => 'multiple',
        'style' => "height:250px",
    ],
    'pluginOptions' => [
        'allowClear' => true,
        'label' => false,
    ],
]);
*/

// Uncomment the following line to enable the email input field
// $form->field($model, 'email')->textInput(['class'=>'form-control border']); 

// Uncomment the following line to enable the phone input field
// $form->field($model, 'phone')->textInput(['class'=>'form-control border']); 

// Uncomment this to enable the address input field as a textarea
// $form->field($model, 'address')->textarea(['rows' => 6, 'class' => 'form-control border']); 

// The following line is commented out for a custom text field input (remove comment to enable it)
// $form->field($model, 'text_1570532600638')->textInput(['class'=>'form-control border']); 

// Uncomment this to enable the Company TRN input field
// $form->field($model, 'text_1578126561394')->textInput(['class'=>'form-control border'])->label('Company TRN'); 

// Check if additional_attachments is empty, if true generate a random string for attachments
/*
if (empty($model->additional_attachments)) {
    $additionalAttachments = \Yii::$app->security->generateRandomString(8) . str_replace('.', '', microtime(true));
    $model->additional_attachments = $additionalAttachments;
}
*/

// Uncomment to include hidden input for the model ID
// $form->field($model, 'id')->hiddenInput()->label(false); 

// Uncomment to include hidden input for additional attachments
// $form->field($model, 'additional_attachments')->hiddenInput()->label(false); 

// The following section is for uploading files (commented out). Uncomment to enable it
/*
$dataProviderRows = FileUpload::find()->where(['file_id'=>$model->additional_attachments])->all();

// Panel for displaying attachments (uncomment to display the attachment gallery)
$attachmentsArrayFinalfile_1609222030883 = [];
foreach ($dataProviderRows as $upload) {
    $attachmentsArrayfile_1609222030883 = [];
    $attachmentsArrayfile_1609222030883['id'] = $upload->id;
    $attachmentsArrayfile_1609222030883['attachment'] = $upload->attachment;
    $attachmentsArrayfile_1609222030883['extension'] = $upload->extension;
    $attachmentsArrayfile_1609222030883['name'] = $upload->name;
    array_push($attachmentsArrayFinalfile_1609222030883, $attachmentsArrayfile_1609222030883);
}

Pjax::begin(['id' => 'attach-documents-pjax']);
echo \backend\widgets\attachmentGallery\AttachmentGallery::widget([
    'label' => 'Attachments',
    'attachmentArray' => $attachmentsArrayFinalfile_1609222030883,
    'module_id' => $model->id,
    'cancel' => true,
    'uId' => 'attachment_file_1609222030883',
    'cancelButton' => 'function onClickCancel(modelId,objectId,moduleId){ console.log("Test click"); }',
    'imageButton' => 'function onClickImage(modelId,object){ handleImageClickEvent(modelId,object); }',
]);
Pjax::end();
*/

// The DropZone file upload section (commented out) for attaching files, uncomment to enable it
/*
<div class="row margin_unset">
    <?= \common\components\DropZone::widget([
        "id" => "drop_zone_new_form_project",
        "dropzoneContainer" => "drop_zone_container_new_form_project_additional_attachments",
        "previewsContainer" => "drop_zone_preview_container_new_form_project_additional_attachments",
        "options" => [
            "url" => \yii\helpers\Url::to(["mii/file-upload/upload-temp-file", "session_id" => $model->additional_attachments]),
            "paramName" => "attachment",
            "maxFilesize" => "20",
            "addRemoveLinks" => true,
        ],
        "clientEvents" => [
            "complete" => "function(file){ handleFileUpload(); }",
            "removedfile" => "function(file){ removeFile(file); }",
            "success" => "function(data){}"
        ],
    ]); ?>
</div>
*/
?>

        <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update' , ['class' => $model->isNewRecord ? 'btn btn-rounded btn-success mr-10 mt-15' : 'btn btn-rounded btn-primary mr-10 mt-15']) ?>
        </div>

    <?php ActiveForm::end(); ?>

<!-- </div> -->
<script>
    var init_array=[];
    function handleCancelEventMore(modelId,objectId,moduleId,id){

        if(confirm("Are you sure")){
            var file_id=id;
            id="attachment_ids_"+id;
            var hiddenInputId="hidden_"+moduleId;
            var value=$("#"+id).val();
            if(value.length==0){
                value=modelId;
                $("#"+id).val(value);
            }else{
                value = value+","+modelId;
                $("#"+id).val(value);
            }
            $("a"+"#"+objectId).remove();
            handleFileUpload(file_id);
        }

    }
    function handleFileUpload(attr){
        var finalLength=$("#attachment_"+attr +" a").length+$("#drop_zone_container_new_form_project_"+attr+" .dz-preview").length;
        if(finalLength===0){
            $("#client-"+attr+"_upload").val("");
        }else{
            $("#client-"+attr+"_upload").val(1);
        }
    }
    (function(){
        for(var i in init_array){
            var attr=init_array[i];
            var finalLength=$("#attachment_"+attr +" a").length+$("#drop_zone_container_new_form_project_"+attr+" .dz-preview").length;
            if(finalLength===0){
                $("#client-"+attr+"_upload").val("");
            }else{
                $("#client-"+attr+"_upload").val(1);
            }
        }
    })();

    function removeFile(file) {
        $.ajax({
            'type': 'POST',
            'url': '<?php echo \yii\Helpers\Url::to(['cases/remove-temp-file']); ?>',
            'data': {
                sessionID: '<?php echo $model->additional_attachments; ?>',
                fileName: file.name,
            },
            'success': function(response) {
                var responseData = JSON.parse(response);
                if (responseData.code == 1) {
                    toastr.success(responseData.message);
                }
            }
        })
    }

    $('.attachment-remove').on('click', function() {
        $(this).removeClass('fa-times-circle');
        $(this).addClass('fa-circle-o-notch');
        $(this).addClass('fa-spin');
        $.ajax({
            type: 'POST',
            url: '<?php echo \yii\Helpers\Url::to(['applicant/delete-file']) ?>',
            data: {
                fileID: $(this).attr('data-file-id')
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.code === 1) {
                    $('#ex90886_4rf_4_attachment_a_tag_' + data.fileID).remove();
                    toastr.options.timeOut = 1000;
                    toastr.success(data.message);
                } else {
                    toastr.warning(data.message);
                }
            },
        })
    })
    // function deleteFile(){
    //     console.log("Cancel clicked");
    //     return;
    //     let fileID = $(this).attr('data-file-id');
    //     $(this).html('<div class="fa fa-circle-o-notch fa-spin"></div>');
    //     $.ajax({
    //       type: 'POST',
    //       url: '<?php //echo \yii\Helpers\Url::to(['applicant/delete-file']) ?>',
    //       data: {
    //         fileID: fileID
    //       },
    //       success: function (response) {
    //         let responseData = JSON.parse(response);
    //         if (responseData.code === 1) {
    //           toastr.success(responseData.message);
    //           $.pjax.reload({container: '#attach-documents-pjax', timeout: 3000, async: false});
    //         } else {
    //           toastr.error(responseData.message);
    //         }
    //         // $('.delete-file').html('<i class="fa fa-close" style="color: #d20511;"></i>');
    //       }
    //     })
    // }


    // function attachListeners() {
    //   $('.delete-file').on('click', function() {
    //     let fileID = $(this).attr('data-id');
    //     $(this).html('<div class="fa fa-circle-o-notch fa-spin"></div>');
    //     $.ajax({
    //       type: 'POST',
    //       url: '<?php //echo \yii\Helpers\Url::to(['applicant/delete-file']) ?>',
    //       data: {
    //         fileID: fileID
    //       },
    //       success: function (response) {
    //         let responseData = JSON.parse(response);
    //         if (responseData.code === 1) {
    //           toastr.success(responseData.message);
    //           $.pjax.reload({container: '#attach-documents-pjax', timeout: 3000, async: false});
    //         } else {
    //           toastr.error(responseData.message);
    //         }
    //         $('.delete-file').html('<i class="fa fa-close" style="color: #d20511;"></i>');
    //       }
    //     })
    //   })
    // }
    // $(document).ready(attachListeners)
    $(document).ready(removeFile)
    handleFileUpload()
    // $(document).on('pjax:success', attachListeners);
    $(document).on('pjax:success', removeFile);

</script>
