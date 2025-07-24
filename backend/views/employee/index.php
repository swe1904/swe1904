<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Employees';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="employee-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Add Employee', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <!-- Desktop GridView -->
    <div class="desktop-table">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'filterRowOptions' => ['style' => 'background: #f9f9f9;'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'preferred_full_name',
                    'filterInputOptions' => ['class' => 'form-control', 'placeholder' => 'Search name']
                ],
                [
                    'attribute' => 'position',
                    'label' => 'Position',
                    'value' => function ($model) {
                        return $model->positionName->name ?? '-';
                    },
                    'filter' => \yii\helpers\ArrayHelper::map(
                        \backend\models\Position::find()->all(), 'name', 'name'
                    ),
                    'filterInputOptions' => ['class' => 'form-control', 'prompt' => 'Select Position'],
                ],
                [
                    'attribute' => 'organisation_id',
                    'label' => 'Company Name',
                    'value' => function ($model) {
                        return $model->organisation->name ?? '-';
                    },
                    'filter' => \yii\helpers\ArrayHelper::map(\common\models\Organisation::find()->all(), 'id', 'name'),
                    'filterInputOptions' => ['class' => 'form-control', 'prompt' => 'Select Company']
                ],
                [
                    'attribute' => 'country_of_legal_residence',
                    'label' => 'Country',
                    'value' => function ($model) {
                        return $model->countryOfLegalResidence->country_name ?? '-';
                    },
                    'filter' => \yii\helpers\ArrayHelper::map(\backend\models\Country::find()->all(), 'id', 'country_name'),
                    'filterInputOptions' => ['class' => 'form-control', 'prompt' => 'Select Country']
                ],
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>

    <!-- Mobile Card View -->
    <div class="mobile-cards">
        <?php foreach ($dataProvider->models as $model): ?>
            <div class="employee-card">
                <h4><?= Html::encode($model->preferred_full_name) ?></h4>
                <p><strong>Position:</strong> <?= $model->positionName->name ?? '-' ?></p>
                <p><strong>Company:</strong> <?= $model->organisation->name ?? '-' ?></p>
                <p><strong>Country:</strong> <?= $model->countryOfLegalResidence->country_name ?? '-' ?></p>
                <div class="d-flex justify-content-start gap-2 mt-2">
    <a href="<?= Url::to(['user/update', 'id' => $model->id]) ?>" class="btn btn-sm btn-outline-primary">
        <i class="fas fa-pencil-alt"></i>
    </a>
    <a href="<?= Url::to(['user/delete', 'id' => $model->id]) ?>"
       data-method="post" data-confirm="Are you sure?" class="btn btn-sm btn-outline-danger">
        <i class="fas fa-trash"></i>
    </a>
</div>

                <div class="btn-group" role="group" aria-label="Actions">
    <?= Html::a('<i class="fas fa-eye"></i>', ['view', 'id' => $model->id], [
        'class' => 'btn btn-sm btn-outline-primary',
        'title' => 'View',
        'data-toggle' => 'tooltip',
    ]) ?>
    <?= Html::a('<i class="fas fa-edit"></i>', ['update', 'id' => $model->id], [
        'class' => 'btn btn-sm btn-outline-success',
        'title' => 'Update',
        'data-toggle' => 'tooltip',
    ]) ?>
    <?= Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $model->id], [
        'class' => 'btn btn-sm btn-outline-danger',
        'title' => 'Delete',
        'data' => [
            'confirm' => 'Are you sure you want to delete this item?',
            'method' => 'post',
        ],
        'data-toggle' => 'tooltip',
    ]) ?>
</div>

            </div>
        <?php endforeach; ?>
    </div>

</div>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<!-- Responsive Styling -->
<?php
$this->registerCss("
    /* Default: show desktop table */
    .desktop-table {
        display: block;
    }
.btn-group .btn {
    margin-right: 4px;
}
@media (max-width: 576px) {
    .d-flex.gap-2 {
        flex-wrap: nowrap;
    }

    .btn-sm {
        padding: 4px 8px;
    }

    .btn i {
        font-size: 1rem;
    }
}

@media (max-width: 768px) {
    .btn-group .btn i {
        font-size: 14px;
    }
}
.btn-outline-primary i {
    color: #007bff; /* Bootstrap blue */
}
.btn-outline-danger i {
    color: #dc3545; /* Bootstrap red */
}

    .mobile-cards {
        display: none;
    }

    .employee-card {
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        background: #fff;
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    }

    .employee-card h4 {
        margin: 0 0 8px;
        font-size: 16px;
        color: #333;
    }

    .employee-card p {
        margin: 4px 0;
        font-size: 14px;
    }

    .card-actions {
        margin-top: 10px;
    }

    .card-actions .btn {
        margin-right: 5px;
        padding: 4px 10px;
        font-size: 13px;
    }

    /* Responsive overrides for small screens */
    @media (max-width: 768px) {
        .desktop-table {
            display: none;
        }

        .mobile-cards {
            display: block;
        }

        .grid-view .btn {
            font-size: 12px;
            padding: 3px 6px;
        }

        .grid-view th,
        .grid-view td {
            white-space: nowrap;
        }

        .yii-grid-view .form-control {
            width: 100%;
            font-size: 13px;
        }
    }
");
?>
