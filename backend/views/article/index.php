<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Articles');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!--    <p>-->
    <!--        --><?php //echo Html::a(
    //            Yii::t('backend', 'Create {modelClass}', ['modelClass' => 'Article']),
    //            ['create'],
    //            ['class' => 'btn btn-success']) ?>
    <!--    </p>-->

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        //   'filterModel' => $searchModel,
        'columns' => [

//            'id',
//            'slug',
//            'title',
//            [
//                'attribute'=>'category_id',
//                'value'=>function ($model) {
//                    return $model->category ? $model->category->title : null;
//                },
//                'filter'=>\yii\helpers\ArrayHelper::map(\common\models\ArticleCategory::find()->all(), 'id', 'title')
//            ],
            [
                'attribute' => 'article_request_author_id',
                'value' => function ($model) {
                    //   $id=$model->article_request_id;
                    $user_id = $model->articleRequest->author_id;
                    $user_details = User::find()->where(['id' => $user_id])->one();
                    return $user_details->username;
                    // return $model->author->username;
                }
            ],
            [
                'attribute' => 'Title',
                'value' => function ($model) {
                    //   $id=$model->article_request_id;
                    $title = $model->articleRequest->title;
                    return $title;
                    // return $model->author->username;
                }
            ],
            [
                'attribute' => 'USD',
                'value' => function ($model) {
                    $client_usd = $model->articleRequest->client_usd;
                    return $client_usd;

                }
            ],
            [
                'attribute' => 'author_id',
                'value' => function ($model) {

                    return $model->author->username;
                }
            ],
//            [
//                'class'=>\common\grid\EnumColumn::className(),
//                'attribute'=>'status',
//                'enum'=>[
//                    Yii::t('backend', 'Not Published'),
//                    Yii::t('backend', 'Published')
//                ]
//            ],
//            'published_at:datetime',

            'created_at:datetime',

            // 'updated_at',
//
//            [
//                'class' => 'yii\grid\ActionColumn',
//                'template'=>'{update}'
//            ]
        ]
    ]); ?>

</div>
