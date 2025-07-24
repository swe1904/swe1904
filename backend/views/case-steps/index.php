<?php

use app\components\GlobalConstant;
use backend\models\Cases;
use backend\models\CaseSteps;
use backend\models\CaseTypeStep;
use backend\models\CaseType;    //Nemanja
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use backend\models\CaseStatus;
use kartik\select2\Select2;
use backend\models\Applicant;
use himiklab\sortablegrid\SortableGridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\CaseStepsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$canSort = in_array(Yii::$app->user->identity->getRole(), [
    GlobalConstant::ROLE_ORGANISATION_ADMIN, 
    GlobalConstant::ROLE_ORGANISATION_MANAGER, 
    GlobalConstant::ROLE_CASE_MANAGER
]);
$this->title = Yii::t('backend', 'Case Steps');
$this->params['breadcrumbs'][] = $this->title;
if (isset($_GET['CaseStepsSearch']['case_id'])) {
    $caseId = $_GET['CaseStepsSearch']['case_id'];
    $case = Cases::findOne($caseId);
    $caseNumber = !empty($case) ? $case->case_number : 0;

    // created Nemanja
    $client_name = $case->client?->client_name ?? 'N/A';
$applicant_name = ($case->applicant?->first_name ?? '') . " " . ($case->applicant?->last_name ?? '');
$case_type_name = CaseType::getTypeName($case->case_type_id) ?? 'Unknown Type';

    // ended Nemanja

    $processingStep = CaseSteps::find()->where(['and', ['case_id' => $caseId], ['status' => 0]])->count(); // check for processing
} else {
    $caseNumber = '';
    $processingStep = 'All Completed';

    // created Nemanja
    $client_name = '';
    $applicant_name = '';
    $case_type_name = '';
    $caseId = '';
    // ended Nemanja
}

?>
<style>
    .panel-body {
    padding: 20px;
}
.form-group {
    margin-bottom: 15px;
}
</style>


<!-- Create Case Step -->
<?php if (in_array(Yii::$app->user->identity->getRole(), [GlobalConstant::ROLE_ORGANISATION_ADMIN, GlobalConstant::ROLE_ORGANISATION_MANAGER, GlobalConstant::ROLE_CASE_MANAGER])): ?>
<div class="row">
    
    <div class="panel panel-default card-view border-panel panel-refresh">
        <div class="panel-heading">
            <div class="pull-left">
                <h6 class="panel-title txt-dark">Create Case Step</h6>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="panel-wrapper collapse in">
            <div class="panel-body">
                <div class="col-md-12">
                    <?php $form = ActiveForm::begin([
                        'fieldConfig' => [
                            'options' => ['class' => 'form-group'],
                        ],
                    ]); ?>

                    <!-- Name Field -->
                    <div class="form-group">
                        <?= $form->field($model, 'name')->textInput([
                            'maxlength' => true,
                            'placeholder' => 'Name',
                            'class' => 'form-control'
                        ])->label('Name') ?>
                    </div>

                    <!-- Number of Days Field -->
                    <div class="form-group">
                        <?= $form->field($model, 'number_of_days')->textInput([
                            'maxlength' => true,
                            'placeholder' => 'Number of Days',
                            'class' => 'form-control'
                        ])->label('Number of Days') ?>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group">
                        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'btn btn-rounded btn-success']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>


