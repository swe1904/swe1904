<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\handyrecruiter\models\EmailTemplate */

$this->title = Yii::t('app', 'Create {modelClass} ', [
    'modelClass' => 'Email Template',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Email Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="email-template-create">

<!--    <h3 style="text-align:center;color: #fc7d07a6">--><?php //echo Html::encode($this->title) ?><!--</h3>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
