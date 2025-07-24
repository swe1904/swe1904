<?php

use backend\models\CaseStatus;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\search\CaseStatusSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('backend', 'Case Statuses');
$this->params['breadcrumbs'][] = ['label' => 'Cases / ' . $this->title];
?>
<div class="row case-status-index">

    <!-- <h1><?//= Html::encode($this->title) ?></h1> -->

    <?php // echo $this->render('_form', ['model' => (new CaseStatus())]) ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="col-md-12">
        <div class="panel panel-default card-view border-panel panel-refresh">
            <div class="refresh-container">
                <div class="la-anim-1"></div>
            </div>
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">Create Case Status</h6>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-wrapper collapse in">
                <div class="panel-body" >
                <?php $form = ActiveForm::begin( [
                        'options' => [
                            'class' => 'form-group',
                            'id'=>'case-status-form',
                        ],
                        'action' => ['create']
                    ],
                    ); ?>
                        <div class="col-md-12 pl-0">
                            <div class="col-md-6">
                                <?= $form->field($model, 'name',
                                    ['template' =>'
                                    <div class="form-group">
                                    <label class="control-label">Case Status</label>
                                    <div class="form-line border">{input}</div>
                                    </div>'
                                    ])->textInput(['maxlength' => 50, 'placeholder' => 'Create Case Status', 'label' => 'Case Status', 'required' => true])
                                    ?>
                            </div>
                        </div>
                        <div class="col-md-2" >
                            <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'btn btn-sm btn-rounded btn-success']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
            </div>
            
        </div>
        <div class="panel panel-default card-view border-panel panel-refresh mt-20">
            <div class="refresh-container">
                <div class="la-anim-1"></div>
            </div>
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">Case Statuses</h6>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-wrapper collapse in">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'tableOptions'=>['class'=>'table data-table'],
                    'columns' => [
                        [   
                            'class' => 'yii\grid\SerialColumn',
                            'headerOptions' => ['class' => 'abc', 'width' => '10%']
                        ],
                        [
                            'attribute' => 'name', 
                            'headerOptions' => ['width: 25%'],
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'headerOptions' => ['width' => '15%'],
                            'buttons'=>[
                                'delete' => function($url, $model){
                                    $url = Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/delete', 'id' => $model->id]);
                                    return '<a class="text-danger edit" href="'.$url.'" data-method="post" data-confirm = "'.Yii::t('yii', 'Are you sure you want to delete this item?').'",  title="Delete"><i class="fa fa-close"></i></a>';
                                },
                                'update' => function($url, $model){
                                    $url = Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/update', 'id' => $model->id]);
                                    return '<a class="mr-25" href="' . $url . '" title="Update"> <i class="fa fa-pencil text-success"></i></a>';
                                },
                            ],
                            'template' => '{update}{delete}',
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
