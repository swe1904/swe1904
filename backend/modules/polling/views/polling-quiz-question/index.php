<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\polling\models\search\base\PollingQuizQuestionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Questionnaire Questions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="polling-quiz-question-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php echo Html::a('Create Polling Quiz Question', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'polling_quiz_id',
            'title',
            'question:ntext',
            'type',
            // 'order',
            // 'action',
            // 'action_compare',
            // 'action_compare_radio',
            // 'action_compare_text',
            // 'action_value',
            // 'visible',
            // 'visible_quiz_question_id',
            // 'visible_compare',
            // 'visible_value',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
