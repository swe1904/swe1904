<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ArticleTmp */

$this->title = 'Create Article Tmp';
$this->params['breadcrumbs'][] = ['label' => 'Article Tmps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-tmp-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
