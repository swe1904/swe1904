<?php
use app\components\GlobalConstant;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Client;
use backend\models\CaseType;
use common\models\User;
use backend\models\CaseTypeApplicantField;
use backend\models\Applicant;


/* @var $this yii\web\View */
/* @var $model backend\models\Applicant */
/* @var $form yii\widgets\ActiveForm */


?>
<div class="col-md-6 col-md-offset-3">
<div class="applicant-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php echo $form->field($model, 'parent_id')->hiddenInput()->label(false); ?>
    <?php $caseTypeObject = new CaseType(); ?>
    <?php  if (isset($_GET['id']) && $_GET['id'] > 0) { 
        $allowedFields = $caseTypeObject->getApplicantFields($_GET['id']); 
        $allowedFieldObjects = CaseTypeApplicantField::find()->where(['case_type_id' => $_GET['id']])->all();
        if (count($allowedFields) == 0) {
            $allowedFields = array_keys($model->attributeLabels());
        }    
    }
        else {
            $allowedFields = array_keys($model->attributeLabels());;;
            //Yii::$app->response->redirect(Yii::$app->urlManager->createAbsoluteUrl(['applicant/index']));
        }
    ?>

        <?php if (in_array('select_1717755396737', $allowedFields)) { ?>
        
    <?= $form->field($model, 'select_1717755396737')->dropDownList(backend\models\Applicant::select_1717755396737(),['prompt'=>"Select",'class'=>'myselect']); ?>

        <?php } ?>
    
        <?php if (in_array('first_name', $allowedFields)) { ?>
        <?= $form->field($model, 'first_name')->textInput(['class'=>'form-control border']); ?>

        <?php } ?>
    
        <?php if (in_array('last_name', $allowedFields)) { ?>
        <?= $form->field($model, 'last_name')->textInput(['class'=>'form-control border']); ?>

        <?php } ?>
    
        <?php if (in_array('mobile_number', $allowedFields)) { ?>
        <?= $form->field($model, 'mobile_number')->textInput(['class'=>'form-control border']); ?>

        <?php } ?>
    
        <?php if (in_array('email', $allowedFields)) { ?>
        <?= $form->field($model, 'email')->textInput(['class'=>'form-control border']); ?>

        <?php } ?>
    
        <?php if (in_array('textarea_1716885445830', $allowedFields)) { ?>
        <?=  $form->field($model, 'textarea_1716885445830')->textarea(['rows' => 6, 'class' => 'form-control border']);?>

        <?php } ?>
    
        <?php if (in_array('office_address', $allowedFields)) { ?>
        <?=  $form->field($model, 'office_address')->textarea(['rows' => 6, 'class' => 'form-control border']);?>

        <?php } ?>
    
        <?php if (in_array('passport_number', $allowedFields)) { ?>
        <?= $form->field($model, 'passport_number')->textInput(['class'=>'form-control border']); ?>

        <?php } ?>
    
        <?php if (in_array('date_of_birth', $allowedFields)) { ?>
         <label class='control-label custom-label' for='date_of_birth'>Date of Birth</label>
                 <?= $form->field($model, 'date_of_birth')->widget(\kartik\date\DatePicker::classname(), [
                                                                'model' => $model,

                                                                'options' => ['placeholder' => ''],
                                                                'pluginOptions' => [
                                                                    'todayHighlight' => true,
                                                                    'todayBtn' => true,
                                                                    'format' => 'yyyy-mm-dd',
                                                                    'autoclose' => true,
                                                                ]
                                                            ])->label(false); ?>

        <?php } ?>
    
        <?php if (in_array('select_1716885518762', $allowedFields)) { ?>
        
    <?= $form->field($model, 'select_1716885518762')->dropDownList(backend\models\Applicant::select_1716885518762(),['prompt'=>"Select",'class'=>'myselect']); ?>

        <?php } ?>
    
        <?php if (in_array('nationality', $allowedFields)) { ?>
        <?= $form->field($model, 'nationality')->textInput(['class'=>'form-control border']); ?>

        <?php } ?>
    
        <?php if (in_array('sending_country', $allowedFields)) { ?>
        <?= $form->field($model, 'sending_country')->textInput(['class'=>'form-control border']); ?>

        <?php } ?>
    
        <?php if (in_array('file_1609222030883', $allowedFields)) { ?>
        <div class='form-group'>
        <div class='row margin_unset upload_cont_all'>
         <label class='control-label'>Passport Upload</label>
          <div class='row margin_unset upload_cont'>
           <div class='row margin_unset'>
    
      <?php if(empty($model->file_1609222030883)){
            
          $file_1609222030883=\Yii::$app->security->generateRandomString(8).str_replace('.','',microtime(true));
          $model->file_1609222030883=$file_1609222030883;
               }   
        ?>

<?php echo $form->field($model, 'file_1609222030883')->hiddenInput()->label(false); ?>

<?php echo $form->field($model, 'file_1609222030883_upload')->hiddenInput()->label(false); ?>

<?php
            if(!$model->isNewRecord){
                echo $form->field($model, 'attachment_ids_file_1609222030883')->hiddenInput(['id'=>'attachment_ids_file_1609222030883'])->label(false);
                $attachmentsArrayFinalfile_1609222030883=[];
        
                foreach ($model->file_1609222030883s as $upload){
                    $attachmentsArrayfile_1609222030883=[];
                    $attachmentsArrayfile_1609222030883['id']=$upload->id;
                    $attachmentsArrayfile_1609222030883['attachment']=$upload->attachment;
                    $attachmentsArrayfile_1609222030883['extension']=$upload->extension;
                     $attachmentsArrayfile_1609222030883['name']=$upload->name;
                    array_push($attachmentsArrayFinalfile_1609222030883, $attachmentsArrayfile_1609222030883);
                }
                echo \backend\widgets\attachmentGallery\AttachmentGallery::widget(
                    [
                        'label'=>false,
                        'attachmentArray' => $attachmentsArrayFinalfile_1609222030883,
                        'module_id'=>$model->id,
                        'cancel'=>true,
                        'uId'=>'attachment_file_1609222030883',
                        'cancelButton'=>'function onClickCancel(modelId,objectId,moduleId){
                                                     handleCancelEventMore(modelId,objectId,moduleId,"file_1609222030883");
                                                     
                                             }',
                        'imageButton'=>'function onClickImage(modelId,object){
                                                     handleImageClickEvent(modelId,object);
                                                    
                                             }',
                    ]
                );
            }
         ?>
      </div>

