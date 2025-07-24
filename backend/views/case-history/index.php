<?php

use app\components\GlobalConstant;
use backend\models\Cases;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\CaseHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Case Histories');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col-md-12">
    <div class="panel panel-default card-view panel-refresh">
        <div class="panel-hading">
            <h6><?= Html::encode($this->title) ?></h6>
        </div>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

//            'id',Cases::find()->asArray()->all()
//           'case_id',
                [ 'attribute' =>  'case_id',
                    'filter'=>ArrayHelper::map(Cases::find()->asArray()->all(), 'id', 'case_number'),
                    'label' => 'Case',
                    'filterInputOptions' => [
                        'class' => 'form-control search',
                        'prompt' => (new Cases)->getAttributeLabel('case_number'),
                    ],
                    'value' => 'case.case_number'
                ],
                [
                    'attribute' =>  'created_at',
                    'label' => 'Log Time'
                ],
                [
                    'attribute' =>  'case_status',
                    'label' => 'Case Status'
                ],
                [
                    'attribute' =>  'case_step_status',
                    'label' => 'Step Status',
                    'value' => function ($model) {
                        if(isset($model->case_step_status)){
                            return GlobalConstant::CASE_STEP_STATUS_ARRAY[$model->case_step_status];
                        }
                        else
                            return $model->case_step_status;
                    },
                ],
                [
                    'attribute' =>  'msg',
                    'label' => 'Notes'
                ],

                // ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>


<div class="case-history-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php // Html::a(Yii::t('backend', 'Create Case History'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>


</div>
