<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Earning */

$this->title = 'Create Earning';
$this->params['breadcrumbs'][] = ['label' => 'Earnings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="earning-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
