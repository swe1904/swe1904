<?php
use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\BusinessTravelSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Business Travel Records';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="business-travel-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Business Travel', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
       'columns' => [
    ['class' => 'yii\grid\SerialColumn'],

    [
        'attribute' => 'employee_id',
        'label' => 'Employee',
        'value' => function ($model) {
            return $model->employee ? $model->employee->preferred_full_name : '(not set)';
        },
    ],

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
    // 'created_at',

    ['class' => 'yii\grid\ActionColumn'],
],

    ]); ?>

</div>
