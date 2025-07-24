<?php

use app\models\ClientEntity;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\components\GlobalConstant;

/** @var yii\web\View $this */
/** @var app\models\search\ClientEntitySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('backend', 'Client Entities');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-entity-index">
    <div class="col-md-12">
        <div class="panel panel-default card-view panel-refresh">
            <h6 class="panel-title txt-dark"><?= Html::encode($this->title) ?></h6>
            <?php if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT){ ?>
            <p style="margin: 15px 0px;">
                <?= Html::a(Yii::t('backend', 'Create Client Entity'), ['create'], ['class' => 'btn btn-success btn-rounded']) ?>
            </p>
            <?php } ?>
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn'
                    ],
                    'name',
                    [
                        'attribute'=> 'client_id',
                        'label' => 'Client',
                        'value' => function ($model) {
                            return $model->client->client_name;
                        }
                    ],
                    'address',
                    // 'cr_number',
                    // 'unified_national_number',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'headerOptions' => ['width' => '200px'],
                        'buttons' => [
                            'view' => function ($url, $model) {
                                $url = Yii::$app->urlManager->createUrl(['/client-entity/view','id'=> $model->id]);
                                return '<a class="mr-15" title="View" href="' . $url . '"><i class="fa fa-eye" style="color: orange;"></i></a>';
                            },
                            'update' => function ($url, $model) {
                                $url = Yii::$app->urlManager->createUrl(['/client-entity/update','id'=> $model->id]);
                                return '<a class="mr-15" title="Update" href="' . $url . '"><i class="fa fa-pencil text-success text-inverse"></i></a>';
                            },
                            'delete' => function ($url, $model) {
                                $url = Yii::$app->urlManager->createUrl(['/client-entity/delete','id'=> $model->id]);
                                return '<a class="mr-15" href="' . $url . '" data-method="post" data-confirm="Are you sure you want to delete this item?" ,="" title="Delete"><i class="fa fa-close text-danger"></i></a>';
                            },
                        ],
                        'template' => '{view} {update} {delete}'
                    ]
                    // [
                        // 'class' => ActionColumn::className(),
                        // 'urlCreator' => function ($action, ClientEntity $model, $key, $index, $column) {
                        //     return Url::toRoute([$action, 'id' => $model->id]);
                        //  }
                    // ],
                ],
            ]); ?>
        </div>
    </div>
</div>
