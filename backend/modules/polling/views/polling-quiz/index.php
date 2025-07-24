<?php

use app\components\GlobalConstant;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\polling\models\search\PollingQuizSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Questionnaire';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
<div class="col-md-12">
    <div class="panel panel-default card-view panel-refresh">
        <!-- <div class="panel-heading">

        </div> -->
        <?php echo $this->render('_form', [
            'model' => $model,
        ]) ?>
        <?php echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions'=>['class'=>'table data-table'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn',  'headerOptions' => ['class' => 'abc']],

     //            'id',
     //            'user_id',
     //            'name',
                [ 'headerOptions' => ['class' => 'abc','style' => ''],
                    'label' => false,
                    'attribute' => 'name',
                    // 'headerOptions' => ['style' => 'width:30%'],
                    'filterInputOptions' => [
                        'class' => 'form-control border search',
                        'placeholder' => 'Name',

                    ],
                    'format'=>'raw',
                    'value' => function ($dataProvider) {
                        return Html::a($dataProvider->name, Yii::$app->getUrlManager()->getBaseUrl().'/polling/polling-quiz/update?id=' . $dataProvider->id);
                    },
                ],
                [ 'headerOptions' => ['class' => 'abc','style' => 'width:60%'],
                    'label' => false,
                    'attribute' => 'description',
                    //  'headerOptions' => ['style' => 'width:57%'],
                    'filterInputOptions' => [
                        'class' => 'form-control border search',
                        'placeholder' => 'Description',

                    ],
                    'format'=>'raw',
                    'value' => function ($dataProvider) {
                        return Html::a($dataProvider->description, Yii::$app->getUrlManager()->getBaseUrl().'/polling/polling-quiz/update?id=' . $dataProvider->id);
                    },
                ],
                //'description:ntext',
                //'type',
                // 'uuid',
                // 'quiz_reminder_is',
                // 'is_deleted',
                // 'master',
                // 'disable_edit',
                // 'created_at',

                [ 'headerOptions' => ['class' => 'abc'],
                    'contentOptions' => ['style' => GlobalConstant::ACTION_STYLE],
                    'class' => 'yii\grid\ActionColumn',
                    'buttons'=> [
                        'add-question'=>function($url, $model){
                            $url = Yii::$app->urlManager->createUrl(['/polling/polling-quiz-question/create','pqi'=>$model->id]);
                            return'<a class="mr-15" href="'.$url.'" title="Add Question"><i class="fa fa-plus txt-violet"></i></a>';
                        },
                        /*     'delete' => function($url, $model){
                              $url = Yii::$app->urlManager->createUrl(['/polling/polling-quiz/delete', 'id' => $model->id]);
                              return'<a class="btn btn-default edit" href="'.$url.'" title="Delete"><i class="fa fa-trash"></i></a>';
                          },*/
                        'delete' => function($url, $model){
                            return Html::a('<span class="mr-15"><i class="fa fa-close text-danger"></i></span>', ['delete', 'id' => $model->id], [
                                'class' => '',
                                'data' => [
                                    'confirm' => 'Are you absolutely sure ? You will lose all the information about this user with this action.',
                                    'method' => 'post',
                                ],
                            ]);
                        },
                        'view' => function($url, $model){
                            $url = Yii::$app->urlManager->createUrl(['/polling/polling-quiz/view', 'id' => $model->id]);
                            return'<a class="mr-15" href="'.$url.'" title="View Questions"><i class="fa fa-eye text-primary"></i></a>';
                        },
                        'update' => function($url, $model){
                            $url = Yii::$app->urlManager->createUrl(['/polling/polling-quiz/update', 'id' => $model->id]);
                            return'<a class="mr-15" href="'.$url.'" title="Update"><i class="fa fa-pencil text-success"></i></a>';
                        },
                    ],
                    'template'=>'{view}{update}{delete}{add-question}',
                ],
            ],
        ]); ?>

    </div>
</div>
</div>