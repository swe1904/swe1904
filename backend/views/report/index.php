<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 11/1/2019
 * Time: 11:41 AM
 */

use app\components\GlobalConstant;
use backend\models\Cases;
use backend\models\CaseType;
use yii\bootstrap\ButtonDropdown;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

?>
<style>
    .report-grid{
        height: 40vh;
        overflow: scroll;
    }
</style>
<?php $template = '{download-pdf}';?>
<div class="col-md-12">
    <!--Progress-->
    <div class="col-md-4">
        <div class="panel panel-default card-view border-panel panel-refresh">
            <div class="panel-heading active">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">Case in progress</h6>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="refresh-container">
                <div class="la-anim-1"></div>
            </div>

            <?php \yii\widgets\Pjax::begin(['id'=>'case-progress', 'timeout' => 0,'enablePushState'=>false]); ?>
            <div class="report-grid">
            <?= GridView::widget([
                'dataProvider' => $dataProviderCasesProgress,
//        'filterModel' => $searchModel,
                'options' => [
                    'class' => 'grid-view'
                ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

//            'id',Cases::find()->asArray()->all()
//           'case_id',
                    [ 'attribute' =>  'case_id',
                        'filter'=>ArrayHelper::map(Cases::find()->asArray()->all(), 'id', 'case_number'),
                        'label' => 'Case',
                        'filterInputOptions' => [
                            'class' => 'form-control search',
                            'prompt' => (new Cases)->getAttributeLabel('case_number'),
                        ],
                        'value' => 'case.case_number'
                    ],
//                    [
//                        'attribute' =>  'created_at',
//                        'label' => 'Log Time'
//                    ],
//                    [
//                        'attribute' =>  'case_status',
//                        'label' => 'Case Status'
//                    ],
                    [
                        'attribute' =>  'case_step_status',
                        'label' => 'Status',
                        'value' => function ($model) {
                            if(isset($model->case_step_status)){
                                return GlobalConstant::CASE_STEP_STATUS_ARRAY[$model->case_step_status];
                            }
                            else
                                return $model->case_step_status;
                        },
                    ],
                    [
                        'attribute' =>  'msg',
                        'label' => 'Notes'
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                        'headerOptions' => ['class' => 'abc'],
                        'contentOptions' => ['style' => 'min-width:100px'],
                        'buttons'=>[
                            'steps'=>function($url, $model){
                                $url=Yii::$app->urlManager->createUrl(['/case-steps/index','CaseStepsSearch[case_id]'=> $model->case_id]);
                                return'<a class="mr-15" href="'.$url.'" title="Show Steps" target="_blank"><i class="fa fa-list text-primary"></i></a>';
                            },
                            'history'=>function($url, $model){
                                $url=Yii::$app->urlManager->createUrl(['/case-history/','CaseHistorySearch[case_id]'=>$model->case_id]);
                                return'<a class="mr-15" href="'.$url.'" title="history" target="_blank"><i class="fa fa-undo txt-grey"></i></a>';
                            }
                        ],
                        'template' => '{steps} {history}',
                    ],

                ],
            ]); ?>
            <?php \yii\widgets\Pjax::end(); ?>
            </div>
        </div>
    </div>
    <!--Delayed-->
    <div class="col-md-4">
        <div class="panel panel-default card-view border-panel panel-refresh">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">Case In progress : Delayed</h6>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="refresh-container">
                <div class="la-anim-1"></div>
            </div>
            <?php \yii\widgets\Pjax::begin(['id'=>'case-inprogress-delayed', 'timeout' => 0,'enablePushState'=>false]); ?>
            <div class="report-grid">
            <?= GridView::widget([
                'dataProvider' => $dataProviderCasesCompletedDelayed,
//        'filterModel' => $searchModel,
                'options' => [
                    'class' => 'grid-view'
                ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

//            'id',Cases::find()->asArray()->all()
//           'case_id',
                    [ 'attribute' =>  'case_id',
                        'filter'=>ArrayHelper::map(Cases::find()->asArray()->all(), 'id', 'case_number'),
                        'label' => 'Case',
                        'filterInputOptions' => [
                            'class' => 'form-control search',
                            'prompt' => (new Cases)->getAttributeLabel('case_number'),
                        ],
                        'value' => 'case.case_number'
                    ],
//                    [
//                        'attribute' =>  'created_at',
//                        'label' => 'Log Time'
//                    ],
//                    [
//                        'attribute' =>  'case_status',
//                        'label' => 'Case Status'
//                    ],
                    [
                        'attribute' =>  'case_step_status',
                        'label' => 'Status',
                        'value' => function ($model) {
                            if(isset($model->case_step_status)){
                                return GlobalConstant::CASE_STEP_STATUS_ARRAY[$model->case_step_status];
                            }
                            else
                                return $model->case_step_status;
                        },
                    ],
                    [
                        'attribute' =>  'msg',
                        'label' => 'Notes'
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                        'headerOptions' => ['class' => 'abc'],
                        'contentOptions' => ['style' => 'min-width:100px'],
                        'buttons'=>[
                            'steps'=>function($url, $model){
                                $url=Yii::$app->urlManager->createUrl(['/case-steps/index','CaseStepsSearch[case_id]'=> $model->case_id]);
                                return'<a class="mr-15" href="'.$url.'" title="Show Steps" target="_blank"><i class="fa fa-list text-primary"></i></a>';
                            },
                            'history'=>function($url, $model){
                                $url=Yii::$app->urlManager->createUrl(['/case-history/','CaseHistorySearch[case_id]'=>$model->case_id]);
                                return'<a class="mr-15" href="'.$url.'" title="history" target="_blank"><i class="fa fa-undo txt-grey"></i></a>';
                            }
                        ],
                        'template' => '{steps} {history}',
                    ],
                ],
            ]); ?>
            </div>
            <?php \yii\widgets\Pjax::end(); ?>
        </div>
    </div>
    <!--On time-->
    <div class="col-md-4">
        <div class="panel panel-default card-view border-panel panel-refresh">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">Case Completed</h6>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="refresh-container">
                <div class="la-anim-1"></div>
            </div>
            <?php \yii\widgets\Pjax::begin(['id'=>'case-completed', 'timeout' => 0,'enablePushState'=>false]); ?>
            <div class="report-grid">
            <?= GridView::widget([
                'dataProvider' => $dataProviderCasesCompletedOnTime,
//        'filterModel' => $searchModel,
                'options' => [
                    'class' => 'grid-view'
                ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

//            'id',Cases::find()->asArray()->all()
//           'case_id',
                    [ 'attribute' =>  'case_id',
                        'filter'=>ArrayHelper::map(Cases::find()->asArray()->all(), 'id', 'case_number'),
                        'label' => 'Case',
                        'filterInputOptions' => [
                            'class' => 'form-control search',
                            'prompt' => (new Cases)->getAttributeLabel('case_number'),
                        ],
                        'value' => 'case.case_number'
                    ],
//                    [
//                        'attribute' =>  'created_at',
//                        'label' => 'Log Time'
//                    ],
//                    [
//                        'attribute' =>  'case_status',
//                        'label' => 'Case Status'
//                    ],
                    [
                        'attribute' =>  'case_step_status',
                        'label' => 'Status',
                        'value' => function ($model) {
                            if(isset($model->case_step_status)){
                                return GlobalConstant::CASE_STEP_STATUS_ARRAY[$model->case_step_status];
                            }
                            else
                                return $model->case_step_status;
                        },
                    ],
                    [
                        'attribute' =>  'msg',
                        'label' => 'Status'
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                        'headerOptions' => ['class' => 'abc'],
                        'contentOptions' => ['style' => 'min-width:100px'],
                        'buttons'=>[
                            'steps'=>function($url, $model){
                                $url=Yii::$app->urlManager->createUrl(['/case-steps/index','CaseStepsSearch[case_id]'=> $model->case_id]);
                                return'<a class="mr-15" href="'.$url.'" title="Show Steps" target="_blank"><i class="fa fa-list text-primary"></i></a>';
                            },
                            'history'=>function($url, $model){
                                $url=Yii::$app->urlManager->createUrl(['/case-history/','CaseHistorySearch[case_id]'=>$model->case_id]);
                                return'<a class="mr-15" href="'.$url.'" title="history" target="_blank"><i class="fa fa-undo txt-grey"></i></a>';
                            }
                        ],
                        'template' => '{steps} {history}',
                    ],
                ],
            ]); ?>
            </div>
            <?php \yii\widgets\Pjax::end(); ?>
        </div>
    </div>

