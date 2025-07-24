<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Organisation */

$this->title = 'Update Organisation: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Organisations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>



<?= $this->render('_form', [
    'model' => $model,
    'currencyArray'=>$currencyArray,
    'searchModel' => $searchModel,
    'dataProvider' => $dataProvider,
]) ?>

