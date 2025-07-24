<?php

use app\components\GlobalConstant;
use common\models\Organisation;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;
/* @var $this yii\web\View */
/* @var $model backend\modules\messagesystem\models\MessageInbox */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="message-inbox-form">
<?php
$session_id=\Yii::$app->security->generateRandomString(8).str_replace('.','',microtime(true));;
$model->session_id=$session_id;
?>
    <?php $form = ActiveForm::begin(['id' => 'compose-form-casestep','action'=>\yii\helpers\Url::to(['/messageSystem/message/send-message-casestep']),
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,]);
    ?>
        <div class="form-group">
            <label>To</label>
            <?php echo $form->field($model, 'receiver_id')->dropDownList(\yii\helpers\ArrayHelper::map($receiver, 'id', 'email'))->label(false);
            ?>
        </div>
        <?= $form->field($model, 'subject')->textInput(['value' => $caseInfor['all'], 'required' => true]) ?>
        <?= $form->field($model, 'thread_id')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'sender_id')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>
        <div class="form-group">
            <?php echo $form->field($model, 'session_id')->hiddenInput()->label(false) ?>
            <?php
            echo \kato\DropZone::widget([
                'id' => "drop_zone_new_form_project_",
                'dropzoneContainer' => "drop_zone_container_new_form_project_",
                'previewsContainer' => "drop_zone_preview_container_new_form_project_",
                'options' => [
                    'url' => \yii\helpers\Url::to(['attachment/upload-temp-file','session_id'=>$model->session_id]),
                    'paramName' => 'attachment',
                    'maxFilesize' => '20',
                    'addRemoveLinks' => true,
                ],
                'clientEvents' => [
                    'complete' => "function(file){console.log(file)}",
                    'removedfile' => "function(file){removeUploadedFile(file.name,'$model->session_id');alert(file.name + ' is removed')}",
                    'success' => 'function(data){             
                                        }'
                ],
            ]);
            ?>
        </div>
        <center>
            <div class="form-group">
                <?= Html::submitButton('Send', ['class' => 'btn btn-default real_validate', 'onclick'=>'return ajaxComposeForm()', 'style' => 'display: none;']) ?>
            </div>
        </center>
    <?php ActiveForm::end(); ?>

    <center>
        <button class="btn btn-default" onclick="presubmitvalidate()">Send</button>
    </center>

</div>
<script>
    function presubmitvalidate() {
        var form = $("#compose-form-casestep");
        if ( $("#compose-form-casestep #messageinbox-subject").val() == '' ) {
            $('.field-messageinbox-subject').removeClass('has-error');
            $('.field-messageinbox-subject .help-block').text();
            
            $('.field-messageinbox-subject').addClass('has-error');
            $('.field-messageinbox-subject .help-block').text('Subject cannot be blank.');
            return;
        }if ( $("#compose-form-casestep #messageinbox-message").val() == '' ) {
            $('.field-messageinbox-message').removeClass('has-error');
            $('.field-messageinbox-message .help-block').text();

            $('.field-messageinbox-message').addClass('has-error');
            $('.field-messageinbox-message .help-block').text('Message cannot be blank.');
            return;
        }

        $('.real_validate').click();
    }

    function ajaxComposeForm() {
        var form = $("#compose-form-casestep");

        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: form.serialize(),
            success: function (data) {
                if(data==1){
                    location.reload();
                }else{
                }
            }
        });

        return false;
    }
</script>
<style>
    .modal-footer{
        display: none;
    }
</style>
