<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Client;
/* @var $this yii\web\View */
/* @var $model backend\models\Client */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="client-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row margin_unset upload_cont_all">
        <h4 class="upload_label">Uploads</h4>
        <div class="row margin_unset upload_cont">
            <div class='row margin_unset'>

                <?php if(empty($model->file_1529059071527)){

                    $file_1529059071527=\Yii::$app->security->generateRandomString(8).str_replace('.','',microtime(true));
                    $model->file_1529059071527=$file_1529059071527;
                }
                ?>

                <?php echo $form->field($model, 'file_1529059071527')->hiddenInput()->label(false); ?>

                <?php echo $form->field($model, 'file_1529059071527_upload')->hiddenInput()->label(false); ?>

                <?php
                if(!$model->isNewRecord){
                    echo $form->field($model, 'attachment_ids_file_1529059071527')->hiddenInput(['id'=>'attachment_ids_file_1529059071527'])->label(false);
                    $attachmentsArrayFinalfile_1529059071527=[];

                    foreach ($model->file_1529059071527s as $upload){
                        $attachmentsArrayfile_1529059071527=[];
                        $attachmentsArrayfile_1529059071527['id']=$upload->id;
                        $attachmentsArrayfile_1529059071527['attachment']=$upload->attachment;
                        $attachmentsArrayfile_1529059071527['extension']=$upload->extension;
                        $attachmentsArrayfile_1529059071527['name']=$upload->name;
                        array_push($attachmentsArrayFinalfile_1529059071527, $attachmentsArrayfile_1529059071527);
                    }
                    echo \backend\widgets\attachmentGallery\AttachmentGallery::widget(
                        [
                            'label'=>'Attachments',
                            'attachmentArray' => $attachmentsArrayFinalfile_1529059071527,
                            'module_id'=>$model->id,
                            'cancel'=>true,
                            'uId'=>'attachment_file_1529059071527',
                            'cancelButton'=>'function onClickCancel(modelId,objectId,moduleId){
                                                     handleCancelEventMore(modelId,objectId,moduleId,"file_1529059071527");
                                                     
                                             }',
                            'imageButton'=>'function onClickImage(modelId,object){
                                                     handleImageClickEvent(modelId,object);
                                                    
                                             }',
                        ]
                    );
                }
                ?>
            </div>
            <div class='row margin_unset'>
                <?=\kato\DropZone::widget([
                    "id" => "drop_zone_new_form_project",
                    "dropzoneContainer" => "drop_zone_container_new_form_project_file_1529059071527",
                    "previewsContainer" => "drop_zone_preview_container_new_form_project_file_1529059071527",
                    "options" => [
                        "url" => \yii\helpers\Url::to(["mii/file-upload/upload-temp-file","session_id"=>$model->file_1529059071527]),
                        "paramName" => "attachment",
                        "maxFilesize" => "20",
                        "addRemoveLinks" => true,
                    ],
                    "clientEvents" => [
                        "complete" => "function(file){
                      handleFileUpload('file_1529059071527');
                    }",
                        "removedfile" => "function(file){
                    handleFileUpload('file_1529059071527');
                    }",
                        "success" => "function(data){
            
            }"
                    ],
                ]); ?>
            </div>
        </div>
    </div>


    <?= $form->field($model, 'first_name')->textInput(); ?>

    <?= $form->field($model, 'phone')->textInput(); ?>

    <?=  $form->field($model, 'address')->textarea(['rows' => 6]);?>

    <?= $form->field($model, 'last_name')->textInput(); ?>

    <!--user_id field-->
    <?php $model->user_id=yii::$app->user->id ?>

    <?= $form->field($model, 'user_id')->hiddenInput()->label(false); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '' : '', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    var init_array=['file_1529059071527'];
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
        alert($("#client-"+attr+"_upload").val());
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
    // interval
    var interval=setInterval(function(){
        $(".has-error").each(function(index,elem){
            for(var i in init_array){
                if($(elem).hasClass("field-client-"+init_array[i]+"_upload")){
                    $(elem).parents(".upload_cont_all").addClass("error_f_cont");
                }
            }
        })
    },1000);


</script>
