<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Slip */

$this->title = 'Create Salary Slip';
$this->params['breadcrumbs'][] = ['label' => 'Slips', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="slip-create">

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1><?= Html::encode($this->title) ?></h1>
            </div>
            <div class="col-sm-6">
                <div class="alert alert-warning" style="display: none; margin-bottom: 0px">

                </div>
            </div>
        </div>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
