<?php

use backend\models\CaseStepsNotes;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CaseStepsNotesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Case Steps Notes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="case-steps-notes-index">

    <!--<h1><?/*= Html::encode($this->title) */?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <!--        --><?php //echo Html::a(Yii::t('app', 'Create Case Steps Notes'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php $pjaxUniqueId = 'updateOnPjaxCompleted-' . uniqid(); ?>


    <?php Pjax::begin(['id' => $pjaxUniqueId, 'enablePushState' => false, 'enableReplaceState' => false]); ?>

    <?= $this->render('pjaxIndex', [
        'dataProvider' => $dataProvider,
        'model' => new CaseStepsNotes(),
    ]) ?>

    <?php Pjax::end(); ?>


</div>