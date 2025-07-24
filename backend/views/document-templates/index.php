<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Document Templates';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-template-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Document Template', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'document_type',
            'language',
            'version',
            [
                'attribute' => 'is_active',
                'value' => function ($model) {
                    return $model->is_active ? 'Yes' : 'No';
                },
                'filter' => [1 => 'Yes', 0 => 'No'], // Allows filtering by active status
            ],
            'created_at:datetime',
            'updated_at:datetime',
            //'content:ntext', // Usually too long to show in index view
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}', // Standard CRUD actions
            ],
        ],
    ]); ?>
</div>