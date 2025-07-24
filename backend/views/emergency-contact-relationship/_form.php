<?php
use yii\helpers\Html;
use yii\bootstrap4\ButtonGroup;
use yii\bootstrap4\ButtonDropdown;
use yii\bootstrap4\Dropdown;
use yii\helpers\Url;
?>

<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title"><?= Html::encode($model->relationship_name) ?></h5>
        <p class="card-text"><?= Html::encode($model->id) ?></p>

        <!-- Action Buttons with Icons -->
        <div class="text-center">
            <?= Html::a('<i class="fas fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
            <?= Html::a('<i class="fas fa-trash-alt"></i> Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger btn-sm',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>
</div>
