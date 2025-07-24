<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\CaseStatus $model */

$this->title = Yii::t('backend', 'Create Case Status');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Case Statuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="case-status-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
