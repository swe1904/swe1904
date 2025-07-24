<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\DynamicCurrency $model */

$this->title = 'Create Dynamic Currency';
$this->params['breadcrumbs'][] = ['label' => 'Dynamic Currencies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dynamic-currency-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'currencies' => $currencies,
    ]) ?>

</div>
