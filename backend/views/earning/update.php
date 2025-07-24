<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Earning */

$this->title = 'Update Earning: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Earnings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="earning-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
