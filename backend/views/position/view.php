<?php
use yii\helpers\Html;

$this->title = 'View Position: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Positions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="position-view container mt-4">
    <!-- Ribbon Section -->
    <div class="ribbon-wrapper">
        <div class="ribbon"><b><?= Html::encode($this->title) ?></b></div>
    </div>

    <!-- Position Details Section -->
    <div class="position-details">
        <p><strong>Position Name:</strong> <?= Html::encode($model->name) ?></p>
    </div>

    <!-- Buttons Section -->
    <div class="form-group button-group-responsive">
        <?= Html::a('<i class="fa fa-pencil"></i> Update', ['update', 'id' => $model->id], [
            'class' => 'btn btn-sm',
            'style' => 'background-color:#aed6f1; color:#000; margin-right:10px;',
        ]) ?>
        <?= Html::a('<i class="fa fa-trash"></i> Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-sm',
            'style' => 'background-color:#f5b7b1; color:#000; margin-right:10px;',
            'data-confirm' => 'Are you sure you want to delete this item?',
            'data-method' => 'post',
        ]) ?>
        <?= Html::a('<i class="fa fa-arrow-left"></i> Back to List', ['index'], [
            'class' => 'btn btn-sm',
            'style' => 'background-color:#d2b4de; color:#000;',
        ]) ?>
    </div>
</div>

<!-- Custom CSS for Ribbon and Responsive Design -->
<style>
    .ribbon-wrapper {
        width: 100%;
        display: flex;
        justify-content: flex-start;
        margin-bottom: 20px;
    }

    .ribbon {
        background-color: #343435;
        color: white;
        padding: 10px 20px;
        font-size: 18px;
        font-weight: bold;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .position-details p {
        font-size: 16px;
        margin: 10px 0;
    }

    .btn-sm {
        font-size: 14px;
        padding: 6px 12px;
    }

    .button-group-responsive a {
        margin-bottom: 10px;
        display: inline-block;
    }

    @media (max-width: 767px) {
        .button-group-responsive a {
            display: block !important;
            width: 100%;
            margin-bottom: 10px;
        }
    }

    .container {
        padding-left: 15px;
        padding-right: 15px;
    }
</style>
