<?php 
use yii\helpers\Html;

$this->title = 'View Team: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Teams', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="team-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><strong>Team Name:</strong> <?= Html::encode($model->name) ?></p>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this team?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
</div>
