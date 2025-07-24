<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\grid\ActionColumn;

/** @var yii\web\View $this */
/** @var backend\models\LeaveRequestSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Work From Home Requests';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-fluid px-4 mt-4">
    <div class="bg-white shadow rounded p-4">
        
        <!-- Title Centered -->
        <div class="text-center mb-4">
            <h3 class="fw-bold m-0"><?= Html::encode($this->title) ?></h3>
        </div>

        <!-- Add Button and Filter Row -->
      
           

            <div class="d-flex justify-content-start mb-3">
                <?= Html::a('<i class="fa fa-plus"></i> Add Work From Home Request', ['wfh-create'], ['class' => 'btn btn-success']) ?>
            </div>
 

        <!-- Table -->
        <div class="table-responsive">
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
                        'attribute' => 'leave_type',
                        'label' => 'Leave Type',
                        'contentOptions' => ['style' => 'white-space: nowrap;'],
                    ],
                     [
                        'attribute' => 'notes',
                        'label' => 'Notes',
                        'contentOptions' => ['style' => 'white-space: nowrap;'],
                    ],
                    [
    'attribute' => 'status',
    'label' => 'Status',
    'value' => function ($model) {
        switch ($model->status) {
            case 'approve':
                return 'Approved';
            case 'reject':
                return 'Rejected';
            case 'postpone':
                return 'Postponed';
            default:
                return ucfirst($model->status);
        }
    },
    'contentOptions' => ['style' => 'white-space: nowrap;'],
],

                    [
                        'attribute' => 'start_date',
                        'label' => 'Start Date',
                        'contentOptions' => ['style' => 'white-space: nowrap;'],
                    ],
                    [
                        'attribute' => 'end_date',
                        'label' => 'End Date',
                        'contentOptions' => ['style' => 'white-space: nowrap;'],
                    ],
                 [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Actions',
                'template' => '{view} {update} {delete}',
                'visibleButtons' => [
                    'update' => function ($model) {
                        return in_array($model->status, ['pending', 'postpone']);
                    },
                    'delete' => function ($model) {
                        return in_array($model->status, ['pending', 'postpone']);
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'update') {
                        return Url::to(['wfh-update', 'id' => $model->id]);
                    }
                    return Url::to([$action, 'id' => $model->id]);
                },
                'contentOptions' => ['class' => 'text-center'],
            ],

                ],
            ]); ?>
        </div>
    </div>
</div>
