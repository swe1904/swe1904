<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DocumentRequest */

$this->title = 'Update Document Request: ' . $model->id;
// $this->params['breadcrumbs'][] = ['label' => 'Document Requests', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>

<div class="document-request-update">
    <h1><?php //Html::encode($this->title) ?></h1>
    <?= $this->render('create', ['model' => $model]) ?>
</div>
