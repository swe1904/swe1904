<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Service */

$this->title = 'Update Service: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Services', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="service-update">

    <h6><?= Html::encode($this->title) ?></h6>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
