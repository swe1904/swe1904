<?php
use yii\widgets\DetailView;
use yii\helpers\Html;

/** @var $this yii\web\View */
/** @var $model backend\models\Role */

$this->title = $model->role_name;
$this->params['breadcrumbs'][] = ['label' => 'Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container mt-4 mb-5">

    <!-- Page Title & Action Buttons -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
        <h2 class="mb-0"><?= Html::encode($this->title) ?></h2>
        <div>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary me-2']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-sm btn-danger',
                'data' => ['confirm' => 'Are you sure?', 'method' => 'post'],
            ]) ?>
        </div>
    </div>

    <!-- Detail Card -->
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="responsive-detail-view">
                <div class="detail-row">
                    <div class="detail-label">ID</div>
                    <div class="detail-value"><?= Html::encode($model->id) ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Role Name</div>
                    <div class="detail-value"><?= Html::encode($model->role_name) ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Description</div>
                    <div class="detail-value"><?= Html::encode($model->description) ?></div>
                </div>
               
            </div>

        </div>
    </div>
</div>

<!-- Styling -->
<style>
.responsive-detail-view {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.detail-row {
    display: flex;
    flex-wrap: wrap;
    border-bottom: 1px solid #f1f1f1;
    padding-bottom: 10px;
    margin-bottom: 10px;
}

.detail-label {
    flex: 1 1 30%;
    font-weight: 600;
    color: #333;
}

.detail-value {
    flex: 1 1 70%;
    color: #444;
}

/* Mobile Responsive */
@media (max-width: 767px) {
    .detail-row {
        flex-direction: column;
    }

    .detail-label {
        margin-bottom: 5px;
        color: #666;
    }

    .detail-value {
        font-weight: 500;
    }
}
</style>
