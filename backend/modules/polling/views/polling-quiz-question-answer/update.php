<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\polling\models\PollingQuizQuestionAnswer */

$this->title = 'Update Polling Quiz Question Answer: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Polling Quiz Question Answers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="polling-quiz-question-answer-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
