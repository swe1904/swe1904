<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use backend\models\Cases;
use backend\models\CaseStatus;
use backend\models\CaseSteps;
use app\components\GlobalConstant;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Report: ' . $caseWorker->username;
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- <h2 style="margin-bottom: 20px; padding-left: 15px;"><?php echo $this->title ?></h2> -->
<?php if ($countCasesWithoutSteps == 1): ?>
  <div style="margin-bottom: 20px; font-size: 16px;"><i class="fa fa-exclamation-triangle fa-lg" style="color: #FFAC1C;"></i> There is <?= $countCasesWithoutSteps ?> case assigned to <?= $caseWorker->username ?> that has no case steps, and it won't be displayed in this report</div>
<?php endif; ?>
<?php if ($countCasesWithoutSteps > 1): ?>
  <div style="margin-bottom: 20px; font-size: 16px;"><i class="fa fa-exclamation-triangle fa-lg" style="color: #FFAC1C;"></i> There are <?= $countCasesWithoutSteps ?> cases assigned to <?= $caseWorker->username ?> that have no case steps, and they won't be displayed in this report</div>
<?php endif; ?>
<style>
  .report-grid {
    height: 40vh;
    overflow-y: scroll;
  }
</style>
<div class="row">
<div class="row report-view col-md-12 mb-20">
  <div class="col-md-6">
    <div class="panel panel-default card-view border-panel panel-refresh">
        <div class="panel-heading active">
            <div class="pull-left">
                <h6 class="panel-title txt-dark">Active Cases: On Time</h6>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="refresh-container">
            <div class="la-anim-1"></div>
        </div>

        <div class="report-grid">
          <?php \yii\widgets\Pjax::begin(['id'=>'active-cases-on-time', 'timeout' => 0,'enablePushState'=>false]); ?>
          <?= GridView::widget([
              'dataProvider' => $dataProviderActiveCasesOnTime,
              'tableOptions'=> ['class'=>'table data-table'],
              'options' => [
                  'class' => 'grid-view'
              ],
              'columns' => [
                  [
                    'class' => 'yii\grid\SerialColumn'
                  ],
                  [ 
                    'attribute' =>  'case_number',
                    'filter'=>ArrayHelper::map(Cases::find()->asArray()->all(), 'id', 'case_number'),
                    'label' => 'Case',
                    'filterInputOptions' => [
                        'class' => 'form-control search',
                        'prompt' => (new Cases)->getAttributeLabel('case_number'),
                    ],
                    'value' => 'case_number'
                  ],
                  [
                    'attribute' => 'case_status',
                    'label' => 'Status',
                    'value' => function ($model) {
                      if (!empty($model->case_status)) {
                        return CaseStatus::findOne($model->case_status)->name;
                      }
                    }
                  ],
                  [
                    'attribute' => 'target_completion_date',
                    'format' => 'raw',
                    'value' => function($model) {
                      $stepIDs = array_values(\yii\helpers\ArrayHelper::map($model->caseSteps, 'id', 'id'));
                      if (!empty($stepIDs)) {
                        return $model->caseSteps[count($stepIDs) - 1]['planned_completion_date'];
                      }
                    }
                  ],
                  [
                    'class' => 'yii\grid\ActionColumn',
                    'headerOptions' => ['class' => 'abc'],
                    'contentOptions' => ['style' => GlobalConstant::ACTION_STYLE],
                    'buttons'=>[
                        'view' => function($url, $model) {
                            $url = Yii::$app->urlManager->createUrl(['cases/view', 'id' => $model->id]);
                            return '<a data-pjax="0" target="_blank" class="mr-15" href="' . $url . '" title="View Details"><i class="fa fa-eye" style="color: orange;"></i></a>';
                        },
                        'steps'=>function($url, $model){
                            $url=Yii::$app->urlManager->createUrl(['/case-steps/index','CaseStepsSearch[case_id]'=> $model->id]);
                            return'<a data-pjax="0" target="_blank" class="mr-15" href="'.$url.'" title="Show Steps"><i class="fa fa-list text-primary"></i></a>';
                        },

                        'history'=>function($url, $model){
                            $url=Yii::$app->urlManager->createUrl(['/case-history/','CaseHistorySearch[case_id]'=>$model->id]);
                            return'<a data-pjax="0" target="_blank" class="mr-15" href="'.$url.'" title="History"><i class="fa fa-undo txt-grey"></i></a>';
                        },
                    ],
                    'template' => '{steps} {view} {history}',
                  ],
              ],
          ]); ?>
          <?php \yii\widgets\Pjax::end(); ?>
        </div>
      </div>
    </div>
    

    <div class="col-md-6">
      <div class="panel panel-default card-view border-panel panel-refresh">
          <div class="panel-heading active">
              <div class="pull-left">
                  <h6 class="panel-title txt-dark">Active Cases: Delayed</h6>
              </div>
              <div class="clearfix"></div>
          </div>
          <div class="refresh-container">
              <div class="la-anim-1"></div>
          </div>

          <div class="report-grid">
            <?php \yii\widgets\Pjax::begin(['id'=>'active-cases-delayed', 'timeout' => 0,'enablePushState'=>false]); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProviderActiveCasesDelayed,
                'tableOptions'=> ['class'=>'table data-table'],
                'options' => [
                    'class' => 'grid-view'
                ],
                'columns' => [
                    [
                      'class' => 'yii\grid\SerialColumn'
                    ],
                    [ 
                      'attribute' =>  'case_number',
                      'filter'=>ArrayHelper::map(Cases::find()->asArray()->all(), 'id', 'case_number'),
                      'label' => 'Case',
                      'filterInputOptions' => [
                          'class' => 'form-control search',
                          'prompt' => (new Cases)->getAttributeLabel('case_number'),
                      ],
                      'value' => 'case_number'
                    ],
                    [
                      'attribute' => 'case_status',
                      'label' => 'Status',
                      'value' => function ($model) {
                        if (!empty($model->case_status)) {
                          return CaseStatus::findOne($model->case_status)->name;
                        }
                      }
                    ],
                    [
                      'attribute' => 'target_completion_date',
                      'format' => 'raw',
                      'value' => function($model) {
                        $stepIDs = array_values(\yii\helpers\ArrayHelper::map($model->caseSteps, 'id', 'id'));
                        if (!empty($stepIDs)) {
                          return $model->caseSteps[count($stepIDs) - 1]['planned_completion_date'];
                        }
                      }
                    ],
                    [
                      'class' => 'yii\grid\ActionColumn',
                      'headerOptions' => ['class' => 'abc'],
                      'contentOptions' => ['style' => GlobalConstant::ACTION_STYLE],
                      'buttons'=>[
                          'view' => function($url, $model) {
                              $url = Yii::$app->urlManager->createUrl(['cases/view', 'id' => $model->id]);
                              return '<a data-pjax="0" target="_blank" class="mr-15" href="' . $url . '" title="View Details"><i class="fa fa-eye" style="color: orange;"></i></a>';
                          },
                          'steps'=>function($url, $model){
                              $url=Yii::$app->urlManager->createUrl(['/case-steps/index','CaseStepsSearch[case_id]'=> $model->id]);
                              return'<a data-pjax="0" target="_blank" class="mr-15" href="'.$url.'" title="Show Steps"><i class="fa fa-list text-primary"></i></a>';
                          },
  
                          'history'=>function($url, $model){
                              $url=Yii::$app->urlManager->createUrl(['/case-history/','CaseHistorySearch[case_id]'=>$model->id]);
                              return'<a data-pjax="0" target="_blank" class="mr-15" href="'.$url.'" title="History"><i class="fa fa-undo txt-grey"></i></a>';
                          },
                      ],
                      'template' => '{steps} {view} {history}',
                    ],
                ],
            ]); ?>
            <?php \yii\widgets\Pjax::end(); ?>
          </div>
      </div>
    </div>
  </div>
  <div class="row report-view col-md-12">
  <div class="col-md-6">
    <div class="panel panel-default card-view border-panel panel-refresh">
        <div class="panel-heading active">
            <div class="pull-left">
                <h6 class="panel-title txt-dark">Completed Cases: On Time</h6>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="refresh-container">
            <div class="la-anim-1"></div>
        </div>

        <div class="report-grid">
          <?php \yii\widgets\Pjax::begin(['id'=>'completed-cases-on-time', 'timeout' => 0,'enablePushState'=>false]); ?>
          <?= GridView::widget([
              'dataProvider' => $dataProviderCompletedCasesOnTime,
              'tableOptions'=> ['class'=>'table data-table'],
              'options' => [
                  'class' => 'grid-view'
              ],
              'columns' => [
                  [
                    'class' => 'yii\grid\SerialColumn'
                  ],
                  [ 
                    'attribute' =>  'case_number',
                    'label' => 'Case',
                    'filterInputOptions' => [
                        'class' => 'form-control search',
                        'prompt' => (new Cases)->getAttributeLabel('case_number'),
                    ],
                    'value' => 'case_number'
                  ],
                  [
                    'attribute' => 'target_completion_date',
                    'format' => 'raw',
                    'value' => function($model) {
                      $stepIDs = array_values(\yii\helpers\ArrayHelper::map($model->caseSteps, 'id', 'id'));
                      if (!empty($stepIDs)) {
                        return $model->caseSteps[count($stepIDs) - 1]['planned_completion_date'];
                      }
                    }
                  ],
                  [
                    'attribute' => 'actual_completion_date',
                    'format' => 'raw',
                    'value' => function($model) {
                      $stepIDs = array_values(\yii\helpers\ArrayHelper::map($model->caseSteps, 'id', 'id'));
                      if (!empty($stepIDs)) {
                        return $model->caseSteps[count($stepIDs) - 1]['actual_completion_date'];
                      }
                    }
                  ],
                  [
                    'class' => 'yii\grid\ActionColumn',
                    'headerOptions' => ['class' => 'abc'],
                    'contentOptions' => ['style' => GlobalConstant::ACTION_STYLE],
                    'buttons'=>[
                        'view' => function($url, $model) {
                            $url = Yii::$app->urlManager->createUrl(['cases/view', 'id' => $model->id]);
                            return '<a data-pjax="0" target="_blank" class="mr-15" href="' . $url . '" title="View Details"><i class="fa fa-eye" style="color: orange;"></i></a>';
                        },
                        'steps'=>function($url, $model){
                            $url=Yii::$app->urlManager->createUrl(['/case-steps/index','CaseStepsSearch[case_id]'=> $model->id]);
                            return'<a data-pjax="0" target="_blank" class="mr-15" href="'.$url.'" title="Show Steps"><i class="fa fa-list text-primary"></i></a>';
                        },

                        'history'=>function($url, $model){
                            $url=Yii::$app->urlManager->createUrl(['/case-history/','CaseHistorySearch[case_id]'=>$model->id]);
                            return'<a data-pjax="0" target="_blank" class="mr-15" href="'.$url.'" title="History"><i class="fa fa-undo txt-grey"></i></a>';
                        },
                    ],
                    'template' => '{steps} {view} {history}',
                  ],
              ],
          ]); ?>
          <?php \yii\widgets\Pjax::end(); ?>
        </div>
      </div>
    </div>
    

    <div class="col-md-6">
      <div class="panel panel-default card-view border-panel panel-refresh">
          <div class="panel-heading active">
              <div class="pull-left">
                  <h6 class="panel-title txt-dark">Completed Cases: Delayed</h6>
              </div>
              <div class="clearfix"></div>
          </div>
          <div class="refresh-container">
              <div class="la-anim-1"></div>
          </div>

          <div class="report-grid">
            <?php \yii\widgets\Pjax::begin(['id'=>'completed-cases-delayed', 'timeout' => 0,'enablePushState'=>false]); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProviderCompletedCasesDelayed,
                'tableOptions'=> ['class'=>'table data-table'],
                'options' => [
                    'class' => 'grid-view'
                ],
                'columns' => [
                    [
                      'class' => 'yii\grid\SerialColumn'
                    ],
                    [ 
                      'attribute' =>  'case_number',
                      'label' => 'Case',
                    ],
                    [
                      'attribute' => 'target_completion_date',
                      'format' => 'raw',
                      'value' => function($model) {
                        $stepIDs = array_values(\yii\helpers\ArrayHelper::map($model->caseSteps, 'id', 'id'));
                        if (!empty($stepIDs)) {
                          return $model->caseSteps[count($stepIDs) - 1]['planned_completion_date'];
                        }
                      }
                    ],
                    [
                      'attribute' => 'actual_completion_date',
                      'format' => 'raw',
                      'value' => function($model) {
                        $stepIDs = array_values(\yii\helpers\ArrayHelper::map($model->caseSteps, 'id', 'id'));
                        if (!empty($stepIDs)) {
                          return $model->caseSteps[count($stepIDs) - 1]['actual_completion_date'];
                        }
                      }
                    ],
                    [
                      'class' => 'yii\grid\ActionColumn',
                      'headerOptions' => ['class' => 'abc'],
                      'contentOptions' => ['style' => GlobalConstant::ACTION_STYLE],
                      'buttons'=>[
                          'view' => function($url, $model) {
                              $url = Yii::$app->urlManager->createUrl(['cases/view', 'id' => $model->id]);
                              return '<a data-pjax="0" target="_blank" class="mr-15" href="' . $url . '" title="View Details"><i class="fa fa-eye" style="color: orange;"></i></a>';
                          },
                          'steps'=>function($url, $model){
                              $url=Yii::$app->urlManager->createUrl(['/case-steps/index','CaseStepsSearch[case_id]'=> $model->id]);
                              return'<a data-pjax="0" target="_blank" class="mr-15" href="'.$url.'" title="Show Steps"><i class="fa fa-list text-primary"></i></a>';
                          },
  
                          'history'=>function($url, $model){
                              $url=Yii::$app->urlManager->createUrl(['/case-history/','CaseHistorySearch[case_id]'=>$model->id]);
                              return'<a data-pjax="0" target="_blank" class="mr-15" href="'.$url.'" title="History"><i class="fa fa-undo txt-grey"></i></a>';
                          },
                      ],
                      'template' => '{steps} {view} {history}',
                    ],
                ],
            ]); ?>
            <?php \yii\widgets\Pjax::end(); ?>
          </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>