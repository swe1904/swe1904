<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\polling\models\search\base\PollingQuizTeam */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Polling Quiz Teams';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="polling-quiz-team-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php echo Html::a('Create Polling Quiz Team', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'polling_quiz_id',
            'name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
