<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\i18n\models\search\I18nMessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'I18n Messages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="i18n-message-index">

    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php echo Html::a(Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'I18n Message',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [


            ['attribute' => 'id',
                'filterInputOptions' => [
                    'class' => 'form-control search',
                    'placeholder' => 'Id'
                ],
                'headerOptions' => ['class' => 'abc'],
            ],
            [
                'attribute'=>'language',
                'filter'=>  Html::activeDropDownList($searchModel, 'language',$languages,['prompt'=>'- Language -','class' => 'form-control search']),
                'headerOptions' => ['class' => 'abc'],
                'filterInputOptions' => [
                    'class' => 'form-control search',
                    'placeholder' => 'Language'
                ],
            ],
            [
                'attribute'=>'category',
                'filter'=>  Html::activeDropDownList($searchModel, 'category',$categories,['prompt'=>'- Category -','class' => 'form-control search']),
                'filterInputOptions' => [
                    'class' => 'form-control search',
                    'placeholder' => 'Category'
                ],
                'headerOptions' => ['class' => 'abc'],
            ],

            ['attribute' => 'sourceMessage',
                'filterInputOptions' => [
                    'class' => 'form-control search',
                    'placeholder' => 'Message'
                ],
                'headerOptions' => ['class' => 'abc'],
            ],

            ['attribute' => 'translation',
                'filterInputOptions' => [
                    'class' => 'form-control search',
                    'placeholder' => 'Translation',
                    'format' => 'ntext',
                ],
                'headerOptions' => ['class' => 'abc'],
            ],
            ['class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['class' => 'abc'],
                'template'=>'{update} {delete}',

            'buttons'=>[
                'delete' => function($url, $model){
                    return Html::a('<span class="btn btn-default fa fa-trash"></span>', $url, [
                        'class' => '',
                        'data' => [
                            'confirm' => 'Are you sure ?',
                            'method' => 'post',
                        ],
                    ]);
                },

                'update' => function($url, $model){
                  //  $url = Yii::$app->urlManager->createUrl(['index', 'id' => $model->id]);
                    return'<a class="btn btn-default edit" href="'.$url.'" title="Update"><i class="fa fa-pencil-square-o"></i></a>';
                },
            ],
            'template' => '{update}&nbsp; {delete}',
             ],
        ],

    ]); ?>

</div>
<style>
    #i18nmessagesearch-category,#i18nmessagesearch-language{
        margin-top: 10px;
    }
</style>