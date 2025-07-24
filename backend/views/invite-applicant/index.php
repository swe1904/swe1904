<?php
/* @var $this yii\web\View */

use app\components\GlobalConstant;
use backend\models\Client;
use backend\models\InviteApplicant;
use backend\modules\polling\models\EmailTemplate;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="col-md-12">
    <div class="panel panel-default card-view panel-refresh">
    <div class="panel-hading">
        <h6 class="mb-20">Invite Applicant</h6>
    </div>

    <div class="row">

        <?php $form = ActiveForm::begin(['action' => ['index'], 'id' => 'invite-applicant-form',
            'fieldConfig' => [
                'options' => [
                    'options' => ['class' => 'form-group invisible']
                ],
            ],
        ]); ?>

        <div class="col-md-12">
            <?php // echo $form->errorSummary($inviteApplicant); ?>
            <?php echo $form->field($inviteApplicant, 'polling_id')->dropDownList($inviteApplicant->getPolling_ids(), ['prompt' => '- Select -'])->label('Questionnaire') ?>
        </div>

        <div class="col-md-12">
            <?php echo $form->field($inviteApplicant, 'to_email')->textInput(['maxlength' => true,'placeholder' => 'Email1@example.com,Email2@example.com,...'])->label('To Email') ?>
        </div>

        <div class="col-md-12">
            <?php echo $form->field($inviteApplicant, 'template_id')->dropDownList(ArrayHelper::map(EmailTemplate::find()->all(), 'id', 'name'), array('prompt' => '- Select -'))->label('Email Template') ?>
        </div>

        <div class="col-md-12">
            <?php if (Yii::$app->user->can('organisation-admin')) { ?>
                <?php echo $form->field($inviteApplicant, 'client_id')->dropDownList(ArrayHelper::map(Client::find()->where(['user_id' => Yii::$app->user->id])->all(), 'id', 'client_name'), array('prompt' => '- Select -'))->label('Select Client') ?>
            <?php }
            else if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_WORKER ){?>
                <?php echo $form->field($inviteApplicant, 'client_id')->dropDownList(ArrayHelper::map( Client::find()->where(['user_id'=>Yii::$app->user->identity->organisation->user_id])->all(), 'id', 'client_name'), array('prompt' => '- Select -'))->label('Select Client');
            }?>
        </div>


        <div class="col-md-12">
            <?php echo Html::submitButton(Yii::t('backend', 'Send Mail'), ['class' => 'btn btn-rounded btn-success mr-10']) ?>
        </div>

    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>