<?php if(isset($_GET["id"]) && $_GET["id"] > 0) {
        $fieldObject = array_column($allowedFieldObjects, null, "applicant_field_key")["file_1609222030883"] ?? false; 
      }?><div class='row margin_unset'>
           <?=\common\components\DropZone::widget([
                "id" => "drop_zone_new_form_project",
                "dropzoneContainer" => "drop_zone_container_new_form_project_file_1609222030883",
                "previewsContainer" => "drop_zone_preview_container_new_form_project_file_1609222030883",
                "options" => [
                    "url" => \yii\helpers\Url::to(["mii/file-upload/upload-temp-file","session_id"=>$model->file_1609222030883]),
                    "paramName" => "attachment",
                    "maxFilesize" => "20",
                    "addRemoveLinks" => true,
                ],
                "clientEvents" => [
                    "complete" => "function(file){
                      handleFileUpload('file_1609222030883');
                    }",
                    "removedfile" => "function(file){
                    handleFileUpload('file_1609222030883');
                    }",
                    "success" => "function(data){
            
            }"
                ],
            ]); ?>
          </div></div></div></div>

        <?php } ?>
    
        <?php if (in_array('date_1716885690490', $allowedFields)) { ?>
         <label class='control-label custom-label' for='date_1716885690490'>Passport Issue Date</label>
                 <?= $form->field($model, 'date_1716885690490')->widget(\kartik\date\DatePicker::classname(), [
                                                                'model' => $model,

                                                                'options' => ['placeholder' => ''],
                                                                'pluginOptions' => [
                                                                    'todayHighlight' => true,
                                                                    'todayBtn' => true,
                                                                    'format' => 'yyyy-mm-dd',
                                                                    'autoclose' => true,
                                                                ]
                                                            ])->label(false); ?>

        <?php } ?>
    
        <?php if (in_array('date_1716885716345', $allowedFields)) { ?>
         <label class='control-label custom-label' for='date_1716885716345'>Passport Expiry Date</label>
                 <?= $form->field($model, 'date_1716885716345')->widget(\kartik\date\DatePicker::classname(), [
                                                                'model' => $model,

                                                                'options' => ['placeholder' => ''],
                                                                'pluginOptions' => [
                                                                    'todayHighlight' => true,
                                                                    'todayBtn' => true,
                                                                    'format' => 'yyyy-mm-dd',
                                                                    'autoclose' => true,
                                                                ]
                                                            ])->label(false); ?>

        <?php } ?>
    
        <?php if (in_array('select_1716885772442', $allowedFields)) { ?>
        
    <?= $form->field($model, 'select_1716885772442')->dropDownList(backend\models\Applicant::select_1716885772442(),['prompt'=>"Select",'class'=>'myselect']); ?>

        <?php } ?>
    
        <?php if (in_array('date_1674644208007', $allowedFields)) { ?>
         <label class='control-label custom-label' for='date_1674644208007'>Request Date</label>
                 <?= $form->field($model, 'date_1674644208007')->widget(\kartik\date\DatePicker::classname(), [
                                                                'model' => $model,

                                                                'options' => ['placeholder' => ''],
                                                                'pluginOptions' => [
                                                                    'todayHighlight' => true,
                                                                    'todayBtn' => true,
                                                                    'format' => 'yyyy-mm-dd',
                                                                    'autoclose' => true,
                                                                ]
                                                            ])->label(false); ?>

        <?php } ?>
    
        <?php if (in_array('file_1716885886753', $allowedFields)) { ?>
        <div class='form-group'>
        <div class='row margin_unset upload_cont_all'>
         <label class='control-label'>Birth Certificate</label>
          <div class='row margin_unset upload_cont'>
           <div class='row margin_unset'>
    
      <?php if(empty($model->file_1716885886753)){
            
          $file_1716885886753=\Yii::$app->security->generateRandomString(8).str_replace('.','',microtime(true));
          $model->file_1716885886753=$file_1716885886753;
               }   
        ?>

<?php echo $form->field($model, 'file_1716885886753')->hiddenInput()->label(false); ?>

<?php echo $form->field($model, 'file_1716885886753_upload')->hiddenInput()->label(false); ?>

<?php
            if(!$model->isNewRecord){
                echo $form->field($model, 'attachment_ids_file_1716885886753')->hiddenInput(['id'=>'attachment_ids_file_1716885886753'])->label(false);
                $attachmentsArrayFinalfile_1716885886753=[];
        
                foreach ($model->file_1716885886753s as $upload){
                    $attachmentsArrayfile_1716885886753=[];
                    $attachmentsArrayfile_1716885886753['id']=$upload->id;
                    $attachmentsArrayfile_1716885886753['attachment']=$upload->attachment;
                    $attachmentsArrayfile_1716885886753['extension']=$upload->extension;
                     $attachmentsArrayfile_1716885886753['name']=$upload->name;
                    array_push($attachmentsArrayFinalfile_1716885886753, $attachmentsArrayfile_1716885886753);
                }
                echo \backend\widgets\attachmentGallery\AttachmentGallery::widget(
                    [
                        'label'=>false,
                        'attachmentArray' => $attachmentsArrayFinalfile_1716885886753,
                        'module_id'=>$model->id,
                        'cancel'=>true,
                        'uId'=>'attachment_file_1716885886753',
                        'cancelButton'=>'function onClickCancel(modelId,objectId,moduleId){
                                                     handleCancelEventMore(modelId,objectId,moduleId,"file_1716885886753");
                                                     
                                             }',
                        'imageButton'=>'function onClickImage(modelId,object){
                                                     handleImageClickEvent(modelId,object);
                                                    
                                             }',
                    ]
                );
            }
         ?>
      </div>

