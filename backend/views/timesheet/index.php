<?php
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Timesheets';
?>

<h1><?= Html::encode($this->title) ?></h1>

<p><?= Html::a('Add Time Entry', ['create'], ['class' => 'btn btn-success']) ?></p>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'employee_id',
        'date',
        'start_time',
        'end_time',
        'total_duration',
        'note',
    ],
]) ?>