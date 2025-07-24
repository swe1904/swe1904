<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm; // This is not strictly needed for just a button, but included if you have a form elsewhere on the page.

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\DocumentRequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Document Requests'; // Set the page title
$this->params['breadcrumbs'][] = $this->title; // Add to breadcrumbs if you are using them

?>

<div class="document-request-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Document Request', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // Assuming these are attributes from your model
            'employee_id',
            'document_type',
            'language_of_document',
            // 'status', // You might want to add a status or request_date here

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {preview} {pdf}', // Ensure these actions are covered by your AccessControl
                'buttons' => [
                    'preview' => function ($url, $model, $key) {
                        return Html::a('<i class="fas fa-eye"></i>', ['document-request/preview', 'id' => $model->id], [
                            'title' => 'Preview',
                            'class' => 'btn btn-info btn-sm',
                            'target' => '_blank'
                        ]);
                    },
                    'pdf' => function ($url, $model, $key) {
                        return Html::a('<i class="fas fa-file-pdf"></i>', ['document-request/generate', 'id' => $model->id], [
                            'title' => 'Download PDF',
                            'class' => 'btn btn-secondary btn-sm',
                            'target' => '_blank'
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>

</div>