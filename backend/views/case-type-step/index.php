<?php

use backend\models\CaseTypeStep;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\CaseTypeStepSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$caseTypeName='';
if(!empty($_GET['CaseTypeStepSearch']['case_type_id'])){
$caseType=backend\models\CaseType::find()->where(['id'=>$_GET['CaseTypeStepSearch']['case_type_id']])->all();
if(!empty($caseType)) {
    $caseTypeName = ": ".$caseType[0]->name;
}
}else {
    $caseType = backend\models\CaseType::find()->all();
}
$this->title = Yii::t('backend', 'Case Type').' '.$caseTypeName;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="case-type-step-create">
    <?= $this->render('_form', [
        'model' => $model,
        'caseType'=>$caseType
    ]) ?>
</div>

<div class="col-md-12">
    <div class="panel panel-default card-view panel-refresh">
        <div class="panel-heading">
            <h6 style="color: #ffffff !important;"><?=  Html::encode(Yii::t('backend', 'Steps')).'</u>'; ?></h6>
        </div>

        <?php if(!empty($_GET['CaseTypeStepSearch']['case_type_id'])){
            //for a partcular casetype
            $gridClass= 'himiklab\sortablegrid\SortableGridView';
        }else {// for all casestype
            $gridClass='yii\grid\GridView';
        }
        echo $gridClass::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'headerOptions' => ['class' => 'abc'],
                    ],

                   /* [ 'attribute' =>  'id',
                        'filterInputOptions' => [
                            'class' => 'form-control search',
                            'placeholder' => (new CaseTypeStep)->getAttributeLabel('id'),
                    ]],

                    [ 'attribute' =>  'case_type_id',
                        'filterInputOptions' => [
                            'class' => 'form-control search',
                            'placeholder' => (new CaseTypeStep)->getAttributeLabel('case_type_id'),
                    ]],*/

                    [
                        'attribute' =>  'name',
                        'filterInputOptions' => [
                            'class' => 'form-control search border',
                            'placeholder' => (new CaseTypeStep)->getAttributeLabel('name'),
                        ]
                    ],

                    [
                        'attribute' =>  'number_of_days',
                        'filterInputOptions' => [
                            'class' => 'form-control search border',
                            'placeholder' => (new CaseTypeStep)->getAttributeLabel('number_of_days'),
                        ]
                    ],

           /* [ 'attribute' =>  'order',
                'filterInputOptions' => [
                    'class' => 'form-control search',
                    'placeholder' => (new CaseTypeStep)->getAttributeLabel('order'),
                ]],*/

                ['class' => 'yii\grid\ActionColumn',
                    'headerOptions' => ['class' => 'abc'],
                    'buttons'=>[
                        'delete' => function($url, $model){
                            $url = Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/delete', 'id' => $model->id]);
                            return '<a class="mr-25" href="'.$url.'" data-method="post" data-confirm = "'.Yii::t('yii', 'Are you sure you want to delete this item?').'",  title="Delete"><i class="fa fa-close text-danger"></i></a>';
                        },
                        'update' => function($url, $model){
                            $url=Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/update', 'id' => $model->id]);
                      //      $url = Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/update', 'id' => $model->id]);
                            return'<a class="mr-25" href="'.$url.'" data-method="post" title="Update"><i class="fa fa-pencil text-success"></i></a>';
                        },

                    ],
                    'template' => '{update} {delete}',

                ],
            ],
        ]); ?>
    </div>
</div>
<style>

    .panel-heading h6{
        color:white !important;
    }
    table th:last-of-type{
        width: 78px !important;
    }
</style>