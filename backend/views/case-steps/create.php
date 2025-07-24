<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\CaseSteps */

$this->title = Yii::t('backend', 'Create Case Steps');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Case Steps'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="case-steps-create">

<!--    <h4>--><?php //echo Html::encode($this->title) ?><!--</h4>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
