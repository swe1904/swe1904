<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\polling\models\PollingQuizQuestionAnswer */

$this->title = 'Create Polling Quiz Question Answer';
$this->params['breadcrumbs'][] = ['label' => 'Polling Quiz Question Answers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="polling-quiz-question-answer-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
