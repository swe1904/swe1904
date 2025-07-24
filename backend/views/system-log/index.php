<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'System Logs';
$this->params['breadcrumbs'][] = $this->title;

// print_r($dataProvider);
// die("die here");
?>
<div class="system-log-index">

    <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        'id',
        [
            'attribute' => 'log_time',
            'format' => ['datetime', 'php:Y-m-d H:i:s'],
            'label' => 'Log Time',
        ],
        'category',
        'prefix',
        [
            'attribute' => 'message',
            'label' => 'Message',
            // increase column width
            'contentOptions' => ['style' => 'width: 100%'],
            'headerOptions' => ['style' => 'width: 100%'],
            'value' => function ($data) {
                return $data->message;
            }
        ],

        // ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>
</div>
