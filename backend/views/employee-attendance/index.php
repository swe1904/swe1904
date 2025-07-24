<?php
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'All Attendance Logs';
?>

<h3>Attendance Logs</h3>

<?= Html::a('Add New Entry', ['create'], ['class' => 'btn btn-primary']) ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'employee_id',
        'date',
        'clock_in_time',
        'clock_out_time',
        'worked_minutes',
        'location_status',
        'ip_address',
        ['class' => 'yii\grid\ActionColumn'],
    ],
]) ?>
