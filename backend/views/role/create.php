<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Roles $model */

$this->title = 'Create Role';
$this->params['breadcrumbs'][] = ['label' => 'Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-header">
    <!-- <h2 class="text-muted fw-normal">Roles / Create Role</h2> -->
    <h3 class="fw-bold"><?php // Html::encode($this->title) ?></h3>
</div>

<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
