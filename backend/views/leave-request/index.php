<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var backend\models\LeaveRequestSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Leave Requests';
$this->params['breadcrumbs'][] = $this->title;

?>

<style>
@media (max-width: 768px) {
    .table-view {
        display: none;
    }

    .card-view {
        display: block;
    }

    .leave-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        background: #fff;
    }

    .leave-card p {
        margin: 5px 0;
    }

    .leave-card .action-buttons {
        margin-top: 10px;
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .leave-card .action-buttons a {
        font-size: 14px;
        padding: 5px 10px;
    }
}

@media (min-width: 769px) {
    .card-view {
        display: none;
    }
}
</style>

<div class="container-fluid px-4 mt-4">
    <div class="bg-white shadow rounded p-4">

        <div class="text-center mb-4">
            <h3 class="fw-bold m-0"><?= Html::encode($this->title) ?></h3>
        </div>

        <div class="d-flex justify-content-start mb-3">
            <?= Html::a('<i class="fa fa-plus"></i> Add Leave Request', ['create'], ['class' => 'btn btn-success']) ?>
        </div>

        <!-- ✅ Desktop Table View -->
        <div class="table-responsive table-view">
            <?= \yii\grid\GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'summary' => 'Showing {begin} - {end} of {totalCount} items',
                'tableOptions' => ['class' => 'table table-bordered table-hover text-center table-sm align-middle'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn', 'header' => 'S.No'],
                    ['attribute' => 'leave_type', 'label' => 'Leave Type'],
                    [
                        'attribute' => 'status',
                        'label' => 'Status',
                        'value' => function ($model) {
                            return ucfirst($model->status);
                        },
                    ],
                    ['attribute' => 'start_date', 'label' => 'Start Date'],
                    ['attribute' => 'end_date', 'label' => 'End Date'],
                    [
                        'class' => \yii\grid\ActionColumn::class,
                        'header' => 'Actions',
                        'template' => '{view} {update} {delete}',
                        'visibleButtons' => [
                            'update' => fn($model) => in_array($model->status, ['pending', 'postpone']),
                            'delete' => fn($model) => in_array($model->status, ['pending', 'postpone']),
                        ],
                        'urlCreator' => fn($action, $model) => Url::to([$action, 'id' => $model->id]),
                    ],
                ],
            ]); ?>
        </div>

        <!-- ✅ Mobile Card View -->
        <div class="card-view">
            <div class="mb-2 text-muted">Showing <?= $dataProvider->getCount() ?> of <?= $dataProvider->getTotalCount() ?> items</div>

            <?php foreach ($dataProvider->models as $index => $model): ?>
                <div class="leave-card">
                    <p><strong>S.No:</strong> <?= $index + 1 ?></p>
                    <p><strong>Leave Type:</strong> <?= Html::encode($model->leave_type) ?></p>
                    <p><strong>Status:</strong> <?= ucfirst($model->status) ?></p>
                    <p><strong>Start Date:</strong> <?= $model->start_date ?></p>
                    <p><strong>End Date:</strong> <?= $model->end_date ?></p>

                    <div class="action-buttons">
                        <?= Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id], [
                            'class' => 'btn btn-sm btn-info',
                            'title' => 'View',
                        ]) ?>

                        <?php if (in_array($model->status, ['pending', 'postpone'])): ?>
                            <?= Html::a('<i class="fa fa-edit"></i>', ['update', 'id' => $model->id], [
                                'class' => 'btn btn-sm btn-primary',
                                'title' => 'Edit',
                            ]) ?>
                            <?= Html::a('<i class="fa fa-trash"></i>', ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-sm btn-danger',
                                'title' => 'Delete',
                                'data-confirm' => 'Are you sure you want to delete this item?',
                                'data-method' => 'post',
                            ]) ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
