<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\polling\models\PollingQuizQuestionOption */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Polling Quiz Question Option',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Polling Quiz Question Options'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="polling-quiz-question-option-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
