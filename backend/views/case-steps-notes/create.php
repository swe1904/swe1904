<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\CaseStepsNotes */

$this->title = Yii::t('app', 'Create Case Steps Notes');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Case Steps Notes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="case-steps-notes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
