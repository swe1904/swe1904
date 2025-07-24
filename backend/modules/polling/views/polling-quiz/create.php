<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\polling\models\PollingQuiz */

$this->title = 'Create Questionnaire';
$this->params['breadcrumbs'][] = ['label' => 'Pollings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="polling-quiz-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
