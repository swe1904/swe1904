<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\CaseType */

$this->title = Yii::t('backend', 'Create Case Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Case Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="case-type-create">

<!--    <h1>--><?php //echo Html::encode($this->title) ?><!--</h1>-->
<!---->
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