<?php if(isset($_GET["id"]) && $_GET["id"] > 0) {
        $fieldObject = array_column($allowedFieldObjects, null, "applicant_field_key")["file_1716885886753"] ?? false; 
      }?><div class='row margin_unset'>
           <?=\common\components\DropZone::widget([
                "id" => "drop_zone_new_form_project",
                "dropzoneContainer" => "drop_zone_container_new_form_project_file_1716885886753",
                "previewsContainer" => "drop_zone_preview_container_new_form_project_file_1716885886753",
                "options" => [
                    "url" => \yii\helpers\Url::to(["mii/file-upload/upload-temp-file","session_id"=>$model->file_1716885886753]),
                    "paramName" => "attachment",
                    "maxFilesize" => "20",
                    "addRemoveLinks" => true,
                ],
                "clientEvents" => [
                    "complete" => "function(file){
                      handleFileUpload('file_1716885886753');
                    }",
                    "removedfile" => "function(file){
                    handleFileUpload('file_1716885886753');
                    }",
                    "success" => "function(data){
            
            }"
                ],
            ]); ?>
          </div></div></div></div>

        <?php } ?>
    
        <?php if (in_array('file_1716885947331', $allowedFields)) { ?>
        <div class='form-group'>
        <div class='row margin_unset upload_cont_all'>
         <label class='control-label'>Driving License</label>
          <div class='row margin_unset upload_cont'>
           <div class='row margin_unset'>
    
      <?php if(empty($model->file_1716885947331)){
            
          $file_1716885947331=\Yii::$app->security->generateRandomString(8).str_replace('.','',microtime(true));
          $model->file_1716885947331=$file_1716885947331;
               }   
        ?>

<?php echo $form->field($model, 'file_1716885947331')->hiddenInput()->label(false); ?>

<?php echo $form->field($model, 'file_1716885947331_upload')->hiddenInput()->label(false); ?>

<?php
            if(!$model->isNewRecord){
                echo $form->field($model, 'attachment_ids_file_1716885947331')->hiddenInput(['id'=>'attachment_ids_file_1716885947331'])->label(false);
                $attachmentsArrayFinalfile_1716885947331=[];
        
                foreach ($model->file_1716885947331s as $upload){
                    $attachmentsArrayfile_1716885947331=[];
                    $attachmentsArrayfile_1716885947331['id']=$upload->id;
                    $attachmentsArrayfile_1716885947331['attachment']=$upload->attachment;
                    $attachmentsArrayfile_1716885947331['extension']=$upload->extension;
                     $attachmentsArrayfile_1716885947331['name']=$upload->name;
                    array_push($attachmentsArrayFinalfile_1716885947331, $attachmentsArrayfile_1716885947331);
                }
                echo \backend\widgets\attachmentGallery\AttachmentGallery::widget(
                    [
                        'label'=>false,
                        'attachmentArray' => $attachmentsArrayFinalfile_1716885947331,
                        'module_id'=>$model->id,
                        'cancel'=>true,
                        'uId'=>'attachment_file_1716885947331',
                        'cancelButton'=>'function onClickCancel(modelId,objectId,moduleId){
                                                     handleCancelEventMore(modelId,objectId,moduleId,"file_1716885947331");
                                                     
                                             }',
                        'imageButton'=>'function onClickImage(modelId,object){
                                                     handleImageClickEvent(modelId,object);
                                                    
                                             }',
                    ]
                );
            }
         ?>
      </div>

