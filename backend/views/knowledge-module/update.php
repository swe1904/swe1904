<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\KnowledgeModule $model */

// $this->title = 'Update Knowledge Module: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Knowledge Modules', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="knowledge-module-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'caseTypeModel' => $caseTypeModel,
    ]) ?>

</div>