<?php if ($caseNumber !== 0) { ?>
    <div class="col-md-12">
        <div class="panel panel-default card-view panel-refresh">
            <div class="panel-hading">
                <div class="row">
                    <div class="col-md-6">
                        <h6><?= '<u title="Case Number" style="font-style: italic;">' . $caseNumber . '</u>';
                            if (empty($processingStep)) {
                                echo ' <i class="fa fa-check-circle" style="font-size: 40px;color:green"></i>';
                            } ?>
                        </h6>

                        <!-- created by Nemanja -->
                        <br>
                        <h6>
                            <label>Applicant Name: </label>
                            <?= $applicant_name; ?>
                        </h6>
                        <h6>
                            <label>Client Name: </label>
                            <?= $client_name; ?>
                        </h6>
                        <h6>
                            <label>Case Type: </label>
                            <?= $case_type_name; ?>
                        </h6>
                        <br>
                        <!-- ended by Nemanja -->
                    </div>
                    <!-- <div class="col-md-1"></div> -->
                    <div class="col-md-4" style="display: flex; align-items: center;">
                        <div class="col-md-4" style="padding-right: 0px !important; text-align: right;">
                            <label>Case Status: </label>
                        </div>
                        <div class="col-md-7">
                            <?php
                            $statuses = ArrayHelper::map(CaseStatus::find()->all(), 'id', 'name');
                            echo Html::dropDownList(
                                'id',
                                'name',
                                $statuses,
                                [
                                    'disabled' => in_array(Yii::$app->user->identity->getRole(), [GlobalConstant::ROLE_CLIENT, GlobalConstant::ROLE_CLIENT_HR, 
                                    GlobalConstant::ROLE_CLIENT_CASE_WORKER,GlobalConstant::ROLE_CLIENT_CASE_MANAGER,
                                    GlobalConstant::ROLE_FINANCE, GlobalConstant::ROLE_CLIENT_GROUP_MANAGER,GlobalConstant::ROLE_CLIENT_ENTITY_MANAGER]),
                                    'class' => 'form-control',
                                    'prompt' => '-- Select Case Status -- ',
                                    'id' => 'case-status-selector',
                                    'options' => [
                                        $case->case_status => ['Selected' => 'selected']
                                    ]
                                ]
                            );
                            ?>
                        </div>
                        <div class="col-md-1">
                            <div class="fa fa-circle-o-notch fa-spin loading-div" style="display: none;"></div>
                        </div>
                    </div>
                   
                   

                    <?php if(!(in_array(Yii::$app->user->identity->getRole(), [GlobalConstant::ROLE_CLIENT_CASE_WORKER,GlobalConstant::ROLE_CLIENT_CASE_MANAGER]))){
                
                        ?>
                         <div class="col-md-2">
                        <button type="button" class="btn btn-orange btn-sm btn-block" onclick="composeMessageForCasestep(<?= $caseId; ?>)">MESSAGES </button>
                    </div>
                    <?php } ?>

                </div>
            </div>

         <?php if ($canSort): ?>
            <?= SortableGridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'sortableAction' =>  ['sort'],
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'headerOptions' => ['class' => 'abc']
                    ],

                    
                    [
                        'attribute' =>  'case_type_step_id',
                        'label' => 'Case Step',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => (new CaseSteps)->getAttributeLabel('case_type_step_id'),
                        ],
                        'value' => function ($model) {
                            return isset(CaseTypeStep::findOne($model->case_type_step_id)->name) ? CaseTypeStep::findOne($model->case_type_step_id)->name : '';
                        },
                    ],
                    [
                        'attribute' =>  'planned_completion_date',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => (new CaseSteps)->getAttributeLabel('planned_completion_date'),
                        ]
                    ],
                    [
                        'attribute' =>  'actual_completion_date',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => (new CaseSteps)->getAttributeLabel('actual_completion_date'),
                        ]
                    ],
                    [
                        'attribute' => 'status',
                        'format' => 'raw', // Allows rendering HTML
                        'filterInputOptions' => [
                            'class' => 'form-control search',
                            'placeholder' => (new CaseSteps)->getAttributeLabel('status'),
                        ],
                        'value' => function ($model) {
                            if (!isset($model->status)) {
                                return ''; // Return empty if status is missing
                            }
                    
                            // Define icons based on status
                            $icons = [
                                // 0 => '<i class="fa fa-hourglass-start text-warning"></i>',
                                0 => '<i class="fa fa-spinner fa-spin text-primary"></i>', // ⏳ Changed Hourglass to a Spinning Loader
                                1 => '<i class="fa fa-check-circle text-success"></i>', // ✅ Completed
                                2 => '<i class="fa fa-times-circle text-danger"></i>', // ❌ Rejected
                            ];
                    
                            // Return the corresponding icon or a default one if status is unknown
                            return $icons[$model->status] ?? '<i class="fa fa-question-circle text-muted"></i>';
                        },
                    ],
                    
                    [
                        'label' =>  'Notes',
                        'attribute' =>  'description',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => (new CaseSteps)->getAttributeLabel('description'),
                        ],
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'headerOptions' => ['class' => 'abc'],
                        'contentOptions' => ['style' => 'width:200px;'],
                        'buttons' => [
                            'delayed' => function ($url, $model) {
                                if (empty($model->actual_completion_date) && $model->planned_completion_date < date('Y-m-d')) {
                                    return '<span title="This step is delayed" class="mr-25"><i class="fa fa-exclamation-triangle" style="color: #FFAC1C;"></i></span>';
                                }
                            },
                            'delete' => function ($url, $model) {
                                $url = Yii::$app->urlManager->createUrl([Yii::$app->controller->id . '/delete', 'id' => $model->id]);
                                return '<a class="text-danger edit" href="' . $url . '" data-method="post" data-confirm = "' . Yii::t('yii', 'Are you sure you want to delete this item?') . '",  title="Delete"><i class="fa fa-trash"></i></a>';
                            },
                            'update' => function ($url, $model) {
                                $url = Yii::$app->urlManager->createUrl([Yii::$app->controller->id . '/update', 'id' => $model->id, 'CaseStepsSearch[case_id]' => $model->case_id]);
                                if (in_array(Yii::$app->user->identity->getRole(), [GlobalConstant::ROLE_SUPERADMIN, GlobalConstant::ROLE_ORGANISATION_ADMIN, GlobalConstant::ROLE_ORGANISATION_MANAGER, GlobalConstant::ROLE_CASE_WORKER, GlobalConstant::ROLE_CASE_MANAGER])) {
                                    return '<a class="mr-25" href="' . $url . '" title="Update"> <i class="fa fa-pencil text-success"></i></a>';
                                }
                            },
                        ],
                        'template' => '{delayed} {update}',
                    ],
                ],
                'options' => [
                    'id' => 'sortable-grid',
                ],
            ]); ?>
          
      <?php else: ?>
        <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'headerOptions' => ['class' => 'abc']
                    ],

                    
                    [
                        'attribute' =>  'case_type_step_id',
                        'label' => 'Case Step',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => (new CaseSteps)->getAttributeLabel('case_type_step_id'),
                        ],
                        'value' => function ($model) {
                            return isset(CaseTypeStep::findOne($model->case_type_step_id)->name) ? CaseTypeStep::findOne($model->case_type_step_id)->name : '';
                        },
                    ],
                    [
                        'attribute' =>  'planned_completion_date',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => (new CaseSteps)->getAttributeLabel('planned_completion_date'),
                        ]
                    ],
                    [
                        'attribute' =>  'actual_completion_date',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => (new CaseSteps)->getAttributeLabel('actual_completion_date'),
                        ]
                    ],
                    [
                        'attribute' =>  'status',
                        'filterInputOptions' => [
                            'class' => 'form-control search',
                            'placeholder' => (new CaseSteps)->getAttributeLabel('status'),
                        ],
                        'contentOptions' => function ($model) {
                            if (isset($model->status)) {
                                $color = GlobalConstant::CASE_STEP_STATUS_COLOR_ARRAY[$model->status];
                            } else $color = Null;
                            return ['style' => "background-color:" . $color . ';color: white;'];
                        },
                        'value' => function ($model) {
                            if (isset($model->status)) {
                                return GlobalConstant::CASE_STEP_STATUS_ARRAY[$model->status];
                            } else
                                return $model->status;
                        },
                    ],
                    [
                        'label' =>  'Notes',
                        'attribute' =>  'description',
                        'filterInputOptions' => [
                            'class' => 'form-control border search',
                            'placeholder' => (new CaseSteps)->getAttributeLabel('description'),
                        ],
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'headerOptions' => ['class' => 'abc'],
                        'contentOptions' => ['style' => 'width:200px;'],
                        'buttons' => [
                            'delayed' => function ($url, $model) {
                                if (empty($model->actual_completion_date) && $model->planned_completion_date < date('Y-m-d')) {
                                    return '<span title="This step is delayed" class="mr-25"><i class="fa fa-exclamation-triangle" style="color: #FFAC1C;"></i></span>';
                                }
                            },
                            'delete' => function ($url, $model) {
                                $url = Yii::$app->urlManager->createUrl([Yii::$app->controller->id . '/delete', 'id' => $model->id]);
                                return '<a class="text-danger edit" href="' . $url . '" data-method="post" data-confirm = "' . Yii::t('yii', 'Are you sure you want to delete this item?') . '",  title="Delete"><i class="fa fa-trash"></i></a>';
                            },
                            'update' => function ($url, $model) {
                                $url = Yii::$app->urlManager->createUrl([Yii::$app->controller->id . '/update', 'id' => $model->id, 'CaseStepsSearch[case_id]' => $model->case_id]);
                                if (in_array(Yii::$app->user->identity->getRole(), [GlobalConstant::ROLE_SUPERADMIN, GlobalConstant::ROLE_ORGANISATION_ADMIN, GlobalConstant::ROLE_ORGANISATION_MANAGER, GlobalConstant::ROLE_CASE_WORKER, GlobalConstant::ROLE_CASE_MANAGER])) {
                                    return '<a class="mr-25" href="' . $url . '" title="Update"> <i class="fa fa-pencil text-success"></i></a>';
                                }
                            },
                        ],
                        'template' => '{delayed} {update}',
                    ],
                ],
                'options' => [
                    'id' => 'sortable-grid',
                ],
            ]); ?>
          <?php endif; ?>
            <?php
            if ($case->over_all_status == 1 && $case->is_sent_for_billing == 0) {
                echo Html::a(Yii::t('backend', 'Send for Billing'), ['index', 'CaseStepsSearch[case_id]' => $caseId, 'sendForBilling' => true], ['class' => 'btn btn-rounded btn-success mr-10 mt-20']);
            }
           
            ?>
        </div>
    </div>
