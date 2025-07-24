<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\polling\models\PollingQuizQuestion */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Questionnaire Questions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">

                <div class="col-md-12">
                    <div class="panel panel-default card-view panel-refresh">
                        <div class="panel-hading">
                            <p class="mb-15">
                                <?php echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-rounded btn-primary mr-10']) ?>
                                <?php echo Html::a('Delete', ['delete', 'id' => $model->id], [
                                    'class' => 'btn btn-rounded btn-danger mr-10',
                                    'data' => [
                                        'confirm' => 'Are you sure you want to delete this item?',
                                        'method' => 'post',
                                    ],
                                ]) ?>
                                <?php echo Html::a('Return To Questionnaire', ['polling-quiz/index'], ['class' => 'btn btn-rounded btn-primary mr-10']) ?>
                            </p>
                        </div>
                        <?php echo DetailView::widget([
                            'model' => $model,
                            'attributes' => [
            //            'id',
                                [
                                    'attribute' => 'polling_quiz_id',
                                    'label' => 'Questionnaire',
                                    'value' => $model->pollingQuiz->name
                                ],
                                'title',
                                'question:ntext',
                                'type',
                                /*            'order',
                                            'action',
                                            'action_compare',
                                            'action_compare_radio',
                                            'action_compare_text',
                                            'action_value',
                                            'visible',
                                            'visible_quiz_question_id',
                                            'visible_compare',
                                            'visible_value',*/
                            ],
                        ]) ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>