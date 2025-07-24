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
    <?php $form = ActiveForm::begin(['id' => 'compose-form','action'=>'send-message',
        'enableAjaxValidation'=>false,
        'enableClientValidation'=>true,]);
    ?>
    <div class="form-group">
        <label>To</label>
        <?php
        if(Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN)){
            $receiver =  User::find()->where(['not', ['id'=>Yii::$app->user->identity->id]])->all();
        }
        elseif(Yii::$app->user->can(GlobalConstant::ROLE_ORGANISATION_ADMIN)){
            $org= Organisation::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
            $receiver =  User::find()->where(['organisation_id' =>$org->id])->andWhere(['not',['email' =>Yii::$app->user->identity->email]])->all();
        }
        elseif (Yii::$app->user->can(GlobalConstant::ROLE_ORGANISATION_MANAGER) || Yii::$app->user->can(GlobalConstant::ROLE_FINANCE)) {
            $org = backend\models\Organisation::findOne(User::findOne(Yii::$app->user->id));
            $receiver =  User::find()->where(['organisation_id' =>$org->id])->andWhere(['not',['email' =>Yii::$app->user->identity->email]])->all();
        }
        elseif(Yii::$app->user->can(GlobalConstant::ROLE_CASE_WORKER)){
            $receiver =  User::find()->where(['organisation_id' =>Yii::$app->user->identity->organisation_id])->andWhere(['not',['email' =>Yii::$app->user->identity->email]])->all();
        }
        elseif (Yii::$app->user->can(GlobalConstant::ROLE_CLIENT)){
            $client=\common\models\Client::find()->where(['id'=>Yii::$app->user->identity->client_id])->one();
            $organisation= Organisation::find()->where(['id'=>Yii::$app->user->identity->organisation_id])->one();
            $receiver = User::find()->Where(['id' =>$organisation->user_id])->orWhere(['id' =>$client->user_id])->orWhere  (['client_id' =>Yii::$app->user->identity->client_id])->andWhere(['not',['email' =>Yii::$app->user->identity->email]])->all();
        }
        else {
            $client=\common\models\Client::find()->where(['id'=>Yii::$app->user->identity->client_id])->one();
            $organisation= Organisation::find()->where(['id'=>Yii::$app->user->identity->organisation_id])->one();
            $receiver = User::find()->Where(['id' =>$organisation->user_id])->orWhere(['id' =>$client->user_id])->all();
        }
        echo $form->field($model, 'receiver_id')->dropDownList(\yii\helpers\ArrayHelper::map($receiver,'id','email'))->label(false);
        ?>
    </div>
    <?= $form->field($model, 'subject')->textInput() ?>
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
        <?= Html::submitButton($model->isNewRecord ? 'Send' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-default' : 'btn btn-primary','onclick'=>'return ajaxComposeForm()']) ?>
    </div>
    </center>
    <?php ActiveForm::end(); ?>

</div>
<script>
    function ajaxComposeForm() {
        var form = $("#compose-form");

        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: form.serialize(),
            success: function (data) {
                if(data==1){
                    location.reload();
                }else{
                    $("#compose_message").find(".modal-body").html(data);
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
