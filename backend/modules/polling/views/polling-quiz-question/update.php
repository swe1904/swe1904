<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\polling\models\PollingQuizQuestion */

$this->title = 'Update Polling Question: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Polling Questions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="col-md-12">
    <div class="panel panel-default card-view panel-refresh">
        <div class="panel-hading">
            <div class="polling-quiz-question-update">

            <?php
            echo $this->render('_form_final', [
                'model' => $model,
                'pollingQuizQuestionOption'=>$pollingQuizQuestionOption
            ]) ?>

            </div>
        </div>
    </div>
</div>



