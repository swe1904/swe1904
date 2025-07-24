<?php

use backend\models\DynamicCurrency;
use backend\models\Currency;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\search\DynamicCurrencySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Dynamic Currencies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dynamic-currency-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Dynamic Currency', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'currency_id',
                'label' => 'Currency',
                'value' => function ($data) {
                    return Currency::findOne($data->currency_id)->name;
                }
            ],
            'conversion_rate_to_SAR',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, DynamicCurrency $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
