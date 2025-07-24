<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\ArticleRequest */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
            <h4 class="modal-title">Confirmation</h4>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to clear balance ?</p>
        </div>
        <div class="modal-footer">
            <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
            <?php
            $url = Yii::$app->urlManager->createUrl(['user/clear-balance-save','id'=>$model->id]);
            echo  Html::a('Clear Balance', $url, ['title' => 'Clear Balance','class' => 'btn btn-primary']);
            ?>
        </div>
    </div>
</div>



