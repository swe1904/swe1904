<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\email\models\EmailTemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Email Templates');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default card-view panel panel-refresh">
            <div class="panel-heading">
                <strong>Create new email template</strong>
            </div>
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
        </div>
    </div>



    <div class="col-md-12">
        <div class="panel panel-default card-view panel panel-refresh mt-20">
        
        <div class="panel-heading">
                <strong>Email templates</strong>
        </div>


          <?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions'=>['class' =>'table data-table'],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'user_id',
            //'event_id',
            [ 'attribute' =>  'name',
                'filterInputOptions' => [
                    'class' => 'form-control border search',
                    'style' => 'border-top: 1px solid #ddd;border-left: 1px solid #ddd;border-right: 1px solid #ddd;',
                    'placeholder' => 'Name'
                ]],
            //'from_name',
            // 'from_email:email',
            // 'to_email:email',
            // 'to_name',
            [ 'attribute' =>  'subject',
                'filterInputOptions' => [
                    'class' => 'form-control search',
                    'style' => 'border-top: 1px solid #ddd;border-left: 1px solid #ddd;border-right: 1px solid #ddd;',
                    'placeholder' => 'Subject'
                ]],
            /*[
                'format' => 'raw',
                'attribute' => 'body',
                'value' => function ($model) {
                    return Html::encode($model->body);
                }
            ],*/

            // 'sent_after_day',
            // 'attachment',


            ['class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width: 23%;','class' => 'abc'],
                'contentOptions' => ['style' => 'min-width: 100px;'],
             //   'contentOptions' => ['style' => 'width:150px;  min-width:110px; '],
                'template' => '{view}{update}{delete}',//{download}
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return  Html::a('<i class="fa fa-pencil text-success"></i>', $url, [ 'class' => 'mr-25','title'=>'Update', 'style'=> 'margin-right:10px']);
                    },
                    'view' => function ($url, $model, $key) {
                        return  Html::a('<i class="fa fa-eye text-primary"></i>', $url, [ 'class' => 'mr-25','title'=>'View' ,'style'=> 'margin-right:10px']);
                    },
                    'delete' => function ($url, $model){
                        //   $url .= '&topic_id='.$model->topic_id.'&name='.$name; //This is where I want to append the $lastAddress variable.
                        $url = getenv('BACKEND_URL'). 'polling/email-template/delete?id='.$model->id;
                        return Html::a('<i class="fa fa-close"></i>', $url,[
                            'data-method'=>'POST',
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'class' => 'mr-25 text-danger',
                            'style'=> 'margin-right:10px'
                        ]);

                    }
                    ],
                ],
        ],
          ]); ?>
          <?php Pjax::end(); ?>
       </div>
    </div>
 </div>
<style>
    .content-in-main-panel {
        margin-left: 0px;
        margin-right: 0px;
    }
    .table-bordered{
        padding-top: 10px;
        /*margin-left: 20px;*/
        /*margin-right: 20px;*/
    }
</style>