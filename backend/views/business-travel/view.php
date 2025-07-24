<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\BusinessTravel $model */

$this->title = 'View Travel ID: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Business Travel', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-travel-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => ['confirm' => 'Are you sure you want to delete this record?', 'method' => 'post'],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            'employee.preferred_full_name:text:Employee',
            [
        'attribute' => 'country',
        'label' => 'Country',
        'value' => function ($model) {
            return $model->countryModel ? $model->countryModel->country_name : '(not set)';
        },
    ],
            'from_date',
            'to_date',
            'reason:ntext',
            'created_at',
        ],
    ]) ?>

</div>
