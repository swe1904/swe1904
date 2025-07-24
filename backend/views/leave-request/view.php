<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\LeaveRequest */

$this->title = 'Leave Request Details: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Leave Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Format status
$statusText = match ($model->status) {
    'approve' => 'Approved',
    'reject' => 'Rejected',
    'postpone' => 'Postponed',
    default => ucfirst($model->status)
};
?>

<div class="leave-request-view">

    <h3 class="page-title"><?= Html::encode($this->title) ?></h3>

    <!-- Ribbon -->
    <div class="ribbon">
        <span>Leave Request Information</span>
    </div>

    <!-- Detail Box -->
    <div class="details-container">
        <div class="detail-item">
            <label>Leave Type:</label>
            <div><?= Html::encode($model->leave_type) ?></div>
        </div>
        <div class="detail-item">
            <label>Status:</label>
            <div><?= Html::encode($statusText) ?></div>
        </div>
        <div class="detail-item">
            <label>Start Date:</label>
            <div><?= Html::encode($model->start_date) ?></div>
        </div>
        <div class="detail-item">
            <label>End Date:</label>
            <div><?= Html::encode($model->end_date) ?></div>
        </div>
    </div>

    <!-- Action Buttons -->
    <?php if (!in_array($model->status, ['approve', 'reject'])): ?>
    <div class="button-group">
        <?= Html::a('Update Request', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete Request', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => ['confirm' => 'Are you sure you want to delete this leave request?', 'method' => 'post'],
        ]) ?>
    </div>
    <?php endif; ?>

</div>

<?php
$this->registerCss("
.leave-request-view {
    padding: 20px;
    font-family: 'Segoe UI', sans-serif;
}

.page-title {
    font-size: 20px;
    margin-bottom: 15px;
    color: #333;
}

.ribbon {
    background-color: #444;
    color: #fff;
    padding: 6px 10px;
    border-radius: 4px;
    margin-bottom: 20px;
    font-weight: bold;
    font-size: 15px;
    text-align: center;
}

.details-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 20px;
    background: #f9f9f9;
    border-radius: 6px;
    padding: 15px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.06);
}

.detail-item {
    flex: 1 1 45%;
    min-width: 220px;
}

.detail-item label {
    font-weight: 600;
    color: #555;
    display: block;
    margin-bottom: 4px;
}

.detail-item div {
    font-size: 15px;
    color: #222;
}

.button-group {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.button-group .btn {
    padding: 8px 16px;
    font-size: 14px;
}

/* Responsive for mobile */
@media (max-width: 576px) {
    .detail-item {
        flex: 1 1 100%;
    }
    .ribbon {
        font-size: 14px;
        padding: 5px 8px;
    }
    .page-title {
        font-size: 18px;
    }
}
");
?>
