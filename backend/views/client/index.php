<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ClientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
<div class="col-md-12">
    <div class="panel panel-default border-panel card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark"><?= $model->isNewRecord ? "Create" : "Update"?> Client</h6>
                </div>
                <div class="clearfix"></div>
            </div>
            <?php echo $this->render('_form', [
                'model' => $model,
                'organisations' => $organisations,
                ]) ?>
    </div>
</div>

<div class="client-index">

<div class="col-md-12">
    <div class="panel panel-default card-view panel-refresh">
        <div class="panel-hading">

    <h6><?= Html::encode($this->title) ?></h6>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!-- <p>
        <?//= Html::a('Create', ['create'], ['class' => 'btn btn-rounded btn-success mr-10 mb-20']) ?>
    </p> -->
        
        <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn',
             'headerOptions' => ['class' => 'abc'],
             'contentOptions' => ['style' => 'width: 5%;'],
    ],

           ['attribute'=>'client_name',
                         'filterInputOptions' => [
                             'class' => 'form-control search border',
                             'placeholder' => (new backend\models\Client)->getAttributeLabel('client_name'),
                         ],
                         'contentOptions' => ['style' => 'width: 70%;'],
                     ],
        //    ['attribute'=>'country',
        //                  'filterInputOptions' => [
        //                      'class' => 'form-control search border',
        //                      'placeholder' => (new backend\models\Client)->getAttributeLabel('country'),
        //                  ],
        //              ],
        //    ['attribute'=>'email',
        //                  'filterInputOptions' => [
        //                      'class' => 'form-control search border',
        //                      'placeholder' => (new backend\models\Client)->getAttributeLabel('email'),
        //                  ],
        //              ],
        //    ['attribute'=>'phone',
        //                  'filterInputOptions' => [
        //                      'class' => 'form-control search border',
        //                      'placeholder' => (new backend\models\Client)->getAttributeLabel('phone'),
        //                  ],
        //              ],
        //    ['attribute'=>'address',
        //                  'filterInputOptions' => [
        //                      'class' => 'form-control search border',
        //                      'placeholder' => (new backend\models\Client)->getAttributeLabel('address'),
        //                  ],
        //              ],

            ['class' => 'yii\grid\ActionColumn',
            'headerOptions' => ['class' => 'abc'],
    'contentOptions' => ['style' => 'width:150px;'],
    'buttons'=>[
        'client-entity' => function ($url, $model) {
                         $url = Yii::$app->urlManager->createUrl(['/client-entity/create','client_id'=> $model->id]);
                                return '<a class="mr-15" title="Add Client Entity" href="' .$url . '"><i class="fa fa-user-plus" style="color: orange;"></i></a>';
                            },
    'delete' => function($url, $model){
    $url = Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/delete', 'id' => $model->id]);
    return '<a class="mr-25" href="'.$url.'" data-method="post" data-confirm = "'.Yii::t('yii', 'Are you sure you want to delete this item?').'",  title="Delete"><i class="fa fa-trash text-danger"></i></a>';
    },
   'update' => function($url, $model){
   $url = Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/update', 'id' => $model->id]);
   return'<a class="mr-25" href="'.$url.'" data-method="post" title="Update"><i class="fa fa-pencil-square-o text-success"></i></a>';
    },
    'link'=>function($url, $model){
$url=Yii::$app->urlManager->createUrl(['/applicant/index','client_id'=> $model->id]);
return'<a class="btn btn-default edit" href="'.$url.'" title="Applicants"><i class="fa fa-user"></i></a>';
},

    'view' => function ($url, $model) {
        $url = Yii::$app->urlManager->createUrl(['client/view', 'id' => $model->id]);
        return'<a class="mr-25" href="'.$url.'" title="View"><i class="fa fa-eye" style="color: orange"></i></a>';
    },    

],
            'template' => '{client-entity}{view} {update} {delete}',
            ],
        ],
    ]); ?>
    </div>
</div>
</div>


