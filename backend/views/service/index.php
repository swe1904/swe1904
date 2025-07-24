<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Services';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-index">

    <h6><?= Html::encode($this->title) ?></h6>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Service', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],


            'name',
            [
                'attribute'=>'user_id',
                'value'=>function($model){
                    return $model->user->email;
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width:220px;'],
                'header' => 'Actions',
                'template' => '{view}{update}{delete}',
                'buttons' => [

                    'view' => function ($url, $model) {
                        return Html::a('<span class="fa fa-eye"></span> View', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class' => 'class-details btn btn-info btn-xs',
                            'style'=>'margin-left:5px;',
                        ]);
                    },

                    'update' => function ($url, $model) {
                        return Html::a('<span class="fa fa-pencil"></span> Update', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'class'=>'btn btn-primary btn-xs',
                            'style'=>'margin-left:5px;'

                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="fa fa-trash"></span> Delete', $url, [
                            'title' => Yii::t('app', 'Delete'),
                            'class'=>'btn btn-danger btn-xs',
                            'style'=>'margin-left:5px;',
                            'data-method'=>'post',
                            'data-confirm'=>'Are you sure you want to delete this item?'
                        ]);
                    }



                ],
            ],
        ],
    ]); ?>

</div>
