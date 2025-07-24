<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Service */

$this->title = 'Create Service';
$this->params['breadcrumbs'][] = ['label' => 'Services', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-create">

    <h6><?= Html::encode($this->title) ?></h6>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
