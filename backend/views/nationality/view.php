<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->name;
?>

<div class="nationality-view container mt-5">
    
    <div class="row">
        <!-- Left side: Card (for details) -->
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header text-center bg-light">
                    <h4 class="mb-0"><?= Html::encode($this->title) ?></h4>
                </div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'name',
                        ],
                        'options' => ['class' => 'table table-condensed table-bordered'],
                        'template' => '<tr><th>{label}</th><td>{value}</td></tr>',
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Buttons section below the card -->
    <div class="row mt-4">
        <div class="col-md-12">
            <p>
                <!-- Update and Delete buttons -->
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-lg w-100 mb-2']) ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger btn-lg w-100',
                    'data' => ['confirm' => 'Are you sure you want to delete this item?', 'method' => 'post'],
                ]) ?>
            </p>
        </div>
    </div>
</div>

<!-- Add this CSS to style the page -->
<style>
    .nationality-view .table {
        width: 100%;
        table-layout: fixed;
    }

    .nationality-view .table th,
    .nationality-view .table td {
        padding: 10px 15px;
        font-size: 14px;
    }

    .card {
        border-radius: 10px;
        border: none;
        background-color: #ffffff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        border-radius: 10px 10px 0 0;
        background-color: #f7f7f7;
        color: #333;
        padding: 20px;
    }

    .card-body {
        padding: 20px;
        background-color: #f8f9fa;
    }

    .btn-lg {
        font-size: 16px;
        padding: 12px 20px;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .container {
        max-width: 1200px;
    }

    /* Make sure the columns are responsive */
    .row {
        display: flex;
        justify-content: center;
    }

    .col-md-12 {
        padding-right: 15px;
    }

    .btn-lg {
        font-size: 16px;
        padding: 12px;
    }

    /* Extra margin between buttons */
    .mb-2 {
        margin-bottom: 10px;
    }
</style>
