<?php
use yii\helpers\Html;

/* @var $model app\models\Attendance */

$this->title = $model->isNewRecord ? 'Create Attendance' : 'Update Attendance';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_form', ['model' => $model]) ?>