<?php if(isset($_GET["id"]) && $_GET["id"] > 0) {
        $fieldObject = array_column($allowedFieldObjects, null, "applicant_field_key")["file_1716885947331"] ?? false; 
      }?><div class='row margin_unset'>
           <?=\common\components\DropZone::widget([
                "id" => "drop_zone_new_form_project",
                "dropzoneContainer" => "drop_zone_container_new_form_project_file_1716885947331",
                "previewsContainer" => "drop_zone_preview_container_new_form_project_file_1716885947331",
                "options" => [
                    "url" => \yii\helpers\Url::to(["mii/file-upload/upload-temp-file","session_id"=>$model->file_1716885947331]),
                    "paramName" => "attachment",
                    "maxFilesize" => "20",
                    "addRemoveLinks" => true,
                ],
                "clientEvents" => [
                    "complete" => "function(file){
                      handleFileUpload('file_1716885947331');
                    }",
                    "removedfile" => "function(file){
                    handleFileUpload('file_1716885947331');
                    }",
                    "success" => "function(data){
            
            }"
                ],
            ]); ?>
          </div></div></div></div>

        <?php } ?>
    
        <?php if (in_array('file_1716886041312', $allowedFields)) { ?>
        <div class='form-group'>
        <div class='row margin_unset upload_cont_all'>
         <label class='control-label'>Educational Certificates</label>
          <div class='row margin_unset upload_cont'>
           <div class='row margin_unset'>
    
      <?php if(empty($model->file_1716886041312)){
            
          $file_1716886041312=\Yii::$app->security->generateRandomString(8).str_replace('.','',microtime(true));
          $model->file_1716886041312=$file_1716886041312;
               }   
        ?>

<?php echo $form->field($model, 'file_1716886041312')->hiddenInput()->label(false); ?>

<?php echo $form->field($model, 'file_1716886041312_upload')->hiddenInput()->label(false); ?>

<?php
            if(!$model->isNewRecord){
                echo $form->field($model, 'attachment_ids_file_1716886041312')->hiddenInput(['id'=>'attachment_ids_file_1716886041312'])->label(false);
                $attachmentsArrayFinalfile_1716886041312=[];
        
                foreach ($model->file_1716886041312s as $upload){
                    $attachmentsArrayfile_1716886041312=[];
                    $attachmentsArrayfile_1716886041312['id']=$upload->id;
                    $attachmentsArrayfile_1716886041312['attachment']=$upload->attachment;
                    $attachmentsArrayfile_1716886041312['extension']=$upload->extension;
                     $attachmentsArrayfile_1716886041312['name']=$upload->name;
                    array_push($attachmentsArrayFinalfile_1716886041312, $attachmentsArrayfile_1716886041312);
                }
                echo \backend\widgets\attachmentGallery\AttachmentGallery::widget(
                    [
                        'label'=>false,
                        'attachmentArray' => $attachmentsArrayFinalfile_1716886041312,
                        'module_id'=>$model->id,
                        'cancel'=>true,
                        'uId'=>'attachment_file_1716886041312',
                        'cancelButton'=>'function onClickCancel(modelId,objectId,moduleId){
                                                     handleCancelEventMore(modelId,objectId,moduleId,"file_1716886041312");
                                                     
                                             }',
                        'imageButton'=>'function onClickImage(modelId,object){
                                                     handleImageClickEvent(modelId,object);
                                                    
                                             }',
                    ]
                );
            }
         ?>
      </div>

