<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

$this->title = 'Emergency Contacts';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="emergency-contact-index container mt-4">
    <!-- Title -->
    <h4 class="text-left"><b><?= Html::encode($this->title) ?></b></h4>

    <!-- Add New Button -->
    <div class="row mb-3">
        <div class="col-md-12">
            <?= Html::a('<i class="fa fa-plus-circle"></i> Add New', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <!-- Entries Dropdown & Search Bar -->
    <div class="row mb-3 d-flex align-items-center justify-content-between">
        <!-- Show Entries Dropdown -->
        <div class="col-md-4 d-flex align-items-center">
            <label for="entries" class="mb-0 mr-2">Show entries:</label>
            <form method="get" action="index" class="d-flex w-100">
                <select name="per-page" id="entries" onchange="this.form.submit()" class="form-control form-control-sm w-auto">
                    <option value="10" <?= Yii::$app->request->get('per-page') == 10 ? 'selected' : '' ?>>10</option>
                    <option value="25" <?= Yii::$app->request->get('per-page') == 25 ? 'selected' : '' ?>>25</option>
                    <option value="50" <?= Yii::$app->request->get('per-page') == 50 ? 'selected' : '' ?>>50</option>
                    <option value="100" <?= Yii::$app->request->get('per-page') == 100 ? 'selected' : '' ?>>100</option>
                </select>
            </form>
        </div>

        <!-- Search Bar (Moved to the right) -->
        <div class="col-md-4 d-flex justify-content-end align-items-right">
            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'action' => ['index'],
                'options' => ['class' => 'd-flex w-100'],
            ]); ?>

            <div class="input-group w-100">
                <?= $form->field($searchModel, 'relationship_name')->textInput([
                    'placeholder' => 'Search Relationship', 
                    'class' => 'form-control form-control-sm'
                ])->label(false); ?> 
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary btn-sm search-btn">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover text-left">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Relationship</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($emergencyContacts)): ?>
                    <tr>
                        <td colspan="3" class="text-left">No entries found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($emergencyContacts as $index => $contact): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= Html::encode($contact->relationship_name) ?></td>
                            <td class="text-left">
                                <a href="<?= Yii::$app->urlManager->createUrl(['view', 'id' => $contact->id]) ?>" class="btn btn-info btn-sm mx-1" title="View">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="<?= Yii::$app->urlManager->createUrl(['update', 'id' => $contact->id]) ?>" class="btn btn-warning btn-sm mx-1" title="Edit">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a href="<?= Yii::$app->urlManager->createUrl(['delete', 'id' => $contact->id]) ?>" class="btn btn-danger btn-sm mx-1" title="Delete"
                                   data-confirm="Are you sure?" data-method="post">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination & Info -->
    <div class="row align-items-center">
        <div class="col-md-6 text-left">
            <p>Displaying <?= $pagination->getOffset() + 1 ?> to <?= min($pagination->getOffset() + $pagination->getLimit(), $pagination->totalCount) ?> of <?= $pagination->totalCount ?> entries</p>
        </div>
        <div class="col-md-6 text-right">
            <?= LinkPager::widget([
                'pagination' => $pagination,
                'prevPageLabel' => '« Previous',
                'nextPageLabel' => 'Next »',
                'maxButtonCount' => 5,
                'options' => ['class' => 'pagination justify-content-center']
            ]) ?>
        </div>
    </div>
</div>

<!-- Add this custom CSS for alignment -->
<style>
    /* Ensure all components are vertically aligned in a row */
    .d-flex {
        display: flex;
        align-items: center;
    }

    /* Add margin to align dropdown and search bar properly */
    .row.mb-3 {
        margin-bottom: 1rem;
    }

    /* Align the search bar input and button to be in one line */
    .input-group {
        display: flex;
        align-items: center;
    }

    /* Align button and input properly */
    .input-group .form-control {
        height: calc(2.25rem + 2px); /* Align height */
    }

    /* Ensure button height matches input */
    .input-group .input-group-append .btn {
        height: calc(2.25rem + 2px); /* Ensure button height matches input */
    }

    .input-group-append .btn i {
        line-height: 0; /* Fix icon misalignment */
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* Fix for show entries label and dropdown alignment */
    .col-md-4 {
        display: flex;
        align-items: center;
    }

    /* Add spacing between dropdown and search */
    .col-md-4.d-flex.align-items-center {
        margin-right: 10px;
    }

    /* Ensure that table is scrollable on small screens */
    .table-responsive {
        -webkit-overflow-scrolling: touch; /* Add smooth scrolling on iOS devices */
        overflow-x: auto; /* Ensure horizontal scrolling works */
    }

    /* Improve table appearance */
    .table {
        font-size: 14px;
        margin-top: 20px;
        border-collapse: collapse;
    }

    .table th, .table td {
        padding: 12px 15px;
    }

    .table-striped tbody tr:nth-child(odd) {
        background-color: #f9f9f9;
    }

    .table-bordered {
        border: 1px solid #ddd;
    }

    .thead-dark th {
        background-color:rgb(109, 108, 108);
        color: #fff;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }

    /* Optional: Adjust column widths for mobile */
    @media (max-width: 767px) {
        .table th, .table td {
            font-size: 12px; /* Reduce font size for better mobile readability */
        }

        /* Optional: Adjust column widths for mobile */
        .table th, .table td {
            white-space: nowrap;
        }
    }
</style>
