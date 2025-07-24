<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\EmergencyContactRelationship */

$this->title = $model->relationship_name;
$this->params['breadcrumbs'][] = ['label' => 'Emergency Contact Relationships', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="emergency-contact-relationship-view container mt-5">
    <div class="ribbon">
        <span><?= Html::encode($this->title) ?></span>
    </div>

    <div class="form-container">
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-lg w-100 mb-3']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger btn-lg w-100',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'relationship_name',
            ],
        ]) ?>
    </div>
</div>

<!-- Add Custom Styles for Ribbon and Form -->
<style>
    .container {
        max-width: 800px;
    }

    .ribbon {
        position: relative;
        background-color: rgb(35, 36, 35);
        color: white;
        font-size: 18px;
        text-align: center;
        padding: 10px 10px;
        margin-bottom: 20px;
    }

    .ribbon::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 0;
        height: 0;
        border-left: 20px solid transparent;
        border-right: 20px solid transparent;
        border-bottom: 20px solid rgb(20, 20, 20);
    }

    .form-container {
        background-color: #f9f9f9;
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-sm {
        padding: 6px 10px;
        font-size: 12px;
    }
</style>
