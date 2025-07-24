<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Department $model */

$this->title = 'Create Department';
$this->params['breadcrumbs'][] = ['label' => 'Departments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="department-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
