<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\DynamicCurrency $model */

$this->title = 'Update Dynamic Currency: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dynamic Currencies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dynamic-currency-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'currency' => $currency,
    ]) ?>

</div>
