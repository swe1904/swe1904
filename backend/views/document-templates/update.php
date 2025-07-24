<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DocumentTemplate */
/* @var $oldModel common\models\DocumentTemplate */ // Passed from controller for reference

$this->title = 'Update Document Template: ' . $oldModel->document_type . ' (v' . $oldModel->version . ')';
$this->params['breadcrumbs'][] = ['label' => 'Document Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $oldModel->document_type, 'url' => ['view', 'id' => $oldModel->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="document-template-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-info">
        <strong>Note:</strong> Updating this template will create a new version and mark the current one as inactive.
        The new version will be assigned version <b><?= Html::encode($model->version) ?></b> and will be active.
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>