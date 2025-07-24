<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Nationalities';
?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
   
<?php endif; ?>
<div class="nationality-index">
    <h1><?php //Html::encode($this->title) ?></h1>
    <p><?= Html::a('Create Nationality', ['create'], ['class' => 'btn btn-success']) ?></p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'id',
            'name',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
