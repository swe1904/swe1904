<?php

use backend\models\KnowledgeModule;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use backend\models\CaseType;

/** @var yii\web\View $this */
/** @var backend\models\search\KnowledgeModuleSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Knowledge Module';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="knowledge-module-index">
    <?= GridView::widget([
        'dataProvider' => $caseTypeDataProvider,
        'filterModel' => $caseTypeSearchModel,
        'tableOptions'=>['class'=>'table data-table'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['style' => 'width: 20%']
            ],

            [ 'attribute' =>  'name',
                'filterInputOptions' => [
                    'style' => 'border-left: 1px solid #eee;border-right: 1px solid #eee;border-top: 1px solid #eee;',
                    'class' => 'form-control search',
                    'placeholder' => (new CaseType)->getAttributeLabel('search'),
            ]],

            [
                'label' => 'Number of Queries',
                'value' => function ($row) {
                    $count = (new KnowledgeModule)->find()->where(['case_type_id' => $row->id])->count();
                    return $count;
                }
            ],

            ['class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['class' => 'abc'],
                'buttons'=> [
                    'queries'=>function($url, $model) {
                        $url = Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/view', 'caseTypeID' => $model->id]);
                        return'<a class="mr-25" href="'.$url.'" title="Queries"><i class="fa fa-eye m-r-10 text-primary";"></i></a>';
                    }

                ],
                'template' => '{queries}',
                'header' => '<strong>ACTION</strong>'
            ],
        ],
    ]); ?>

</div>
