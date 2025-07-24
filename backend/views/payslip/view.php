<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Payslip $model */

$this->title = 'Payslip Details: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Payslips', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="payslip-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'employee_id',
            'pay_period',
            'basic_salary',
            'allowances',
            'deductions',
            'net_salary',
        ],
    ]) ?>
</div>
