<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Applicant */
$params = Yii::$app->request->queryParams;
if(isset($params['parent_id'])&& !empty($params['parent_id']))
{
    $this->title = 'Create Dependent';
    $model->parent_id = $params['parent_id'];
}
else
{
    $this->title = 'Create Applicant';
}
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="applicant-create">

    <div class="col-md-12 ">
        <div class="panel panel-default card-view panel-refresh">
            <div class="panel-hading">
            </div>
            <div class="row">
                <?= $this->render('_form', [
                    'model' => $model,
                    //'model_case' => $model_case
                ]) ?>
            </div>
        </div>
    </div>

</div>
