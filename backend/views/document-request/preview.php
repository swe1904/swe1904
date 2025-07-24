<?php
$this->title = "Preview - " . $model->document_type;
?>

<div class="container mt-4">
    <h3><?= $model->document_type ?> (<?= $model->language_of_document ?>)</h3>
    <hr>
    <div class="border p-3 bg-white">
        <?= $rendered ?>
    </div>
    <div class="mt-4 text-center">
        <?= \yii\helpers\Html::a('Download PDF', ['generate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </div>
</div>
