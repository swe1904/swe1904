<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\PaypalRecurringPaymentsProfile */

$this->title = 'Create Paypal Recurring Payments Profile';
$this->params['breadcrumbs'][] = ['label' => 'Paypal Recurring Payments Profiles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="paypal-recurring-payments-profile-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
