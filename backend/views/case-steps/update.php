<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\CaseSteps */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Case Steps',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Case Steps'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="case-steps-update">

<!--    <h4>--><?php //echo Html::encode($this->title) ?><!--</h4>-->
<!---->
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
