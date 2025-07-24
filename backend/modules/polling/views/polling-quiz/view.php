<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\modules\polling\models\PollingQuiz */

$this->title = $model->name;
$pollingQuizId=$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Questionnaire', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col-md-12">
    <div class="panel panel-default card-view panel-refresh">
        <div class="panel-hading">
            <p class="mb-10">
                <?php echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-rounded btn-primary mr-10']) ?>
                <?php echo Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-rounded btn-danger mr-10',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
                <?php echo Html::a('Add Question', ['polling-quiz-question/create', 'pqi'=>$_GET['id']], ['class' => 'btn btn-rounded btn-success mr-10']) ?>
            </p>
        </div>
        <?php echo DetailView::widget([
            'model' => $model,
            'attributes' => [
                //'id',
                //'user_id',
                'name',
                'description:ntext',
                //'uuid',
                //'quiz_reminder_is',
                //'is_deleted',
                //'master',
                //'disable_edit',
//            'created_at',
                [
                    'attribute'=>'created_at',
                    'format'=>['date', 'm/d/Y'],
                ],
                'polling_id',
                [
                    'label' => 'Questionnaire Link',
                    'value' => "<a href='http://pangeaportal.com/backend/web/polling/polling-quiz/play-quiz?id=$model->polling_id&c_id=%ClientId%' target='_blank'>http://pangeaportal.com/backend/web/polling/polling-quiz/play-quiz?id=$model->polling_id&c_id=%ClientId%</a>"
                ],
            ],
            'template' => function ($attribute, $index) {
                return  '<tr><th width="20%">'.$attribute["label"].'</th><td>'.$attribute["value"].'</td></tr>';
            },
        ]) ?>
    </div>
</div>


<div class="col-md-12">
    <div class="panel panel-default card-view panel-refresh">
        <div class="panel-hading">
            <div class="pull-left">
                <p><i class="fa fa-warning"></i> Please make sure you fill all required Applicant Attributes for Questionnaire to be sent.</p>
            </div>
            <div class="clearfix"></div>
        </div>
        <?php echo GridView::widget([
            'dataProvider' => $pollingQuizQuestionDataProvider,
            'filterModel' => $pollingQuizQuestionSearchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn',
                    'headerOptions' => ['class' => 'abc'],],
                // 'question:ntext',
                [ 'headerOptions' => ['class' => 'abc'],
                    'attribute' => 'question',
                    'filterInputOptions' => [
                        'class' => 'form-control search',
                        'placeholder' => 'Question',
                    ],
                ],
                ['headerOptions' => ['class' => 'abc'],
                    'attribute' => 'questionType',
                    'format' => 'raw',
                    'filterInputOptions' => [
                        'class' => 'form-control search',
                        'placeholder' => 'Question Type',

                    ],
                    'value' => function ($model) {
                        return $model->pollingQuizQuestionType->name;
                    },
                ],
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
                [ 'headerOptions' => ['class' => 'abc'],
                    'controller' => 'polling-quiz-question',
                    'class' => 'yii\grid\ActionColumn',
                    'buttons'=> [
                        'delete' => function($url, $model){
                            $url = Yii::$app->urlManager->createUrl(['/polling/polling-quiz-question/delete', 'id' => $model->id]);
                            return'<a class="mr-25" data-method="post" href="'.$url.'" title="Delete"><i class="fa fa-close text-danger"></i></a>';
                        },
                        'view' => function($url, $model){
                            $url = Yii::$app->urlManager->createUrl(['/polling/polling-quiz-question/view', 'id' => $model->id]);
                            return'<a class="mr-25"   href="'.$url.'" title="View"> <i class="fa fa-eye text-primary"></i></a>';
                        },
                        'update' => function($url, $model){
                            $url = Yii::$app->urlManager->createUrl(['/polling/polling-quiz-question/update', 'id' => $model->id]);
                            return'<a class="mr-25"   href="'.$url.'" title="Update"> <i class="fa fa-pencil text-success"></i></a>';
                        },
                    ],
                    'template'=>'{view}&nbsp;&nbsp;&nbsp;{update}&nbsp;&nbsp;&nbsp;{delete}',
                ],

            ],
        ]); ?>
    </div>
</div>
