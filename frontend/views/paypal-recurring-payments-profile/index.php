<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\PaypalRecurringPaymentsProfileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Paypal Recurring Payments Profiles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="paypal-recurring-payments-profile-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Paypal Recurring Payments Profile', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'receipt_id',
            'profileId',
            'profileStatus',
            'ack',
            // 'payerId',
            // 'token',
            // 'timestamp',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
