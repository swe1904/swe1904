<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EarningSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Earnings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="earning-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
    <div style="text-align:left;">
        <?= Html::a('Create  Earning', ['create'], ['class' => 'btn btn-success']) ?>

        </div>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'id',
            'name',
            'percentage',

            ['class' => 'yii\grid\ActionColumn'],

//          [
// //    'class' => 'yii\grid\ActionColumn',
// //   'template' => '{my_button}', 
// // 'buttons' => [
// //     'my_button' => function ($url, $model, $key) {
// //         return Html::a('Print', ['earning/sample-pdf', 'id'=>$model->id]);
// //     },
// // ]
// // ]
      ],
    ]); ?>
</div>
