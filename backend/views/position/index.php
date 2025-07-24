<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var backend\models\Position[] $positions */

$this->title = 'Positions';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    /* Desktop: Show table */
    @media (max-width: 767px) {
        .table-responsive {
            display: none;
        }
    }

    /* Mobile: Show cards only */
    @media (min-width: 768px) {
        .mobile-cards {
            display: none;
        }
    }

    .card-title {
        font-size: 1.1rem;
        font-weight: 600;
    }

    .card .btn i {
        font-size: 14px;
    }
</style>

<div class="position-index">

    <h3><?= Html::encode($this->title) ?></h3>
 <!-- Add New Button -->
    <div class="row mb-3">
        <div class="col-md-12">
            <?= Html::a('<i class="fa fa-plus-circle"></i> Add New', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <!-- Desktop Table View -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Position Name</th>
                    <th class="text-center" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($positions)): ?>
                    <tr><td colspan="3" class="text-center text-muted">No positions found.</td></tr>
                <?php else: ?>
                    <?php foreach ($positions as $index => $position): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= Html::encode($position->name) ?></td>
                            <td class="text-center">
                                <a href="<?= Url::to(['view', 'id' => $position->id]) ?>" class="btn btn-outline-primary btn-sm" title="View">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="<?= Url::to(['update', 'id' => $position->id]) ?>" class="btn btn-outline-info btn-sm" title="Edit">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a href="<?= Url::to(['delete', 'id' => $position->id]) ?>" class="btn btn-outline-danger btn-sm" title="Delete"
                                   data-confirm="Are you sure you want to delete this item?" data-method="post">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="mobile-cards">
        <?php if (empty($positions)): ?>
            <div class="alert alert-warning">No positions found.</div>
        <?php else: ?>
            <?php foreach ($positions as $index => $position): ?>
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?= Html::encode($position->name) ?></h5>
                        <!-- <p class="text-muted mb-2">#<?php // $index + 1 ?></p> -->
                        <div class="d-flex justify-content-start">
                            <a href="<?= Url::to(['view', 'id' => $position->id]) ?>" class="btn btn-light btn-sm me-2" title="View">
                                <i class="fa fa-eye text-primary"></i>
                            </a>
                            <a href="<?= Url::to(['update', 'id' => $position->id]) ?>" class="btn btn-light btn-sm me-2" title="Edit">
                                <i class="fa fa-pencil text-info"></i>
                            </a>
                            <a href="<?= Url::to(['delete', 'id' => $position->id]) ?>" class="btn btn-light btn-sm" title="Delete"
                               data-confirm="Are you sure you want to delete this item?" data-method="post">
                                <i class="fa fa-trash text-danger"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>
