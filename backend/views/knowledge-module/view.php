<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var backend\models\KnowledgeModule $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Knowledge Modules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="knowledge-module-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Add Query', ['create', 'caseTypeID' => $_GET['caseTypeID']], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'query',
            'notes:ntext',
            [ 'class' => 'yii\grid\ActionColumn',
              'headerOptions' => ['class' => 'abc'],
              'contentOptions' => ['style' => 'width: 25%;'],
              'buttons'=>[
                  'delete' => function($url, $model){
                      $url = Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/delete', 'id' => $model->id]);
                      return '<a class="mr-25" href="'.$url.'" data-method="post" data-confirm = "'.Yii::t('yii', 'Are you sure you want to delete this item?').'",  title="Delete"><i class="fa fa-close text-danger"></i></a>';
                  },
                  'update' => function($url, $model){
                      $url = Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/update', 'id' => $model->id]);
                      return'<a class="mr-25" href="'.$url.'" data-method="post" title="Update"><i class="fa fa-pencil text-success text-inverse m-r-10"></i></a>';
                  },
                  'view'=>function($url, $model){
                      $url=Yii::$app->urlManager->createUrl(['/knowledge-module/view-query', 'id' => $model->id]);
                      return'<a class="mr-25" href="'.$url.'" title="View"><i class="fa fa-eye text-primary m-r-10"></i></a>';
                  },
              ],
              'template' => '{view} {update} {delete}',
              'header' => '<strong>ACTION</strong>'
          ],
        ],
    ]); ?>

</div>
