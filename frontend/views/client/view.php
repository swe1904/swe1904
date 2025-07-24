<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Client */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'first_name_fixed',
            'last_name_fixed',
            'phone_fixed',
            'address_fixed:ntext',
            'text_1528808645886',
            'select_1528809495736',
            'date_1528809715690',
            'date_1528810280939',
        ],
    ]) ?>

</div>
