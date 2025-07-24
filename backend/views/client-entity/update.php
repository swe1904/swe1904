<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ClientEntity $model */

$this->title = Yii::t('backend', 'Update Client Entity: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Client Entities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="client-entity-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'clients' => $clients,
    ]) ?>

</div>
