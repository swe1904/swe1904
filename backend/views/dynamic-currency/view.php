<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Currency;

/** @var yii\web\View $this */
/** @var backend\models\DynamicCurrency $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dynamic Currencies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="dynamic-currency-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'currency_id',
                'label' => 'Currency',
                'value' => function($data) {
                    return Currency::findOne($data->currency_id)->name;
                }
            ],
            'conversion_rate_to_SAR',
        ],
    ]) ?>

</div>
