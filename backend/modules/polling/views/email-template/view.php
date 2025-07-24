<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\EmailTemplate */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Email Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default card-view panel-refresh">
            <div class="panel-hading">
                <p class="mb-15">
                    <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-rounded btn-success mr-10']) ?>
                    <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-rounded btn-danger mr-10',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                </p>
            </div>
            <div class="row">
                <div class="container">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                //            'user_id',
                //            'event_id',
                            'name',
                            //'from_name',
                            //'from_email:email',
                            'to_email:email',
                            'to_name',
                            'subject',
                            'body:html',
                            // 'sent_after_day',
                            'attachment',

                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>