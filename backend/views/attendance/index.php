<?php

use app\components\GlobalConstant;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\DatePicker;
use backend\models\Attendance;
use backend\models\Employee;

$this->title = 'Attendance Records';
$this->params['breadcrumbs'][] = $this->title;

// Get current user attendance
$today = date('Y-m-d');
$userId = Yii::$app->user->id;
$employee = Employee::findOne(['user_id' => $userId]);
$attendance = null;
$isCheckedIn = 'false';

if ($employee) {
    $attendance = Attendance::findOne([
        'employee_id' => $employee->user_id,
        'date' => $today
    ]);
    if ($attendance && $attendance->out_time === null) {
        $isCheckedIn = 'true';
    }
}
?>

<style>
/* Hide table on mobile, show on desktop */
.desktop-table {
    display: block;
}
.attendance-wrapper {
    display: none;
}

@media (max-width: 767px) {
    .desktop-table {
        display: none;
    }
    .attendance-wrapper {
        display: block;
    }
}

.attendance-wrapper {
    padding: 1rem;
    font-family: 'Segoe UI', sans-serif;
    max-width: 480px;
    margin: 0 auto;
}

h2 {
    font-size: 1.6rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.mark-attendance-btn {
    background-color: #0d1b2a;
    color: #fff;
    padding: 0.6rem 1.2rem;
    border: none;
    border-radius: 6px;
    font-size: 1rem;
    margin-bottom: 1rem;
    width: 100%;
    text-align: center;
}

.attendance-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    padding: 1rem;
    font-size: 0.95rem;
    margin-bottom: 1rem;
}

.top-row {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    margin-bottom: 0.8rem;
}

.datetime span {
    display: block;
    color: #555;
    font-size: 0.85rem;
}

.info-row {
    margin: 0.3rem 0;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    font-size: 0.9rem;
}

.status-badge {
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 0.75rem;
    font-weight: bold;
}

.status-badge.late {
    background-color: #ffedc2;
    color: #d17900;
}

.view-btn-wrapper {
    text-align: center;
    margin-top: 1rem;
}

.view-btn {
    background-color: #0d1b2a;
    color: #fff;
    border: none;
    padding: 0.4rem 1rem;
    border-radius: 8px;
    font-size: 0.9rem;
}
</style>

<div class="attendance-index">
  

  <div class="desktop-table">
        <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Mark Attendance', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
</div>

    <input type="hidden" name="returnUrl" value="<?= Yii::$app->request->url ?>">

    <!-- ✅ Desktop GridView -->
    <div class="desktop-table">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'employee_id',
                    'value' => 'employee.preferred_full_name',
                    'label' => 'Employee Name',
                    'filter' => function () {
                        $userId = Yii::$app->user->id;
                        $roles = Yii::$app->authManager->getRolesByUser($userId);
                        $roleNames = array_keys($roles);

                        if (
                            in_array(GlobalConstant::ROLE_HR_MANAGER, $roleNames) ||
                            in_array(GlobalConstant::ROLE_SUPERADMIN, $roleNames)
                        ) {
                            return \yii\helpers\ArrayHelper::map(
                                \backend\models\Employee::find()->all(),
                                'id',
                                'preferred_full_name'
                            );
                        }

                        return false;
                    },
                ],
                [
                    'attribute' => 'date',
                    'format' => ['date', 'php:d M Y'],
                    'filter' => DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'date',
                        'dateFormat' => 'yyyy-MM-dd',
                        'options' => ['class' => 'form-control'],
                    ]),
                ],
                'in_time',
                'out_time',
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $badge = match ($model->status) {
                            'Present' => 'success',
                            'Absent' => 'danger',
                            'Late' => 'warning',
                            'Half-day' => 'info',
                            default => 'secondary',
                        };
                        return "<span class='badge bg-$badge'>{$model->status}</span>";
                    },
                    'filter' => [
                        'Present' => 'Present',
                        'Absent' => 'Absent',
                        'Late' => 'Late',
                        'Half-day' => 'Half-day',
                    ]
                ],
                [
                    'label' => 'Worked Hours',
                    'value' => function ($model) {
                        if ($model->in_time && $model->out_time) {
                            $start = new \DateTime($model->in_time);
                            $end = new \DateTime($model->out_time);
                            $interval = $start->diff($end);
                            return $interval->format('%h hr %i min');
                        }
                        return 'N/A';
                    }
                ],
                [
                    'label' => 'Check-in Location',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->checkin_lat && $model->checkin_lng) {
                            $url = "https://www.google.com/maps?q={$model->checkin_lat},{$model->checkin_lng}";
                            return Html::a('📍 View Map', $url, ['target' => '_blank', 'class' => 'btn btn-sm btn-outline-info']);
                        }
                        return 'N/A';
                    },
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a('<i class="fa fa-eye"></i>', ['attendance/view', 'id' => $model->id], [
                                'title' => 'View Attendance',
                                'class' => 'btn btn-sm btn-primary',
                            ]);
                        },
                    ],
                ],
            ],
        ]); ?>
    </div>

    <!-- ✅ Mobile Card View -->
    <div class="attendance-wrapper">
        <h2>Attendance Records</h2>
        <a href="<?= Url::to(['attendance/create']) ?>" class="mark-attendance-btn">Mark Attendance</a>

        <?php foreach ($dataProvider->models as $model): ?>
            <div class="attendance-card">
                <div class="top-row">
                    <strong><?= Html::encode($model->employee->preferred_full_name ?? 'N/A') ?></strong>
                    <div class="datetime">
                        <span>Date:📅 <?= date('d M Y', strtotime($model->date)) ?></span>
                        <span>In⏰ :<?= $model->in_time ?? 'N/A' ?></span>
                    </div>
                </div>

                <div class="info-row">
                    <span>🚪 Out: <strong><?= $model->out_time ?? 'N/A' ?></strong></span>
                    <span>✅ Status:
                        <span class="status-badge <?= strtolower($model->status) ?>">
                            <?= $model->status ?? 'N/A' ?>
                        </span>
                    </span>
                </div>  

                <div class="info-row">
                    <span>⏳ Worked Hours:   <strong>
                        <?php
                        if ($model->in_time && $model->out_time) {
                            $start = new \DateTime($model->in_time);
                            $end = new \DateTime($model->out_time);
                            echo $start->diff($end)->format('%h hr %i min');
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </strong></span>
                </div>

                <div class="info-row">
                    <span>📍 Location:
                        <?php if ($model->checkin_lat && $model->checkin_lng): ?>
                            <a href="https://www.google.com/maps?q=<?= $model->checkin_lat ?>,<?= $model->checkin_lng ?>" target="_blank">View Map</a>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </span>
                </div>

                <div class="view-btn-wrapper">
                    <a class="view-btn" href="<?= Url::to(['attendance/view', 'id' => $model->id]) ?>">👁️ View</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
