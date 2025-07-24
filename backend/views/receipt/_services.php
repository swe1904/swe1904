<?php 
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use backend\models\search\CaseTypeServicePriceSearch;

$params['CaseTypeServicePriceSearch']['case_type_pricing_id'] = $id;

$searchModel = new CaseTypeServicePriceSearch();
$dataProvider = $searchModel->search($params);
?>

<?= GridView::widget([
    'filterOnFocusOut' => false,
    'layout' => "\n{items}\n{pager}\n{summary}",
    'dataProvider' => $dataProvider,
    'responsive' => true,
    'responsiveWrap' => false,
    'bordered' => false,
    // 'options' => [
    //     'class' => 'table-responsive'
    // ],
     // 'tableOptions'=>['class'=> 'table data-table '],
     'tableOptions' => [
        'class' => 'table data-table kv-grid-table',
        'style' => 'width: 100%;', // Ensure the table takes the full width
    ],
    'tableOptions' => ['class' => 'table'],
    'columns' => [
        'service_name',
        'price',
        [
            'class' => 'yii\grid\ActionColumn',
            'contentOptions' => ['style' => 'min-width:120px'],
            'template' => '{delete}',

            'buttons' => [

                'view' => function ($url, $subtask) {
                    if(isset($subtask->project->id) && isset($subtask->module_id) && isset($subtask->id))
                    return Html::a('<span class="ti-search"></span> ', Url::to(['default/view','id'=>$subtask->project->id,'module_id'=>$subtask->module_id,'point_id'=>$subtask->parent_id, 'subtask_id'=>$subtask->id]), [
                        'title' => Yii::t('app', 'View'),
                        'class' => 'class-details action-btn btn-success btn-xs px-2',
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'top',
                    ]);
                },

                
                // 'delete' => function ($url, $model){
                //     return Html::a('<span class="ti-trash"></span> ', $url, [
                //         'title' => Yii::t('app', 'Delete'),
                //         'class' => 'action-btn btn-danger btn-xs px-2',
                //         'data-toggle' => 'modal',
                //         'data-target' => '#delete-box',
                //         'data-id' => $model->id,
                //         'onclick' => 'deleteClicked("service",'.$model->id.',"'.$pjaxId.'")'
                //     ]);
                // }
                'delete' => function($url, $model){
                        $url = Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/service-delete', 'id' => $model->id]);
                        return '<a class="mr-25" href="'.$url.'" data-method="post" data-confirm = "'.Yii::t('yii', 'Are you sure you want to delete this item?').'",  title="Delete"><i class="fa fa-trash text-danger"></i></a>';
                    },

            ],               
        ],
        // 'created_at',
        // 'updated_at',
    ]
    ]);
?>
<style>
   /* Ensure the expanded row div takes the full width */
.kv-expanded-row {
    width: 100%;
    padding: 0 !important;
    margin: 0 !important;
}

/* Ensure the inner table takes the full width */
.kv-expand-detail-row .kv-grid-table {
    width: 100%;
    table-layout: fixed; /* Prevents shrinking */
}

/* Set the container div to full width */
.kv-grid-container {
    width: 100%;
    overflow-x: auto; /* Ensure responsiveness */
}

/* Ensure the inner <td> elements take the full width */
.kv-expand-detail-row td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>