<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ArticlePaypalOrder */

$this->title = 'Update Article Paypal Order: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Article Paypal Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="article-paypal-order-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
