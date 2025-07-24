<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\Case_history_search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Case Histories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="case-history-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Case History', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'case_id',
            'case_number',
            'cae_history',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
