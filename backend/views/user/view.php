<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->getPublicIdentity();
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col-md-12">
    <div class="panel panel-default card-view panel-refresh">
        <div class="panel-hading">

        </div>
        <div class="user-view">

            <p class="mb-15">
                <?php echo Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-rounded btn-primary mr-10']) ?>
                <?php echo Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-rounded btn-danger mr-10',
                    'data' => [
                        'confirm' => Yii::t('backend', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </p>


            <?php echo DetailView::widget([
                'model' => $model,
                'attributes' => [
                     [
                        'attribute' => 'fullname',
                        'label' => 'Full Name',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => 'Full Name'
                        ],
                        'value' => function ($model) {
                            return $model->fullname ?? '';
                        },
                        'contentOptions' => ['style' => 'width: auto;'],
                        'headerOptions' => ['class' => 'abc'],
                    ],
                    
                    'username',
                    'email',
                   
                ],
            ]) ?>

        </div>
    </div>
</div>

