<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\KnowledgeModule $model */

$this->title = ''.$caseTypeModel->name.'';
$this->params['breadcrumbs'][] = ['label' => 'Knowledge Modules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="knowledge-module-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'caseTypeModel' => $caseTypeModel,
    ]) ?>

</div>
