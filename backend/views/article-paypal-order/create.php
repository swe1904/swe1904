<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ArticlePaypalOrder */

$this->title = 'Create Article Paypal Order';
$this->params['breadcrumbs'][] = ['label' => 'Article Paypal Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-paypal-order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
