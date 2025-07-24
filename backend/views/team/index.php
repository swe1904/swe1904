<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var backend\models\search\TeamSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Teams';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
@media (max-width: 767px) {
    .table-responsive {
        display: none;
    }
    .mobile-card {
        display: block;
    }
}
@media (min-width: 768px) {
    .mobile-card {
        display: none;
    }
}
.card-custom {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.card-custom .btn {
    margin-right: 5px;
}
</style>

<div class="container-fluid px-4 mt-4">
    <div class="bg-white shadow rounded p-4">

        <!-- Title -->
        <div class="text-center mb-4">
            <h3 class="fw-bold m-0"><?= Html::encode($this->title) ?></h3>
        </div>

        <!-- Add Button -->
        <div class="d-flex justify-content-end mb-3">
            <?= Html::a('<i class="fa fa-plus"></i> Add Team', ['create'], ['class' => 'btn btn-success']) ?>
        </div>

        <!-- Desktop Table View -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped text-center table-sm align-middle">
                <thead class="table-secondary">
                    <tr>
                        <th>S.No</th>
                        <th>Team Name</th>
                        <th>Team Manager</th>
                        <th>Parent Team</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($teams as $index => $team): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= Html::encode($team->name) ?></td>
                            <td><?= Html::encode($team->teamManager ? $team->teamManager->username : '-') ?></td>
                            <td><?= Html::encode($team->parentTeam ? $team->parentTeam->name : '-') ?></td>
                            <td><?= Yii::$app->formatter->asDate($team->created_at, 'php:d-m-Y') ?></td>
                            <td>
                                <a href="<?= Url::to(['view', 'id' => $team->id]) ?>" class="btn btn-sm" style="background-color:#f7dc6f; color:#000;" title="View">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="<?= Url::to(['update', 'id' => $team->id]) ?>" class="btn btn-outline-secondary btn-sm" title="Edit">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a href="<?= Url::to(['delete', 'id' => $team->id]) ?>" class="btn btn-sm" style="background-color:#f1948a; color:#000;" title="Delete" data-confirm="Are you sure?" data-method="post">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="mobile-card">
            <?php foreach ($teams as $index => $team): ?>
                <div class="card-custom">
                    <p><strong>S.No:</strong> <?= $index + 1 ?></p>
                    <p><strong>Team Name:</strong> <?= Html::encode($team->name) ?></p>
                    <p><strong>Team Manager:</strong> <?= Html::encode($team->teamManager ? $team->teamManager->username : '-') ?></p>
                    <p><strong>Parent Team:</strong> <?= Html::encode($team->parentTeam ? $team->parentTeam->name : '-') ?></p>
                    <p><strong>Created At:</strong> <?= Yii::$app->formatter->asDate($team->created_at, 'php:d-m-Y') ?></p>
                    <div class="d-flex justify-content-start mt-2">
                        <a href="<?= Url::to(['view', 'id' => $team->id]) ?>" class="btn btn-sm" style="background-color:#f7dc6f; color:#000;" title="View">
                            <i class="fa fa-eye"></i>
                        </a>
                        <a href="<?= Url::to(['update', 'id' => $team->id]) ?>" class="btn btn-outline-secondary btn-sm" title="Edit">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a href="<?= Url::to(['delete', 'id' => $team->id]) ?>" class="btn btn-sm" style="background-color:#f1948a; color:#000;" title="Delete" data-confirm="Are you sure?" data-method="post">
                            <i class="fa fa-trash"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>
