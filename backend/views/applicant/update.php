<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Applicant */
if($model->parent_id)
    $this->title = 'Update Dependent';
else
    $this->title = 'Update Applicant';

$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '';
?>
<div class="applicant-update">
    <!-- Basic design -->
    <div class="col-md-12">
        <div class="panel panel-default card-view panel-refresh">
            <div class="panel-hading">
<!--                <h1>Update applicant field</h1>-->
            </div>
            <div class="row">
                <?= $this->render('_form', [
                'model' => $model,
               // 'model_case' => $model_case,
                ]) ?>
            </div>
        </div>
    </div>

</div>