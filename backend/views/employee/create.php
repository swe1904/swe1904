<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Employee */

$this->title = 'Create Employee';
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .custom-title {
    font-size: 1.5em;  /* You can adjust this value to make the title smaller */
    margin-bottom: 10px;  /* Optional: Adjust the bottom margin */
}
</style>
<div class="employee-create">

    <h3 class="custom-title ribbon"><?= Html::encode($this->title) ?></h3>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
<style>
   .ribbon {
    position: relative;
    padding: 5px 10px; /* Decrease padding to reduce the height and width */
    background-color: rgb(57, 57, 58);
    color: white;
    font-size: 1em; /* Reduce font size */
    text-align: center;
    margin-top: 15px; /* Adjust margin if needed */
    margin-bottom: 5px; /* Adjust margin if needed */
    display: inline-block; /* Make the ribbon fit the content */
}

.row {
    margin-bottom: 10px;
}

</style>