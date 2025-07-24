<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Case_history */

$this->title = 'Update Case History: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Case Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="case-history-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
