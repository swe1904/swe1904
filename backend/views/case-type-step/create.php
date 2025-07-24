<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\CaseTypeStep */

$this->title = Yii::t('backend', 'Create Case Type Step');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Case Type Steps'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
if(!empty($_GET['CaseTypeStepSearch']['case_type_id'])){
    $caseType=backend\models\CaseType::find()->where(['id'=>$_GET['CaseTypeStepSearch']['case_type_id']])->all();
}else
    $caseType=backend\models\CaseType::find()->all();
?>

<div class="case-type-step-create">

    <h6><?= Html::encode($this->title) ?></h6>

    <?= $this->render('_form', [
        'model' => $model,
        'caseType'=>$caseType
    ]) ?>


</div>
