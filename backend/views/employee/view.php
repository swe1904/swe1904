<?php

use yii\helpers\Html;

$this->title = 'Employee View Page';
$this->params['breadcrumbs'][] = $this->title;

// Font Awesome for icons
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
?>

<div class="container mt-4">

    <!-- ✅ Desktop View -->
    <div class="d-none d-md-block">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><?= Html::encode($model->preferred_full_name) ?></h4>
                <div>
                    <?= Html::a('<i class="fas fa-pen-to-square"></i> Edit', ['update', 'id' => $model->id], [
                        'class' => 'btn btn-outline-success me-2',
                        'title' => 'Edit',
                    ]) ?>
                    <?= Html::a('<i class="fas fa-trash"></i> Delete', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-outline-danger',
                        'title' => 'Delete',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this employee?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Position:</strong> <?= $model->positionName ? $model->positionName->name : '-' ?></p>
                        <p><strong>Employee ID:</strong> <?= Html::encode($model->employee_id) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Country:</strong> <?= $model->countryOfLegalResidence ? $model->countryOfLegalResidence->country_name : 'Not Set' ?></p>
                        <p><strong>Manager:</strong> <?= $model->departmentManager ? $model->departmentManager->username : 'N/A' ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ Mobile View -->
    <div class="d-md-none">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5><?= Html::encode($model->preferred_full_name) ?></h5>
                <p><strong>Position:</strong> <?= $model->positionName ? $model->positionName->name : '-' ?></p>
                <p><strong>Country:</strong> <?= $model->countryOfLegalResidence ? $model->countryOfLegalResidence->country_name : 'Not Set' ?></p>
                <p><strong>Employee ID:</strong> <?= Html::encode($model->employee_id) ?></p>
                <p><strong>Manager:</strong> <?= $model->departmentManager ? $model->departmentManager->username : 'N/A' ?></p>

                <div class="d-flex justify-content-around mt-4">
                    <?= Html::a('<i class="fas fa-pen-to-square"></i>', ['update', 'id' => $model->id], [
                        'class' => 'btn btn-success',
                        'title' => 'Edit',
                    ]) ?>
                    <?= Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'title' => 'Delete',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this employee?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
$this->registerCss("
    .btn i {
        font-size: 1.2rem;
    }
    @media (min-width: 768px) {
        .card-header h4 {
            font-size: 1.25rem;
        }
    }
");
?>
