<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\BusinessTravel $model */

$this->title = 'Create Business Travel';
$this->params['breadcrumbs'][] = ['label' => 'Business Travel', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-travel-create">

    <h3 class="text-center"><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
