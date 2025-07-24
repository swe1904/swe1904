<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\ClientSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="client-search">
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

    <?= $form->field($model, 'organisation_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'client_name') ?>

    <?php // echo $form->field($model, 'country') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'text_1570532600638') ?>

    <?php // echo $form->field($model, 'text_1578126561394') ?>

    <div class="form-group">
        <?= Html::submitButton('', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
