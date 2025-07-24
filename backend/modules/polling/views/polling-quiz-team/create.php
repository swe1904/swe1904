<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\polling\models\base\PollingQuizTeam */

$this->title = 'Create Polling Quiz Team';
$this->params['breadcrumbs'][] = ['label' => 'Polling Quiz Teams', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="polling-quiz-team-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
