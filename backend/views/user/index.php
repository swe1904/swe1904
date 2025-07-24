<?php

use app\components\GlobalConstant;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use \backend\models\Group;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN) ? Yii::t('backend', 'Organisation') : Yii::t('backend', 'Users');
$this->params['breadcrumbs'][] = $this->title;
$rolesFilter = ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name');
?>
<style>
@media (max-width: 768px) {
    .data-table thead {
        display: none;
    }

    .data-table tr {
        display: block;
        margin-bottom: 1.5rem;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 10px;
        background: #fff;
    }

    .data-table td {
        display: block;
        padding: 6px 0;
        border: none;
        border-bottom: 1px solid #eee;
        font-size: 14px;
        color: #333;
        word-break: break-word;
    }

    .data-table td:last-child {
        border-bottom: none;
    }

    .data-table td::before {
        content: attr(data-label) ": ";
        font-weight: 600;
        color: #000;
    }
}
</style>






<div class="row">
    <?= $this->render('_form', [
        'model' => $model,
        'roles' => $roles,
        'connectClients' => $connectClients,
        'allClients' => $allClients,
        'clientEntityArr' => $clientEntityArr
    ]) ?>

    <div class="col-md-12">
        <?php \yii\widgets\Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table data-table'],
            'columns' => [
                [
                    'headerOptions' => ['class' => 'abc'],
                    'class' => 'yii\grid\SerialColumn',
                ],
                [
                    'attribute' => 'organisation',
                    'label' => 'Organisation',
                    'visible' => Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_SUPERADMIN,
                    'value' => function ($model) {
                        return $model->organisation->name ?? '';
                    },
                    'contentOptions' => ['data-label' => 'Organisation']
                ],
                [
                    'attribute' => 'userProfile.firstname',
                    'label' => 'First Name',
                    'value' => function ($model) {
                        return $model->userProfile->firstname ?? '';
                    },
                    'contentOptions' => ['data-label' => 'First Name']
                ],
                [
                    'attribute' => 'fullname',
                    'label' => 'Full Name',
                    'filterInputOptions' => [
                        'class' => 'form-control border search',
                        'placeholder' => 'Full Name'
                    ],
                    'value' => function ($model) {
                        return $model->fullname ?? '';
                    },
                    'contentOptions' => ['data-label' => 'Full Name'],
                    'headerOptions' => ['class' => 'abc']
                ],
                [
                    'attribute' => 'id',
                    'label' => 'User Id',
                    'filterInputOptions' => [
                        'class' => 'form-control border search',
                        'placeholder' => 'User Id'
                    ],
                    'value' => function ($model) {
                        return $model->username ?? '';
                    },
                    'contentOptions' => ['data-label' => 'User Id'],
                    'headerOptions' => ['class' => 'abc']
                ],
                [
                    'attribute' => 'email',
                    'filterInputOptions' => [
                        'class' => 'form-control border search',
                        'placeholder' => 'Email'
                    ],
                    'contentOptions' => ['data-label' => 'Email'],
                    'headerOptions' => ['class' => 'abc']
                ],
                [
                    'attribute' => 'role',
                    'filterInputOptions' => [
                        'class' => 'form-control border search',
                        'placeholder' => 'Role'
                    ],
                    'value' => function ($data) {
                        $roles = Yii::$app->authManager->getRolesByUser($data->id);
                        return $roles ? ucfirst(implode(', ', array_keys($roles))) : 'no role';
                    },
                    'filter' => Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN) ? false : $roles,
                    'contentOptions' => ['data-label' => 'Role'],
                    'headerOptions' => ['class' => 'abc']
                ],
                [
                    'attribute' => 'created_at',
                    'filter' => false,
                    'value' => function ($model) {
                        return date('Y-m-d', $model->created_at);
                    },
                    'contentOptions' => ['data-label' => 'Created At'],
                    'headerOptions' => ['class' => 'abc']
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{impersonate} {update} {delete}',
                    'contentOptions' => ['data-label' => 'Actions', 'style' => GlobalConstant::ACTION_STYLE],
                    'headerOptions' => ['class' => 'abc'],
                    'buttons' => [
                        'impersonate' => function ($url, $model) {
                            $url = Yii::$app->urlManager->createUrl(['/user/impersonate', 'id' => $model->id]);
                            return Html::a('<i class="fa fa-user text-warning"></i>', $url, ['title' => 'Impersonate']);
                        },
                        'update' => function ($url, $model) {
                            $params = ['id' => $model->id];
                            if (isset($_GET['role']) && $_GET['role'] == GlobalConstant::ROLE_CLIENT_GROUP_MANAGER) {
                                $params['role'] = GlobalConstant::ROLE_CLIENT_GROUP_MANAGER;
                            }
                            $url = Yii::$app->urlManager->createUrl(array_merge(['/user/update'], $params));
                            return Html::a('<i class="fa fa-pencil text-success"></i>', $url, ['title' => 'Update']);
                        },
                        'delete' => function ($url, $model) {
                            $params = ['id' => $model->id];
                            if (isset($_GET['role']) && $_GET['role'] == GlobalConstant::ROLE_CLIENT_GROUP_MANAGER) {
                                $params['role'] = GlobalConstant::ROLE_CLIENT_GROUP_MANAGER;
                            }
                            $url = Yii::$app->urlManager->createUrl(array_merge(['/user/delete'], $params));
                            return Html::a('<i class="fa fa-trash text-danger"></i>', $url, [
                                'title' => 'Delete',
                                'data' => [
                                    'confirm' => 'Are you absolutely sure? You will lose all the information about this user with this action.',
                                    'method' => 'post',
                                ],
                            ]);
                        }
                    ]
                ]
            ]
        ]); ?>
        <?php \yii\widgets\Pjax::end(); ?>
    </div>
</div>

<?php
if (Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN)) {
    $this->registerJs("$('.field-userform-roles').hide();");
}
?>