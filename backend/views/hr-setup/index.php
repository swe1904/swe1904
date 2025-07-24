<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $models app\models\HrSetup */

$this->title = 'HR Setup';
?>

<h1><?= Html::encode($this->title) ?></h1>

<p>
    <?= Html::a('Create HR Setup', ['create'], ['class' => 'btn btn-success']) ?>
</p>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>HR Name</th>
            <th>Address</th>
            <th>Landline</th>
            <th>Receipt Alphabetic Part</th>
            <th>Receipt Number Part</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($models as $model): ?>
        <tr>
            <td><?= $model->id ?></td>
            <td><?= $model->name ?></td>
            <td><?= $model->address ?></td>
            <td><?= $model->landline ?></td>
            <td><?= $model->receipt_alphabetic_part ?></td>
            <td><?= $model->receipt_number_part ?></td>
            <td>
                <?= Html::a('Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger btn-sm',
                    'data' => ['confirm' => 'Are you sure?', 'method' => 'post'],
                ]) ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
