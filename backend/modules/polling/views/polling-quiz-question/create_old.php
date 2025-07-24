<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\polling\models\PollingQuizQuestion */

$this->title = 'Create Polling Question';
$this->params['breadcrumbs'][] = ['label' => 'Polling Questions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel-body pa-0 row">
    <div class="col-md-12">
        <div class="quiz-container">

        </div>
        <div class="content" style="display: none"></div>

    </div>
</div>

<?php echo $this->render('_form_new', [
    'model' => $model,
]) ?>


