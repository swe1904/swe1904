<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\BloodGroup */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Blood Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="blood-group-view container mt-5">

    <!-- Title Section -->
    <div class="text-center mb-4">
        <h1><?php echo 'View -'.Html::encode($this->title) ?></h1>
    </div>

    <!-- Detail View Section -->
    <div class="row justify-content-center">
        <div class="col-md-6">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'name',
                ],
                'options' => ['class' => 'table table-bordered table-striped'],
                'template' => '<tr><th>{label}</th><td>{value}</td></tr>',
            ]) ?>
        </div>
    </div>

    <!-- Action Buttons: Update and Delete -->
                <div class="row justify-content-center mt-4">
                    <div class="col-md-4">
                        <div class="text-center">
                        <?= Html::a('<i class="fas fa-edit"></i> EDIT', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-lg w-100 mb-3']) ?>
                <?= Html::a('<i class="fas fa-trash-alt"></i> Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger btn-lg w-100',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
                ]) ?>

            </div>
        </div>
    </div>

</div>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<!-- Custom Styling -->
<style>
    .blood-group-view .btn {
        font-size: 16px;
        padding: 12px 0;
    }

    .blood-group-view .table {
        width: 100%;
        table-layout: fixed;
    }

    .blood-group-view .table th,
    .blood-group-view .table td {
        padding: 12px;
        font-size: 14px;
    }

    .container {
        max-width: 1200px;
    }

    .row {
        display: flex;
        justify-content: center;
    }

    .text-center {
        text-align: center;
    }

    .mb-4 {
        margin-bottom: 1.5rem;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }
</style>