<?php } else { ?>
    <div class="col-md-12">
        <div class="panel panel-default card-view panel-refresh">
            <div class="panel-hading">
                <h6>No Case Exist
                </h6>
            </div>
        </div>
    </div>
<?php } ?>
<?php
$canSort = in_array(Yii::$app->user->identity->getRole(), [
    GlobalConstant::ROLE_ORGANISATION_ADMIN,
    GlobalConstant::ROLE_ORGANISATION_MANAGER,
    GlobalConstant::ROLE_CASE_MANAGER
]);
$notesUrl = Yii::$app->urlManager->createUrl(['case-steps-notes/index']);
$updateStatusURL = Yii::$app->urlManager->createUrl(['case-status/update-status-of-case']);
$sortStepsURL = Yii::$app->urlManager->createUrl(['case-steps/sort']);
$afterSortURL = Yii::$app->urlManager->createUrl(['case-steps/after-sort']);
$this->registerJs(
    <<<JS


  $(document).on('click', '.get-notes', function(){
      $.ajax({
              url: '$notesUrl?case_step_id=' + $(this).data('case-step-id'),
              type: 'GET',
              dataType: 'html',
              success: function(response){
                $('#modalContent').html(response);
                $('#modal').modal("show");
              },
              error: function(xhr, textStatus, errorThrown) {
      
              }
      });
  });
  
//   $(document).ready(function() {
//     $('#sortable-grid tbody').each(function(){
//         var row = $(this);
//         var isOnTime = false;
//         var isDely   = false;
//         row.find('td').each(function() {
//              if ($(this).text().trim() === 'On Time') {
//                 isOnTime = true;
//                 isDely = true;                
//             }
//             if (isOnTime && isDely) {
//             row.removeClass('ui-sortable-handle');
//             row.addClass('non-draggable');

//         }
//         });
        
//     });

//   });


$(document).ready(function() {

    
    // Iterate through each row in the sortable grid
    $('#sortable-grid tbody tr').each(function() {
        var row = $(this);
        var isNonDraggable = false; 
        // Check each cell in the row for "On Time" or "Delay"
        row.find('td').each(function() {
            var cellText = $(this).text().trim();
            if (cellText === 'On Time' || cellText === 'Delay') {
                isNonDraggable = true;
            }
        });

        if (isNonDraggable) {
            // Add non-draggable class to the row
            row.addClass('non-draggable');
            row.removeClass('ui-sortable-handle');
            row.find('td').each(function() {
                var td = $(this);
                // Check if this td contains the update icon
                if (!td.find('a i.fa-pencil').length) {
                    td.css({
                        'pointer-events': 'none',
                    });
                }
            });
        }
    });

    // Capture the payload of sortable grid
    (function() {
    let originalXhrOpen = XMLHttpRequest.prototype.open;
    let originalXhrSend = XMLHttpRequest.prototype.send;

    XMLHttpRequest.prototype.open = function(method, url) {
        this.url = url;
        originalXhrOpen.apply(this, arguments);
    };

    XMLHttpRequest.prototype.send = function(body) {
        if (this.url.includes('$sortStepsURL')) {
            try {
                let decodedPayload = decodeURIComponent(body);
                let caseStepIds = JSON.parse(decodedPayload.split('=')[1]); // Extract sorted IDs payload

                // Wait for the sort to complete successfully
                this.addEventListener('load', function() {
                    if (this.status === 200) {
                        $.ajax({
                            url: '$afterSortURL',  // Send the sorted IDs to after-sort action
                            type: 'POST',
                            contentType: 'application/json',
                            data: JSON.stringify(caseStepIds),
                            success: function(response) {
                               
                                if (response.status === 'success') {
                                    toastr.success(response.message);
                                    window.location.reload(); 
                                } else {
                                    toastr.error(response.message || "Something went wrong.");
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log("AJAX error:", error);
                                toastr.error("An error occurred while processing the sort.");
                            }
                        });
                    }
                });
            } catch (e) {
                console.error("Error parsing the payload: ", e);
            }
        }

        originalXhrSend.apply(this, arguments);
    };
})();




      
});


//Dont allow user to drag beyond the completed step
// $(document).ready(function() {
//     // Initialize Sortable
//     $('#sortable-grid tbody').sortable({
      
//         items: '> tr', // Enable sorting for all rows
//         start: function(event, ui) {
//             var rows = $('#sortable-grid tbody tr');
//             var draggedRowIndex = ui.item.index(); 
            
//             var blockIndex = -1;
//             rows.each(function(index) {
//                 var status = $(this).find('td').eq(4).text().trim(); 
//                 if (status === 'On Time' || status === 'Delay') {
//                     blockIndex = index;
//                     return false; 
//                 }
//             });

//             ui.item.data('blockIndex', blockIndex);
//         },
//         update: function(event, ui) {
//             var draggedRowIndex = ui.item.index(); 
//             var blockIndex = ui.item.data('blockIndex'); 
//             if (blockIndex !== -1 && draggedRowIndex < blockIndex) {
//                 $(this).sortable('cancel'); 
//             }
//         }
//     });
// });










  $('#case-status-selector').on('change', function() {
    var status = $(this).val();
    $.ajax({
        type: 'POST',
        url: '$updateStatusURL',
        data: {
            caseID: '$caseId',
            statusID: status,
        },
        beforeSend: function() {
            $('.loading-div').attr('style', 'display: inline-block')
        },
        success: function(response) {
            var jsonParseResponse = JSON.parse(response);
            if (jsonParseResponse.code === 1) {
                toastr.success(jsonParseResponse.message);
            } else {
                toastr.error(jsonParseResponse.message);
            }
            $('.loading-div').attr('style', 'display: none')
        }
    })
  })
JS
);
?>

<style>
    table tr th,
    table tr th a {
        background-color: var(--color-primary-blue) !important;
        color: var(--color-theme-primary) !important;
    }

    table tr td:last-of-type {
        width: min-content !important;
    }
</style>