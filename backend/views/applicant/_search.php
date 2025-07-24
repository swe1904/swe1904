<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\ApplicantSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="applicant-search">
    $organisation=Organisation::find()->where('user_id=:user_id',[':user_id'=>yii::$app->user->id])->one();
    if(empty($organisation)){
      return null;
    }
    $query = Client::find()->where('organisation_id=:organisation_id and user_id=:user_id',[':organistion_id'=>$organisation->id,':user_id'=>yii::$app->user->id]);
    $dataProvider = new ActiveDataProvider([
    'query' => $query,
    ]);
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'client_id') ?>

    <?= $form->field($model, 'parent_id') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'first_name') ?>

    <?php // echo $form->field($model, 'last_name') ?>

    <?php // echo $form->field($model, 'nationality') ?>

    <?php // echo $form->field($model, 'sending_country') ?>

    <?php // echo $form->field($model, 'date_of_birth') ?>

    <?php // echo $form->field($model, 'passport_number') ?>

    <?php // echo $form->field($model, 'mobile_number') ?>

    <?php // echo $form->field($model, 'office_address') ?>

    <?php // echo $form->field($model, 'file_1609222030883') ?>

    <?php // echo $form->field($model, 'date_1674644208007') ?>

    <?php // echo $form->field($model, 'textarea_1716885445830') ?>

    <?php // echo $form->field($model, 'select_1716885518762') ?>

    <?php // echo $form->field($model, 'date_1716885690490') ?>

    <?php // echo $form->field($model, 'date_1716885716345') ?>

    <?php // echo $form->field($model, 'select_1716885772442') ?>

    <?php // echo $form->field($model, 'file_1716885886753') ?>

    <?php // echo $form->field($model, 'file_1716885947331') ?>

    <?php // echo $form->field($model, 'file_1716886041312') ?>

    <?php // echo $form->field($model, 'file_1716886071776') ?>

    <?php // echo $form->field($model, 'select_1717755396737') ?>

    <div class="form-group">
        <?= Html::submitButton('', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
