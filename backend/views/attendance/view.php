<?php

use yii\widgets\DetailView;
use yii\helpers\Html;

$this->title = 'Attendance Detail';
$this->params['breadcrumbs'][] = ['label' => 'Attendance Records', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="attendance-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Back to List', ['index'], ['class' => 'btn btn-secondary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            [
                'label' => 'Employee Name',
                'value' => $model->employee->first_name . ' ' . $model->employee->last_name,
            ],
            [
                'attribute' => 'date',
                'format' => ['date', 'php:d M Y']
            ],
            [
                'attribute' => 'in_time',
                'format' => ['time', 'php:H:i:s']
            ],
            [
                'attribute' => 'out_time',
                'format' => ['time', 'php:H:i:s']
            ],
            'status',
            'remarks',
        ],
    ]) ?>

</div>
