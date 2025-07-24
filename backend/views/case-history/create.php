<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\CaseHistory */

$this->title = Yii::t('backend', 'Create Case History');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Case Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="case-history-create">

    <h6><?= Html::encode($this->title) ?></h6>

    <?= $this->render('_form', [
        'model' => $model,
        'data' => $data,
        //'case'=>$case->select('case_type_step_id')->where(['case_id'=>15])->orderBy(['id'=>'desc'])->limit(1)->all()[0] ["case_type_step_id"]
    ]) ?>

</div>
