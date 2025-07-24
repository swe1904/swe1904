<?php
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;

$this->title = 'Leave Request History';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1 class="mb-4"><?= Html::encode($this->title) ?></h1>

<style>
@media (max-width: 768px) {
    /* Hide traditional table on mobile */
    .table-responsive-desktop {
        display: none;
    }

    .mobile-card {
        display: block;
        margin-bottom: 1rem;
        border: 1px solid #ddd;
        border-radius: 0.5rem;
        padding: 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .mobile-card h5 {
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
        font-weight: bold;
    }

    .mobile-card p {
        margin: 0.25rem 0;
        font-size: 0.95rem;
    }
}

@media (min-width: 769px) {
    /* Hide mobile cards on desktop */
    .mobile-responsive-view {
        display: none;
    }
}
</style>

<?php Pjax::begin(); ?>

<!-- Desktop Table View -->
<div class="table-responsive-desktop">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-bordered table-striped table-hover'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'employee_id',
                'label' => 'Employee Name',
                'value' => fn($model) => $model->employee?->preferred_full_name ?? 'N/A',
            ],
            'leave_type',
            [
                'attribute' => 'start_date',
                'format' => ['date', 'php:d M Y'],
                'label' => 'Start Date',
            ],
            [
                'attribute' => 'end_date',
                'format' => ['date', 'php:d M Y'],
                'label' => 'End Date',
            ],
            [
                'label' => 'No. of Days',
                'value' => fn($model) => (new \DateTime($model->start_date))->diff(new \DateTime($model->end_date))->days + 1,
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'status',
                'label' => 'Status',
                'value' => fn($model) => match($model->status) {
                    'approve' => 'Approved',
                    'reject' => 'Rejected',
                    'postpone' => 'Postponed',
                    default => ucfirst($model->status),
                },
                'contentOptions' => ['style' => 'text-transform: capitalize;'],
            ],
            'pay_type',
            [
                'attribute' => 'leave_coverage',
                'label' => 'Leave Coverage',
                'value' => fn($model) => $model->coverage?->preferred_full_name ?? 'N/A',
            ],
            [
                'attribute' => 'approved_by',
                'label' => 'Approved By',
                'value' => fn($model) => $model->approver?->preferred_full_name ?? 'N/A',
            ],
            [
                'attribute' => 'approved_on',
                'format' => ['date', 'php:d M Y'],
                'label' => 'Approved On',
            ],
            [
                'attribute' => 'remarks',
                'format' => 'ntext',
            ],
        ],
    ]); ?>
</div>

<!-- Mobile Card View -->
<div class="mobile-responsive-view">
    <?php foreach ($dataProvider->getModels() as $model): ?>
        <div class="mobile-card">
            <h5><?= $model->employee?->preferred_full_name ?? 'N/A' ?></h5>
            <p><strong>Leave Type:</strong> <?= $model->leave_type ?></p>
            <p><strong>Start:</strong> <?= Yii::$app->formatter->asDate($model->start_date, 'php:d M Y') ?></p>
            <p><strong>End:</strong> <?= Yii::$app->formatter->asDate($model->end_date, 'php:d M Y') ?></p>
            <p><strong>No. of Days:</strong>
                <?= (new \DateTime($model->start_date))->diff(new \DateTime($model->end_date))->days + 1 ?>
            </p>
            <p><strong>Status:</strong>
                <?= match($model->status) {
                    'approve' => 'Approved',
                    'reject' => 'Rejected',
                    'postpone' => 'Postponed',
                    default => ucfirst($model->status),
                } ?>
            </p>
            <p><strong>Pay Type:</strong> <?= $model->pay_type ?></p>
            <p><strong>Leave Coverage:</strong> <?= $model->coverage?->preferred_full_name ?? 'N/A' ?></p>
            <p><strong>Approved By:</strong> <?= $model->approver?->preferred_full_name ?? 'N/A' ?></p>
            <p><strong>Approved On:</strong> <?= Yii::$app->formatter->asDate($model->approved_on, 'php:d M Y') ?></p>
            <?php if ($model->remarks): ?>
                <p><strong>Remarks:</strong> <?= nl2br(Html::encode($model->remarks)) ?></p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<?php Pjax::end(); ?>
