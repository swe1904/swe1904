<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DocumentTemplate */

$this->title = 'Create Document Template';
$this->params['breadcrumbs'][] = ['label' => 'Document Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-template-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>