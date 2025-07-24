<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\polling\models\PollingQuizQuestionOption */

$this->title = Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'Polling Quiz Question Option',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Polling Quiz Question Options'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="polling-quiz-question-option-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
