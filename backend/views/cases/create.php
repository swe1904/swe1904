<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Cases */

$this->title = Yii::t('backend', 'Create Case');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Cases'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cases-create">

    <!--<h1><?/*= Html::encode($this->title) */?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'caseTypes' => $caseTypes
    ]) ?>

</div>
