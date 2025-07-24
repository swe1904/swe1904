<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\messagesystem\models\MessageInbox */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="message-inbox-form">

    <?php $form = ActiveForm::begin(['id' => 'login-form','action'=>'/messageSystem/message/send-message']);
     $model->user_email=$model->getUserEmail();
    ?>

    <?= $form->field($model, 'thread_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'room_listing_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'tenant_listing_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'sender_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'receiver_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'user_email')->textInput(['readOnly'=> true]) ?>

    <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Post' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
