<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\ActionColumn;

/** @var yii\web\View $this */
/** @var backend\models\search\DepartmentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Departments';
$this->params['breadcrumbs'][] = $this->title;

$css = <<<CSS
@media (max-width: 768px) {
    .desktop-table {
        display: none;
    }
    .mobile-card {
        display: block;
    }
}
@media (min-width: 769px) {
    .mobile-card {
        display: none;
    }
}
.department-card {
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}
.department-card h5 {
    font-weight: bold;
}
.department-actions a {
    margin-right: 10px;
    font-size: 16px;
}
CSS;

$this->registerCss($css);
?>

<div class="container-fluid px-4 mt-4">
    <div class="bg-white shadow rounded p-4">
        
        <!-- Title -->
        <div class="text-center mb-4">
            <h3 class="fw-bold m-0"><?= Html::encode($this->title) ?></h3>
        </div>

        <!-- Add Department Button -->
        <div class="d-flex justify-content-end mb-3">
            <?= Html::a('<i class="fa fa-plus"></i> Add Department', ['create'], ['class' => 'btn btn-success']) ?>
        </div>

        <!-- DESKTOP TABLE -->
        <div class="desktop-table table-responsive">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'summary' => 'Showing {begin} - {end} of {totalCount} items',
                'tableOptions' => ['class' => 'table table-bordered table-hover table-striped text-center table-sm align-middle'],
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'header' => 'S.No',
                        'contentOptions' => ['class' => 'fw-bold'],
                    ],
                    [
                        'attribute' => 'name',
                        'label' => 'Department Name',
                        'contentOptions' => ['style' => 'white-space: nowrap;'],
                    ],
                    [
                        'attribute' => 'department_manager',
                        'label' => 'Department Manager',
                        'value' => function ($model) {
                            return $model->departmentManager ? $model->departmentManager->username : 'Not Assigned';
                        },
                        'contentOptions' => ['style' => 'white-space: nowrap;'],
                    ],
                    [
                        'attribute' => 'parent_department_id',
                        'label' => 'Parent Department',
                        'value' => function ($model) {
                            return $model->parent ? $model->parent->name : 'N/A';
                        },
                        'contentOptions' => ['style' => 'white-space: nowrap;'],
                    ],
                    [
                        'class' => ActionColumn::class,
                        'header' => 'Actions',
                        'urlCreator' => function ($action, $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        },
                        'contentOptions' => ['class' => 'text-center'],
                    ],
                ],
            ]); ?>
        </div>

        <!-- MOBILE CARDS -->
        <div class="mobile-card">
            <?php foreach ($dataProvider->getModels() as $model): ?>
                <div class="department-card">
                    <h5><?= Html::encode($model->name) ?></h5>
                    <p><strong>Manager:</strong> <?= $model->departmentManager->username ?? 'Not Assigned' ?></p>
                    <p><strong>Parent:</strong> <?= $model->parent->name ?? 'N/A' ?></p>
                    <div class="department-actions">
                        <?= Html::a('<i class="fa fa-eye text-primary"></i>', ['view', 'id' => $model->id], [
                            'class' => '',
                            'title' => 'View',
                        ]) ?>
                        <?= Html::a('<i class="fa fa-pencil-alt text-success"></i>', ['update', 'id' => $model->id], [
                            'class' => '',
                            'title' => 'Update',
                        ]) ?>
                        <?= Html::a('<i class="fa fa-trash-alt text-danger"></i>', ['delete', 'id' => $model->id], [
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                            'title' => 'Delete',
                        ]) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>
