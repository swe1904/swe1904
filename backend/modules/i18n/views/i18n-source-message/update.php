<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\i18n\models\I18nSourceMessage */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Source Message',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Source Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="i18n-source-message-update">



</div>

<div class="col-md-12">
    <div class="panel panel-default card-view panel-refresh">
        <div class="panel-hading">

        </div>
        <?php echo $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>