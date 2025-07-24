<?php
use yii\helpers\Html;

// $this->title = 'Create Nationality';
?>
<div class="nationality-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>