</div>


<!-- Billing -->
<div class="col-md-12">
    <!--Quote-->
    <div class="col-md-4">
        <div class="panel panel-default card-view border-panel panel-refresh">
            <div class="panel-heading active">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">Quote</h6>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="refresh-container">
                <div class="la-anim-1"></div>
            </div>
            <?php \yii\widgets\Pjax::begin(['id'=>'report-quote','enablePushState'=>false]); ?>
            <div class="report-grid">
            <?= GridView::widget([
                'dataProvider' => $dataProviderQuote,
   //             'filterModel' => $searchModel,
                'options' => [
                    'class' => 'grid-view'
                ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn',
                        'headerOptions' => ['class' => 'abc']],

                    [
                        'attribute'=>'receipt_number',
                        'label' => 'Quote Number',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => 'Serial Number'
                        ]
                    ],
                    ['attribute'=>'set_client_name',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => 'Set Client Name'
                        ]
                    ],
                    ['attribute'=>'set_mobile',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => 'Set Mobile No.'
                        ]
                    ],
                    ['attribute'=>'set_email',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => 'Set Email'
                        ]
                    ],
                    ['attribute'=>'date',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => 'Date'
                        ]
                    ],
                    ['attribute'=>'currency.name',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => 'Currency'
                        ]
                    ],
                    ['attribute'=>'amount',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => 'Amount'
                        ]
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'headerOptions' => ['class' => 'abc'],
                        'template'=>$template,
                        'contentOptions' => ['style' => 'width:350px;'],
                        'buttons' => [
                            'download-pdf' => function ($url, $model) {
                                $url = \yii\helpers\Url::to(['/receipt/sample-pdf/', 'id' => $model->id, 'options'=>'download']);
                                // return Html::dropDownList(['1'=>'abc','2'=>'xyz']);
                                return  ButtonDropdown::widget([
                                    'label' => 'Download',
                                    'options'=>['class' => 'btn btn-default btn-xs waves-effect dropdown-toggle '],
                                    'dropdown' => [
                                        'items' => [
                                            ['label' => 'with VAT',
                                                'linkOptions' => ['target' => '_blank'],
                                                'url' => $url.'&template=1'],
                                            ['label' => 'without VAT',
                                                'linkOptions' => ['target' => '_blank'],
                                                'url' =>$url.'&template=2'],

                                        ],
                                    ],
                                ]);
                            },
                        ],
                    ],
                ],
            ]); ?>
            </div>
            <?php \yii\widgets\Pjax::end(); ?>
        </div>
    </div>
    <!--Invoice-->
    <div class="col-md-4">
        <div class="panel panel-default card-view border-panel panel-refresh">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">Invoice</h6>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="refresh-container">
                <div class="la-anim-1"></div>
            </div>
            <?php \yii\widgets\Pjax::begin(['id'=>'report-invoice','enablePushState'=>false]); ?>
            <div class="report-grid">
            <?= GridView::widget([
                'dataProvider' => $dataProviderInvoice,
   //             'filterModel' => $searchModel,
                'options' => [
                    'class' => 'grid-view'
                ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn',
                        'headerOptions' => ['class' => 'abc']],

                    [
                        'attribute'=>'receipt_number',
                        'label' => 'Invoice Number',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => 'Serial Number'
                        ]
                    ],
                    ['attribute'=>'set_client_name',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => 'Set Client Name'
                        ]
                    ],
                    ['attribute'=>'set_mobile',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => 'Set Mobile No.'
                        ]
                    ],
                    ['attribute'=>'set_email',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => 'Set Email'
                        ]
                    ],
                    ['attribute'=>'date',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => 'Date'
                        ]
                    ],
                    ['attribute'=>'currency.name',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => 'Currency'
                        ]
                    ],
                    ['attribute'=>'amount',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => 'Amount'
                        ]
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'headerOptions' => ['class' => 'abc'],
                        'template'=>$template,
                        'contentOptions' => ['style' => 'width:350px;'],
                        'buttons' => [
                            'download-pdf' => function ($url, $model) {
                                $url = \yii\helpers\Url::to(['/receipt/sample-pdf/', 'id' => $model->id, 'options'=>'download']);
                                // return Html::dropDownList(['1'=>'abc','2'=>'xyz']);
                                return  ButtonDropdown::widget([
                                    'label' => 'Download',
                                    'options'=>['class' => 'btn btn-default btn-xs waves-effect dropdown-toggle '],
                                    'dropdown' => [
                                        'items' => [
                                            ['label' => 'with VAT',
                                                'linkOptions' => ['target' => '_blank'],
                                                'url' => $url.'&template=1'],
                                            ['label' => 'without VAT',
                                                'linkOptions' => ['target' => '_blank'],
                                                'url' =>$url.'&template=2'],

                                        ],
                                    ],
                                ]);
                            },
                        ],
                    ],
                ],
            ]); ?>
            </div>
            <?php \yii\widgets\Pjax::end(); ?>
        </div>
    </div>
    <!--Receipt-->
    <div class="col-md-4">
        <div class="panel panel-default card-view border-panel panel-refresh">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">Receipt</h6>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="refresh-container">
                <div class="la-anim-1"></div>
            </div>
            <?php \yii\widgets\Pjax::begin(['id'=>'report-receipt','enablePushState'=>false]); ?>
            <div class="report-grid">
            <?= GridView::widget([
                'dataProvider' => $dataProviderReceipt,
               // 'filterModel' => $searchModel,
                'options' => [
                    'class' => 'grid-view'
                ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn',
                        'headerOptions' => ['class' => 'abc']],

                    [
                        'attribute'=>'receipt_number',
                        'label' => 'Receipt Number',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => 'Serial Number'
                        ]
                    ],
                    ['attribute'=>'set_client_name',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => 'Set Client Name'
                        ]
                    ],
                    ['attribute'=>'set_mobile',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => 'Set Mobile No.'
                        ]
                    ],
                    ['attribute'=>'set_email',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => 'Set Email'
                        ]
                    ],
                    ['attribute'=>'date',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => 'Date'
                        ]
                    ],
                    ['attribute'=>'currency.name',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => 'Currency'
                        ]
                    ],
                    ['attribute'=>'amount',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => 'Amount'
                        ]
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'headerOptions' => ['class' => 'abc'],
                        'template'=>$template,
                        'contentOptions' => ['style' => 'width:350px;'],
                        'buttons' => [
                            'download-pdf' => function ($url, $model) {
                                $url = \yii\helpers\Url::to(['/receipt/sample-pdf/', 'id' => $model->id, 'options'=>'download']);
                                // return Html::dropDownList(['1'=>'abc','2'=>'xyz']);
                                return  ButtonDropdown::widget([
                                    'label' => 'Download',
                                    'options'=>['class' => 'btn btn-default btn-xs waves-effect dropdown-toggle '],
                                    'dropdown' => [
                                        'items' => [
                                            ['label' => 'with VAT',
                                                'linkOptions' => ['target' => '_blank'],
                                                'url' => $url.'&template=1'],
                                            ['label' => 'without VAT',
                                                'linkOptions' => ['target' => '_blank'],
                                                'url' =>$url.'&template=2'],

                                        ],
                                    ],
                                ]);
                            },
                        ],
                    ],
                ],
            ]); ?>
            </div>
            <?php \yii\widgets\Pjax::end(); ?>
        </div>
    </div>

</div>

