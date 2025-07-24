<?php

use app\components\GlobalConstant;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\i18n\models\search\I18nSourceMessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Source Messages');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
<div class="col-md-12">
    <div class="panel panel-default card-view border-panel panel-refresh">
        <div class="panel-heading">
            <strong>Create source message</strong>
        </div>
        <?php echo $this->render('_form', [
            'model' => $model
        ]) ?>
    </div>

</div>

<div class="col-md-12">
    <div class="panel panel-default card-view border-panel panel-refresh mt-20">
        <div class="panel-heading">
        <strong> Translations</strong>
        </div>
<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<p>
    <?php /* echo Html::a(Yii::t('backend', '<i class="material-icons">account_circle</i><span> Create {modelClass}</span>', [
'modelClass' => 'Source Message',
]), ['create'], ['class' => 'btn bg-red waves-effect']) */?>
</p>

<?php echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'tableOptions'=>['class'=>'table data-table'],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn', 'headerOptions' => ['class' => 'abc'],],

//            ['attribute' => 'id',
//                'filterInputOptions' => [
//                    'class' => 'form-control search',
//                    'placeholder' => 'Id',
//                ],
//                'headerOptions' => ['class' => 'abc'],
//            ],

//            ['attribute' => 'category',
//                'filterInputOptions' => [
//                    'class' => 'form-control search',
//                    'placeholder' => 'Category',
//                ],
//                'headerOptions' => ['class' => 'abc'],
//            ],

        ['attribute' => 'message',
            'filterInputOptions' => [
                'class' => 'form-control border search',
                'placeholder' => 'Message',
                'format' => 'ntext',
            ],
            'headerOptions' => ['class' => 'abc'],
        ],
        [
            'attribute'=>'arabic',
            'filterInputOptions' => [
                'class'       => 'form-control border search',
                'placeholder' => 'Arabic'
            ],
            'headerOptions' => ['class' => 'abc'],
            'value'=>function ($data) {
                $i18nMessageModel =\backend\modules\i18n\models\I18nMessage::find()->andWhere(['id'=>$data->id])->all();
                if(isset($i18nMessageModel)){
                    foreach($i18nMessageModel as $i18Model) {
                        if($i18Model->language == "ar-AE") {
                            return $i18Model->translation;
                        }
                    }
                }
            },
        ],
        [
            'attribute'=>'espanol',
            'filterInputOptions' => [
                'class'       => 'form-control border search',
                'placeholder' => 'Espanol'
            ],
            'headerOptions' => ['class' => 'abc'],
            'value'=>function ($data) {
                $i18nMessageModel =\backend\modules\i18n\models\I18nMessage::find()->andWhere(['id'=>$data->id])->all();
                if(isset($i18nMessageModel)){
                    foreach($i18nMessageModel as $i18Model) {
                        if($i18Model->language == "es") {
                            return $i18Model->translation;
                        }
                    }
                }
            },
        ],

        ['class' => 'yii\grid\ActionColumn', 'headerOptions' => ['class' => 'abc'],
            'contentOptions' => ['style' => GlobalConstant::ACTION_STYLE],
               'buttons'=>[
                       'view' => function($url, $model){
                return'<a class="mr-25" href="'.$url.'" title="View"><i class="fa fa-eye text-primary"></i></a>';
            },
            'delete' => function($url, $model){
                return Html::a('<span class="mr-25"><i class="fa fa-close text-danger"></i></span>', $url, [
                    'class' => '',
                    'data' => [
                        'confirm' => 'Are you sure ?',
                        'method' => 'post',
                    ],
                    'title'=>"Delete"
                ]);
            },
            'update' => function($url, $model){
                return'<a class="mr-25" href="'.$url.'" title="Update"><i class="fa fa-pencil text-success"></i></a>';
            },
        ],
        'template' => '{view}&nbsp;{update}&nbsp; {delete}',
         ],

    ],
]); ?>

</div>
</div>

</div>