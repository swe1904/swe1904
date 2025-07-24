<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Payroll Management';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?= Html::a('Create New Payroll', ['create'], ['class' => 'btn btn-success']) ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'payroll_month',
        'payroll_year',
        'status',
        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>
