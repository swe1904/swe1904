<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\GlobalConstant;
use yii\helpers\ArrayHelper;
use backend\models\Client;
use backend\models\Country;
use backend\models\FileUpload;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var app\models\ClientEntity $model */
/** @var yii\widgets\ActiveForm $form */

?>
<style>
    .select2-selection__arrow{
        display: none !important;
    }
    .select2-selection__rendered{
        padding-top: 6px !important;
    }
</style>
<div class="client-entity-form">
    <div class="col-md-12">    
        <?php $form = ActiveForm::begin(); ?>

        <?php 
            if (Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT) {
                echo $form->field($model, 'client_id')->hiddenInput(['value' => Yii::$app->user->identity->client_id])->label(false);
            }
           else{
                echo $form->field($model, 'client_id')->hiddenInput(['value' => $client->id])->label(false); 
           }
        ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

        <?//= $form->field($model, 'cr_number')->textInput(['maxlength' => true]) ?>
        
        <?= $form->field($model, 'country')->label('Country')->widget(Select2::className(), [
                                    'data' => ArrayHelper::map(Country::find()->all(), 'country_name','country_name'),
//                                    'model' => $model,
                                    // 'attribute' => 'categories',

                                    'language' => 'en',

                                    'options' => [
                                            'placeholder' => 'Select country',
                                                 'class'=>'multiple',
                                                'style'=>"height:250px",
                                                // 'id'=> 'multiselect',
                                                // 'onchange'=>'dropDownChange()'
                                            ],
                                    'pluginOptions' => [
                                            'allowClear' => true,
                                            // 'multiple' => true,
                                            // 'closeOnSelect' => false,
                                            'label' => false,
                                        ],

                                        ])
                                        ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'company_vat')->textInput(['maxlength' => true]) ?>

        <?php if(empty($model->additional_attachments)){
        $additionalAttachments = \Yii::$app->security->generateRandomString(8).str_replace('.','',microtime(true));
        $model->additional_attachments = $additionalAttachments;
    }
    ?>

    <?php echo $form->field($model, 'id')->hiddenInput()->label(false); ?>

    <?php echo $form->field($model, 'additional_attachments')->hiddenInput()->label(false); ?>

    <?php //echo $form->field($model, 'additional_attachments_upload')->hiddenInput()->label(false); ?>
    <?php $dataProviderRows = FileUpload::find()->where(['file_id'=>$model->additional_attachments])->all();
        // if($dataProviderRows) {

            // $provider = new ArrayDataProvider([
            //     'allModels' => $dataProviderRows,
            //     'pagination' => [
            //         'pageSize' => 20,
            //     ],
            //   ]);
        ?>

       <!-- <div class="panel panel-default card-view border-panel panel-refresh"> -->
        <?php
    $attachmentsArrayFinalfile_1609222030883=[];

            foreach ($dataProviderRows as $upload){
                $attachmentsArrayfile_1609222030883=[];
                $attachmentsArrayfile_1609222030883['id']=$upload->id;
                $attachmentsArrayfile_1609222030883['attachment']=$upload->attachment;
                $attachmentsArrayfile_1609222030883['extension']=$upload->extension;
                $attachmentsArrayfile_1609222030883['name']=$upload->name;
                array_push($attachmentsArrayFinalfile_1609222030883, $attachmentsArrayfile_1609222030883);
            }
            Pjax::begin(['id' => 'attach-documents-pjax']);
            echo \backend\widgets\attachmentGallery\AttachmentGallery::widget(
                [
                    'label'=>'Attachments',
                    'attachmentArray' => $attachmentsArrayFinalfile_1609222030883,
                    'module_id'=>$model->id,
                    'cancel'=>true,
                    'uId'=>'attachment_file_1609222030883',
                    'cancelButton'=>'function onClickCancel(modelId,objectId,moduleId){
                                                console.log("Test click");
                                                
                                        }',
                    'imageButton'=>'function onClickImage(modelId,object){
                                                handleImageClickEvent(modelId,object);
                                                
                                        }',
                ]
            );
            Pjax::end();
        // }
        ?>
    <!-- </div> -->
        <?php
        ?><div class='row margin_unset'>
            <?=\common\components\DropZone::widget([
                "id" => "drop_zone_new_form_project",
                "dropzoneContainer" => "drop_zone_container_new_form_project_additional_attachments",
                "previewsContainer" => "drop_zone_preview_container_new_form_project_additional_attachments",
                "options" => [
                    "url" => \yii\helpers\Url::to(["mii/file-upload/upload-temp-file","session_id"=>$model->additional_attachments]),
                    "paramName" => "attachment",
                    "maxFilesize" => "20",
                    "addRemoveLinks" => true,
                ],
                "clientEvents" => [
                    "complete" => "function(file){
                        handleFileUpload();
                        }",
                    "removedfile" => "function(file){
                        removeFile(file);
                        }",
                    "success" => "function(data){
                        }"
                ],
            ]); ?>
        </div>

        

        <div class="form-group" style="margin-top: 15px;">
            <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-success btn-rounded']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

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
