<?php

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Roles';
?>

<div class="role-index container mt-4">

    <h1 class="mb-3"><?= Html::encode($this->title) ?></h1>
    <p><?= Html::a('Create Role', ['create'], ['class' => 'btn btn-success mb-3']) ?></p>

    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table table-bordered table-striped table-hover table-sm mb-0'],
            'layout' => "{items}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'role_name',
                [
                    'attribute' => 'description',
                    'contentOptions' => ['style' => 'white-space: normal; word-wrap: break-word; max-width: 300px;'],
                ],
                [
                    'attribute' => 'created_on',
                    'format' => ['date', 'php:d F Y'],
                ],
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>

<style>
/* MOBILE STYLES */
@media (max-width: 768px) {
    .grid-view table thead {
        display: none;
    }

    .grid-view table tbody tr {
        display: block;
        margin-bottom: 15px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        padding: 10px;
    }

    .grid-view table tbody td {
        display: block;
        width: 100%;
        padding: 8px 10px;
        border: none;
        border-bottom: 1px solid #e9ecef;
        word-wrap: break-word;
        white-space: normal;
    }

    .grid-view table tbody td:last-child {
        border-bottom: none;
    }

    .grid-view table tbody td::before {
        content: attr(data-label);
        font-weight: bold;
        display: block;
        margin-bottom: 4px;
        color: #555;
    }

    /* .grid-view table tbody tr td:nth-child(1)::before { content: "S.No"; } */
    .grid-view table tbody tr td:nth-child(2)::before { content: "Role Name"; }
    .grid-view table tbody tr td:nth-child(3)::before { content: "Description"; }
    .grid-view table tbody tr td:nth-child(4)::before { content: "Created On"; }
    .grid-view table tbody tr td:nth-child(5)::before { content: "Actions"; }
}
</style>
