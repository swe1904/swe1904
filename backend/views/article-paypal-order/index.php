<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ArticlePaypalOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Article Paypal Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-paypal-order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Article Paypal Order', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'article_request_id',
            'article_id',
            'article_request_author_id',
            'article_author_id',
            // 'amount',
            // 'paymentId',
            // 'created_at',
            // 'updated_at',
            // 'author_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
