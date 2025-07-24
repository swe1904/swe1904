<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\modules\messageSystem\models\MessageInbox;
/* @var $this yii\web\View */
/* @var $model backend\modules\messagesystem\models\MessageInbox */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="message-inbox-form">
    <?php
    $session_id=\Yii::$app->security->generateRandomString(8).str_replace('.','',microtime(true));;
    $model->session_id=$session_id;
    ?>
    <?php $form = ActiveForm::begin(['id' => 'new-message-form','action'=>'../send-message']);
    $model->message="";
    ?>

    <?= $form->field($model, 'thread_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'sender_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'receiver_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'message')->textarea(['rows' => 3]) ?>
    <div class="row margin_unset"> <div class="form-group">
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
        </div></div>
    <div class="form-group">
        <?= Html::submitButton('Send message', ['class' =>'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
