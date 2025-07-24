<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\polling\models\base\PollingQuizTeam */

$this->title = 'Update Polling Quiz Team: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Polling Quiz Teams', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="polling-quiz-team-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
