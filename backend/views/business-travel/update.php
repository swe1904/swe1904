<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\BusinessTravel $model */

$this->title = 'Update Business Travel: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Business Travel', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="business-travel-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', ['model' => $model]) ?>

</div>
