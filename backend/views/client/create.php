<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Client */

$this->title = 'Create Client';
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-create">

    <div class="col-md-12 ">
        <div class="panel panel-default card-view panel-refresh">
            <div class="panel-hading">
            </div>
            <div class="row">
                <?= $this->render('_form', [
                    'model' => $model,
                    'organisations' => $organisations
                ]) ?>
            </div>
        </div>
    </div>

</div>
