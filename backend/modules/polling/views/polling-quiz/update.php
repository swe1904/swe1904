<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\polling\models\PollingQuiz */

$this->title = 'Update Questionnaire: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Questionnaire', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>


<div class="col-md-12">
    <div class="panel panel-default card-view panel-refresh">
        <div class="panel-hading">

        </div>
        <div class="row">

            <?php echo $this->render('_form_new', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>
