<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\CaseHistory */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Case History',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Case Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="case-history-update">

    <div class="col-md-12">
        <div class="panel panel-default card-view panel-refresh">
            <div class="panel-hading">
                <h6><?= Html::encode($this->title) ?></h6>
            </div>
            <?= $this->render('_form', [
                'model' => $model, 'data'=>$data
            ]) ?>
        </div>
    </div>

</div>
