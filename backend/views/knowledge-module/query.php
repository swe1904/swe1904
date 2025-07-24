<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\ActionColumn;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var backend\models\KnowledgeModule $model */

$this->title = $model->query;
$this->params['breadcrumbs'][] = ['label' => 'Knowledge Modules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="knowledge-module-view">

    <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Delete', ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => 'Are you sure you want to delete this item?',
            'method' => 'post',
        ],
    ]) ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
          'query',
          'notes'
        ],
    ]) ?>

    

</div>