<?php if(isset($_GET["id"]) && $_GET["id"] > 0) {
        $fieldObject = array_column($allowedFieldObjects, null, "applicant_field_key")["file_1716886041312"] ?? false; 
      }?><div class='row margin_unset'>
           <?=\common\components\DropZone::widget([
                "id" => "drop_zone_new_form_project",
                "dropzoneContainer" => "drop_zone_container_new_form_project_file_1716886041312",
                "previewsContainer" => "drop_zone_preview_container_new_form_project_file_1716886041312",
                "options" => [
                    "url" => \yii\helpers\Url::to(["mii/file-upload/upload-temp-file","session_id"=>$model->file_1716886041312]),
                    "paramName" => "attachment",
                    "maxFilesize" => "20",
                    "addRemoveLinks" => true,
                ],
                "clientEvents" => [
                    "complete" => "function(file){
                      handleFileUpload('file_1716886041312');
                    }",
                    "removedfile" => "function(file){
                    handleFileUpload('file_1716886041312');
                    }",
                    "success" => "function(data){
            
            }"
                ],
            ]); ?>
          </div></div></div></div>

        <?php } ?>
    
        <?php if (in_array('file_1716886071776', $allowedFields)) { ?>
        <div class='form-group'>
        <div class='row margin_unset upload_cont_all'>
         <label class='control-label'>Other Docs</label>
          <div class='row margin_unset upload_cont'>
           <div class='row margin_unset'>
    
      <?php if(empty($model->file_1716886071776)){
            
          $file_1716886071776=\Yii::$app->security->generateRandomString(8).str_replace('.','',microtime(true));
          $model->file_1716886071776=$file_1716886071776;
               }   
        ?>

<?php echo $form->field($model, 'file_1716886071776')->hiddenInput()->label(false); ?>

<?php echo $form->field($model, 'file_1716886071776_upload')->hiddenInput()->label(false); ?>

<?php
            if(!$model->isNewRecord){
                echo $form->field($model, 'attachment_ids_file_1716886071776')->hiddenInput(['id'=>'attachment_ids_file_1716886071776'])->label(false);
                $attachmentsArrayFinalfile_1716886071776=[];
        
                foreach ($model->file_1716886071776s as $upload){
                    $attachmentsArrayfile_1716886071776=[];
                    $attachmentsArrayfile_1716886071776['id']=$upload->id;
                    $attachmentsArrayfile_1716886071776['attachment']=$upload->attachment;
                    $attachmentsArrayfile_1716886071776['extension']=$upload->extension;
                     $attachmentsArrayfile_1716886071776['name']=$upload->name;
                    array_push($attachmentsArrayFinalfile_1716886071776, $attachmentsArrayfile_1716886071776);
                }
                echo \backend\widgets\attachmentGallery\AttachmentGallery::widget(
                    [
                        'label'=>false,
                        'attachmentArray' => $attachmentsArrayFinalfile_1716886071776,
                        'module_id'=>$model->id,
                        'cancel'=>true,
                        'uId'=>'attachment_file_1716886071776',
                        'cancelButton'=>'function onClickCancel(modelId,objectId,moduleId){
                                                     handleCancelEventMore(modelId,objectId,moduleId,"file_1716886071776");
                                                     
                                             }',
                        'imageButton'=>'function onClickImage(modelId,object){
                                                     handleImageClickEvent(modelId,object);
                                                    
                                             }',
                    ]
                );
            }
         ?>
      </div>

