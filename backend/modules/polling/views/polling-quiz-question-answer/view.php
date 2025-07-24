<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\polling\models\PollingQuizQuestionAnswer */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Polling Quiz Question Answers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="polling-quiz-question-answer-view">

    <p>
        <?php echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php echo Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'participant_id',
            'polling_quiz_question_id',
            'answer',
        ],
    ]) ?>

</div>
