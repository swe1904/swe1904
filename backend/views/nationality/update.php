<?php
use yii\helpers\Html;

$this->title = 'Update Nationality';
?>
<div class="nationality-update">
    <h4><?php Html::encode($this->title) ?></h4>
    <h4 class="mb-0"><?php  Html::encode($this->title) ?></h4>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>
