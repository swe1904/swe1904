<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\polling\models\search\base\PollingQuizQuestionAnswerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Polling Quiz Question Answers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="polling-quiz-question-answer-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php echo Html::a('Create Polling Quiz Question Answer', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'participant_id',
            'polling_quiz_question_id',
            'answer',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