<?php if(isset($_GET["id"]) && $_GET["id"] > 0) {
        $fieldObject = array_column($allowedFieldObjects, null, "applicant_field_key")["file_1716886071776"] ?? false; 
      }?><div class='row margin_unset'>
           <?=\common\components\DropZone::widget([
                "id" => "drop_zone_new_form_project",
                "dropzoneContainer" => "drop_zone_container_new_form_project_file_1716886071776",
                "previewsContainer" => "drop_zone_preview_container_new_form_project_file_1716886071776",
                "options" => [
                    "url" => \yii\helpers\Url::to(["mii/file-upload/upload-temp-file","session_id"=>$model->file_1716886071776]),
                    "paramName" => "attachment",
                    "maxFilesize" => "20",
                    "addRemoveLinks" => true,
                ],
                "clientEvents" => [
                    "complete" => "function(file){
                      handleFileUpload('file_1716886071776');
                    }",
                    "removedfile" => "function(file){
                    handleFileUpload('file_1716886071776');
                    }",
                    "success" => "function(data){
            
            }"
                ],
            ]); ?>
          </div></div></div></div>

        <?php } ?>
    
        

 
<!--    if((isset($_GET['applicantID']) && isset($_GET['id'])) || Yii::$app->controller->action->id == 'create') -->
<!--    {?>-->
    
<?php 
if($model->parent_id){
    $parentApplicant = Applicant::findOne($model->parent_id);
    $model->client_id = $parentApplicant->client_id;
    echo $form->field($model, 'client_id')->hiddenInput()->label(false);
}
else if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT){
        $model->client_id = Yii::$app->user->identity->client_id;
    echo $form->field($model, 'client_id')->hiddenInput()->label(false);
} 
else if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_WORKER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_MANAGER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_MANAGER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN){
 $clientArray = ArrayHelper::map(Client::find()->leftJoin('tbl_client_organisation', 'tbl_client_organisation.client_id = client.id')->andWhere(['tbl_client_organisation.organisation_id' => Yii::$app->user->identity->organisation_id])->all(), 'id', 'client_name');
 ?>
<label class='control-label custom-label' for='template_id'>
    Select Client
</label>
<?php echo $form->field($model, 'client_id')->dropDownList($clientArray, array('prompt' => '- Select -','class'=>'myselect','required'=>true))->label(false);
}
    ?><!--    -->
