<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\DocumentTemplate */

$this->title = $model->document_type . ' Template (v' . $model->version . ')';
$this->params['breadcrumbs'][] = ['label' => 'Document Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-template-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item? This action is irreversible.',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Back to List', ['index'], ['class' => 'btn btn-default']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'document_type',
            'language',
            'version',
            [
                'attribute' => 'is_active',
                'value' => $model->is_active ? 'Yes' : 'No',
            ],
            'created_at:datetime',
            'updated_at:datetime',
            [
                'attribute' => 'content',
                'format' => 'raw', // Render HTML content directly
                'value' => $model->content,
            ],
        ],
    ]) ?>

</div>