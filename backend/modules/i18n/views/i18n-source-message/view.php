<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\i18n\models\I18nSourceMessage */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'I18n Source Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col-md-12">
    <div class="panel panel-default card-view panel-refresh">
        <div class="panel-hading">
            <h6><?php echo Html::encode($this->title) ?></h6>
            <p style="margin: 5px 0 20px 0;">
                <?php echo Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-rounded btn-success mr-10']) ?>
                <?php echo Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-rounded btn-danger mr-10',
                    'data' => [
                        'confirm' => Yii::t('backend', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
        </div>
        <?php echo DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'category',
                'message:ntext',
            ],
        ]) ?>
    </div>
</div>