<!-- -->
<!--    $clientEntities = [];-->
<!--    if (Yii::$app->controller->action->id == 'update' || isset(Yii::$app->user->identity->client_id) || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_WORKER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_MANAGER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_MANAGER ) {-->
<!--        $clientEntities = \backend\models\ClientEntity::find()->where(['client_id' => $model->client_id])->asArray()->all();-->
<!--        $clientEntities = ArrayHelper::map($clientEntities, 'id', 'name');-->
<!--    }-->
<!--?>-->
<!--    <div>-->
<!--        <label class="control-label custom-label" for='client-entity-dropdown'>Select Client Entity</label>-->
<!--    </div>-->
<!--    <div class="fa fa-circle-o-notch fa-spin loading-div-client-entity" style="display:none;"></div>-->
<!---->
<!--    echo $form->field($model_case, 'client_entity')->dropDownList($clientEntities, [-->
<!--        'prompt' => '- Select - ',-->
<!--        'class' => 'myselect',-->
<!--        'id' => 'client-entity-dropdown',-->
<!--        'required' => true,-->
<!--    ])->label(false);-->
<!--?>-->
<!---->
<!---->
<!--    --><!--if(Yii::$app->getUser()->identity->role != GlobalConstant::ROLE_CLIENT_HR){-->
<!--    if(Yii::$app->getUser()->identity->role == GlobalConstant::ROLE_CLIENT){-->
<!--     $dropList = ArrayHelper::map(User::find()->where(['client_id'=>User::findOne(Yii::$app->user->id)->client_id])->all(), 'id','username');-->
<!--if(count($dropList) == 1)-->
<!--{?> --><!--    -->
<!--    --><!-- echo $form->field($model_case, 'raised_by_id')->dropDownList([Yii::$app->user->id => Yii::$app->user->id],['prompt' => '- Select -','class'=>'myselect d-none','id'=>'HR_dropdown', 'options' => [Yii::$app->user->id=>['Selected'=> 'selected']]])->label(false);-->
<!--}-->
<!--else-->
<!--{?> --><!--    --><!-- unset($dropList[Yii::$app->user->id]);-->
<!--    echo $form->field($model_case, 'raised_by_id')->dropDownList($dropList,['prompt' => '- Select -','class'=>'myselect','id'=>'HR_dropdown','required'=>true])->label(false);}-->
<!--    } -->
<!--    else{?>-->
<!---->
<!--echo $form->field($model_case, 'raised_by_id')->dropDownList($model_case->isNewRecord ?[]:ArrayHelper::map(User::find()->where(['client_id'=>$model->client_id])->all(),'id','username'),['prompt' => '- Select -','class'=>'myselect','id'=>'HR_dropdown','required'=>true])->label(false);-->
<!--}} else { -->
<!--    echo $form->field($model_case, 'raised_by_id')->dropDownList([Yii::$app->user->id => Yii::$app->user->id],['prompt' => '- Select -','class'=>'myselect d-none','id'=>'HR_dropdown', 'options' => [Yii::$app->user->id=>['Selected'=> 'selected']]])->label(false);}}-->
<!--?>-->
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update' , ['class' => $model->isNewRecord ? 'btn btn-rounded btn-success mr-10' : 'btn btn-rounded btn-primary mr-10']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
<script>
    function getClientEntities() {
        var clientID = $(this).val();
        console.log(clientID)
        $.ajax({
            'type': 'GET',
            'url': '<?php echo \yii\helpers\Url::to(['client-entity/get-client-entities?']) ?>clientID=' + clientID,
            'beforeSend': function () {
                $('.loading-div-client-entity').attr('style', 'display:inline-block');
                $('#client-entity-dropdown').attr('style', 'display:none');
            },
            'success': function (response) {
                var responseData = JSON.parse(response)
                if (responseData.code === 0) {
                    toastr.warning(responseData.message);
                    $('.loading-div-client-entity').attr('style', 'display:none');
                    $('#client-entity-dropdown').attr('style', 'display:block');
                    return;
                } 

                if (responseData.code === 1) {
                    var clientEntities = responseData.clientEntities;
                    var options = '';
                    $('.appended-options').each(function() {
                        $(this).remove();
                    })

                    for (var i = 0; i < clientEntities.length; i += 1) {
                        options += '<option class="appended-options" value="' + clientEntities[i].id + '">' + clientEntities[i].name + '</option>';
                    }
                    $('#client-entity-dropdown').append(options);
                    $('.loading-div-client-entity').attr('style', 'display:none');
                    $('#client-entity-dropdown').attr('style', 'display:block');
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
            url: 'delete-file',
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
     $(".loading-div").each(function() {
                        $(this).prop('style', 'display: none;');
                    })
     $("#no-client-hr").each(function() {
            $(this).prop('style', 'display: none;');
        })

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
            $("#applicant-"+attr+"_upload").val("");
        }else{
            $("#applicant-"+attr+"_upload").val(1);
        }
    }
    (function(){
        for(var i in init_array){
            var attr=init_array[i];
            var finalLength=$("#attachment_"+attr +" a").length+$("#drop_zone_container_new_form_project_"+attr+" .dz-preview").length;
            if(finalLength===0){
                $("#applicant-"+attr+"_upload").val("");
            }else{
                $("#applicant-"+attr+"_upload").val(1);
            }
        }
    })();
    
    function getClientHR() 
    {
        $("#select-label").each(function() {
                                $(this).prop('style', 'display: inline-block;');
                            })
        $("#no-client-hr").each(function() {
                                $(this).prop('style', 'display: none;');
                            })
        $("#HR_dropdown").each(function() {
                        $(this).prop('style', 'display: none;');
                    })
        $(".loading-div").each(function() {
                        $(this).prop('style', 'display: inline-block;');
                    })
        var id = $('#applicant-client_id').val();
        
        $.ajax({
                    

                    url:  'get-client-hr',
                    type: 'GET',
                    data: { id: id },
                    success: function(data) {
                        
                        // console.log(data);
                        var jsondata = JSON.parse(data);
                        // console.log(jsondata);
                        var keys= Object.keys(jsondata);
                        var values= Object.values(jsondata);
                        // console.log(keys, keys.length);
                        // console.log(values);
                        
                        //condition when only client is fetched as there are no client-hr for the client
                        if(keys.length == 1)
                        {
                            $("#select-label").each(function() {
                                $(this).prop('style', 'display: none;');
                            })
                            $("#no-client-hr").each(function() {
                                $(this).prop('style', 'display: inline-block;');
                            })
                            $(".loading-div").each(function() {
                                    $(this).prop('style', 'display: none;');
                                })
                            return;
                        }   
                        $('#HR_dropdown').html('<option value="">- Select -</option>'); 
                        var option = '';


                        for (i = 1 ; i < keys.length; i++){
                        option += '<option name="raised_by_id" value="'+ keys[i] + '">' + values[i] + '</option>';
                        }
                        console.log(option);
                        $('#HR_dropdown').append(option);
                        $(".loading-div").each(function() {
                                    $(this).prop('style', 'display: none;');
                                })
                        $("#HR_dropdown").each(function() {
                                    $(this).prop('style', 'display: inline-block;');
                                })

                    }
                });
    }

    $(document).ready(function() {
        
        var dependent = "<?= $model->parent_id?>";
        if(!dependent)
        {
            //select relationship field for dev : UNCOMMENT BELOW LINE FOR DEV AND COMMENT FOR LIVE
            //$('.field-applicant-select_1717159945674').hide();
            // select relationship field for LIVE : COMMENT BELOW LINE FOR DEV UNCOMMENT FOR LIVE AND
            $('.field-applicant-select_1717755396737').hide();
        }
    })

    // $("#applicant-client_id").change(getClientHR)
    // $("#applicant-client_id").change(getClientEntities)
</script>
