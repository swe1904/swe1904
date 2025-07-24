<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'All Users';
$this->params['breadcrumbs'][] = $this->title;

$models = $dataProvider->getModels();
?>

<style>
/* Desktop - show table */
.user-table {
    display: block;
}
.user-cards {
    display: none;
}

/* Mobile - show cards instead of table */
@media (max-width: 768px) {
    .user-table {
        display: none;
    }
    .user-cards {
        display: block;
    }

    .user-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .user-card h5 {
        margin-bottom: 0.25rem;
        font-size: 16px;
    }

    .user-card .meta {
        font-size: 14px;
        color: #666;
    }

    .user-card-actions {
        margin-top: 0.5rem;
    }

    .user-card-actions a {
        margin-right: 10px;
        font-size: 16px;
    }
}
</style>

<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="user-table">
        <?php Pjax::begin(); ?>
        <?= \yii\grid\GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table table-bordered table-hover table-striped'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'fullname',
                'username',
                'email',
                [
                    'attribute' => 'role',
                    'label' => 'Role',
                    'value' => function ($model) {
                        $roles = Yii::$app->authManager->getRolesByUser($model->id);
                        return $roles ? implode(', ', array_keys($roles)) : 'no role';
                    },
                    'filter' => false,
                ],
                [
                    'attribute' => 'created_at',
                    'value' => function ($model) {
                        return date('Y-m-d', $model->created_at);
                    },
                    'filter' => false,
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{impersonate} {update} {delete}',
                    'buttons' => [
                        'impersonate' => function ($url, $model) {
                            $url = Url::to(['/user/impersonate', 'id' => $model->id]);
                            return Html::a('<i class="fa fa-user text-warning"></i>', $url, ['title' => 'Impersonate']);
                        },
                        'update' => function ($url, $model) {
                            $url = Url::to(['/user/update', 'id' => $model->id]);
                            return Html::a('<i class="fa fa-pencil text-success"></i>', $url, ['title' => 'Update']);
                        },
                        'delete' => function ($url, $model) {
                            $url = Url::to(['/user/delete', 'id' => $model->id]);
                            return Html::a('<i class="fa fa-trash text-danger"></i>', $url, [
                                'title' => 'Delete',
                                'data' => [
                                    'confirm' => 'Are you sure?',
                                    'method' => 'post',
                                ],
                            ]);
                        },
                    ],
                ],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>

    <!-- ✅ Mobile card view -->
    <div class="user-cards">
        <?php foreach ($models as $model): ?>
            <div class="user-card">
                <h5><?= Html::encode($model->fullname) ?></h5>
                <div class="meta">User ID: <?= Html::encode($model->username) ?></div>
                <div class="meta">Email: <?= Html::encode($model->email) ?></div>
                <div class="meta">
                    Role:
                    <?= implode(', ', array_keys(Yii::$app->authManager->getRolesByUser($model->id))) ?: 'no role' ?>
                </div>
                <div class="meta">Created: <?= date('Y-m-d', $model->created_at) ?></div>

                <div class="user-card-actions">
                    <?= Html::a('<i class="fa fa-user text-warning"></i>', ['/user/impersonate', 'id' => $model->id], ['title' => 'Impersonate']) ?>
                    <?= Html::a('<i class="fa fa-pencil text-success"></i>', ['/user/update', 'id' => $model->id], ['title' => 'Update']) ?>
                    <?= Html::a('<i class="fa fa-trash text-danger"></i>', ['/user/delete', 'id' => $model->id], [
                        'title' => 'Delete',
                        'data' => ['confirm' => 'Delete this user?', 'method' => 'post'],
                    ]) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
