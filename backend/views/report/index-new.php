<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use backend\models\Cases;
use app\components\GlobalConstant;
use yii\widgets\Pjax;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Report';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if(Yii::$app->user->can(GlobalConstant::ROLE_ORGANISATION_ADMIN) || Yii::$app->user->can(GlobalConstant::ROLE_ORGANISATION_MANAGER)): ?>
  <style>
    .custom-nav-tabs>li>a {
      border: 1px solid #727272 !important;
    }
    .custom-nav-tabs>li>a:hover {
      border: 1px solid black;
    }
    .custom-nav-tabs>li:first-of-type.active>a {
      border-top-left-radius: 12px;
      border-bottom-left-radius: 12px;
      border-bottom-color: #fff;
      border-top-color: #fff;
    }
    .custom-nav-tabs>li>a{
      border:none !important;
    }
  </style>



<div class="row">
  <div class="container-fluid" style="margin-bottom: 20px;">
    <ul class="nav nav-tabs custom-nav-tabs">
      <li class="<?= !isset($_GET['clientCW']) ? 'active' : '' ?>">
        <?php echo Html::a('Pangea Case Worker', ['index'], ['class' => '']); ?>
      </li>
      <li class="<?= isset($_GET['clientCW']) ? 'active' : '' ?>">
        <?php echo Html::a('Client Case Worker', ['index','clientCW' => '1'], ['class' => '']); ?>
      </li>
    </ul>
  </div>
<?php endif; ?>
    <div class="panel panel-default card-view border-panel panel-refresh" style="display: flex; flex-direction: column;">
        <div class="panel-heading" style="padding-left: 28px;">
            <span class="reciept-filter"> Filters </span>
            <a class="<?php echo (count($_GET) == 0 || (count($_GET) == 1 && isset($_GET['clientCW']))) ? 'disabled-clear-filter' : '' ?> clear-all-filters" style="margin-left: 10px" title="Reset All Filters" href="<?php echo isset($_GET['clientCW'])? Yii::$app->urlManager->createAbsoluteUrl(['report/index','clientCW'=>1]) :Yii::$app->urlManager->createAbsoluteUrl(['report/index']) ?>"><i class="fa fa-undo fa-lg" style="color: #d20511;"></i></a>
        </div>
        <div class="col-md-12 case-filter" style="margin-bottom: 20px;  display: flex; align-items: flex-end;">
            <div class="col-md-4 filter-fields" style="padding-left: 0;">
                <label class="control-label" for="from-date">From Date</label>
                <?php
                $fromDate = '';
                if (isset($_GET['CasesSearch']['from_date'])) {
                    $fromDate = $_GET['CasesSearch']['from_date'];
                }
                echo DateControl::widget([
                    'name' => 'from-date',
                    'id' => 'from-date',
                    'value' => $fromDate,
                    'type'=>DateControl::FORMAT_DATE,
                    'displayFormat' => 'yyyy-MM-dd',
                    'saveFormat' => 'yyyy-MM-dd',
                    'ajaxConversion' => false,
                    'widgetOptions' => [
                        'pluginOptions' => [
                            'autoclose' => true,
                            'orientation' => 'bottom',
                            'endDate' => '0d',
                        ]
                    ]
                ]);
                ?>
            </div>
            <div class="col-md-4 filter-fields">
                <label class="control-label" for="to-date">To Date</label>
                <?php
                $toDate = '';
                if (isset($_GET['CasesSearch']['to_date'])) {
                    $toDate = $_GET['CasesSearch']['to_date'];
                }
                echo DateControl::widget([
                    'name' => 'to-date',
                    'id' => 'to-date',
                    'type'=>DateControl::FORMAT_DATE,
                    'value' => $toDate,
                    'displayFormat' => 'yyyy-MM-dd',
                    'saveFormat' => 'yyyy-MM-dd',
                    'ajaxConversion' => false,
                    'widgetOptions' => [
                        'pluginOptions' => [
                            'autoclose' => true,
                            'orientation' => 'bottom',
                            'endDate' => '0d',
                        ]
                    ]
                ]);
                ?>
            </div>
            <div class="col-md-1">
                <div class="fa fa-circle-o-notch fa-spin date-filters-loader" style="display: none;"></div>
            </div>
            <div class="col-md-4 filter-fields">
                <label class="control-label" for="to-date">Filter By Client</label>
                <?php
                $options = [];
                foreach($clients as $clientID => $clientName) {
                    if (isset($_GET['CasesSearch']['client_id']) && $_GET['CasesSearch']['client_id'] == $clientID) {
                        $options[$clientID] = ['Selected' => 'selected'];
                    } else {
                        $options[$clientID] = [];
                    }
                }
                echo Select2::widget([
                    'name' => 'client-filter',
                    'class' => 'form-group',
                    'data' => $clients,
                    'options' => [
                        'placeholder' => '--Select Client--',
                        'id' => 'client-filter',
                        'options' => $options
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);

                ?>
            </div>
            <div class="col-md-1">
                <div class="fa fa-circle-o-notch fa-spin client-filter-loader" style="display: none;"></div>
            </div>
            <div class="col-md-4 filter-fields">
                <label class="control-label" for="to-date">Filter By Client Entity</label>
                <?php
                //attaching data-client-id to client entity to disable values when a certain client is selected
                $clientIDs = [];
                foreach($clientEntities as $clientEntity) {
                    if (isset($_GET['CasesSearch']['client_entity']) && $_GET['CasesSearch']['client_entity'] == $clientEntity->id) {
                        $clientIDs[$clientEntity->id] = ['data-client-id' => $clientEntity->client_id, 'class' => 'client-entity-options', 'Selected' => 'selected'];
                    } else {
                        $clientIDs[$clientEntity->id] = ['data-client-id' => $clientEntity->client_id, 'class' => 'client-entity-options'];
                    }
                }

                echo Select2::widget([
                    'name' => 'client-entity-filter',
                    'class' => 'form-group',
                    'data'=> ArrayHelper::map($clientEntities, 'id', 'name'),
                    'options' => [
                        'placeholder' => '--Select Client Entity--',
                        'id' => 'client-entity-filter',
                        'options' => $clientIDs,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>
            <div class="col-md-1">
                <div class="fa fa-circle-o-notch fa-spin client-entity-filter-loader" style="display: none;"></div>
            </div>
            <div class="col-md-4 filter-fields">
                <label class="control-label" for="to-date">Filter By Case Worker</label>
                <?php
                $options = [];
                foreach($caseworkers as $caseworkerID => $caseworkerEmail) {
                    if (isset($_GET['CasesSearch']['case_worker']) && $_GET['CasesSearch']['case_worker'] == $caseworkerID) {
                        $options[$caseworkerID] = ['Selected' => 'selected'];
                    } else {
                        $options[$caseworkerID] = [];
                    }
                }

                echo Select2::widget([
                    'name' => 'case-worker-filter',
                    'class' => 'form-group',
                    'data' => $caseworkers,
                    'options' => [
                        'placeholder' => '--Select Case Worker--',
                        'id' => 'case-worker-filter',
                        'options' => $options,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>
            <div class="col-md-1">
                <div class="fa fa-circle-o-notch fa-spin case-worker-filter-loader" style="display: none;"></div>
            </div>
        </div>
    </div>
<div class="report-index col-md-12">
    <style>
      .view-report-button {
        background-color: #727272;
        color: white;
        padding: 8px 30px;
        float: right;
        transition: 0.2s linear;
        border: 1px solid #727272;
      }

      .view-report-button:hover {
        color: black;
        background-color: white;
      }

      .disabled-clear-filter {
                visibility: hidden;
            }
    </style>

    <div class="panel panel-default card-view panel panel-refresh">
      <div class="refresh-container"></div>
      <div class="panel-heading">
        <div class="panel-title txt-dark">Reports</div>
      </div>
      <?php Pjax::begin(['id' => 'report-pjax']); ?>
      <?= GridView::widget([
          'dataProvider' => $dataProvider,
          'tableOptions'=>['class'=>'table data-table'],
          'columns' => [
              ['class' => 'yii\grid\SerialColumn',
                  'headerOptions' => ['style' => 'width: 5%']
              ],

              [
                'attribute' => 'username',
                'label' => 'Case Worker Name',
              ],

              [
                'label' => 'Active Cases',
                'attribute' => 'active_cases_count'
              ],

              [
                'label' => 'Completed Cases / Sent for billing',
                'attribute' => 'completed_cases_count',
              ],

              [
                'format' => 'raw',
                'label' => false,
                'value' => function ($model) {
                    $params = ['id' => $model['id']]; // Base parameters

                // Check if the 'clientCW' parameter is set
                if (isset($_GET['clientCW'])) {
                    $params['clientCW'] = 1; // Append 'clientCW' parameter if set
                }
                if(isset($_GET['CasesSearch']['from_date']))
                    $params['CasesSearch']['from_date']= $_GET['CasesSearch']['from_date'];

                if(isset($_GET['CasesSearch']['to_date']))
                    $params['CasesSearch']['to_date']= $_GET['CasesSearch']['to_date'];

                if(isset($_GET['CasesSearch']['client_id']))
                    $params['CasesSearch']['client_id']= $_GET['CasesSearch']['client_id'];

                if(isset($_GET['CasesSearch']['client_entity']))
                    $params['CasesSearch']['client_entity']= $_GET['CasesSearch']['client_entity'];

                //   if (isset($_GET['clientCW'])) {
                //     return '<a class="view-report-button" href="' . Yii::$app->urlManager->createAbsoluteUrl(['report/view', 'id' => $model['id'], 'clientCW' => 1]) . '">View</a>';
                //   }
                  return '<a class="view-report-button" href="' . Yii::$app->urlManager->createAbsoluteUrl(['report/view']+ $params) . '">View</a>';
                } 
              ]
          ],
      ]); ?>
      <?php Pjax::end() ?>
    </div>
    <script>
                // var url = '<?php //echo \yii\helpers\Url::to(['cases/index']); ?>';
                var url = window.location.href;
                function attachScripts() {
                    $(".case-worker-dropdown").each(function() {
                        const dropdown = $(this);
                        if (dropdown.attr('value')) {
                            const value = dropdown.attr('value')
                            dropdown.children().each(function () {
                                if ($(this).attr('value') === value) {
                                    $(this).prop('selected', true);
                                }
                            })
                        }
                    });
                    $(".case-manager-dropdown").each(function() {
                        const dropdown = $(this);
                        if (dropdown.attr('value')) {
                            const value = dropdown.attr('value')
                            dropdown.children().each(function () {
                                if ($(this).attr('value') === value) {
                                    $(this).prop('selected', true);
                                }
                            })
                        }
                    });

                    $(".loading-div").each(function() {
                        $(this).prop('style', 'display: none;');
                    })
                    $(".loading-div-manager").each(function() {
                        $(this).prop('style', 'display: none;');
                    })

                    $(".mark-as-billed-checkbox").each(function() {
                        if ($(this).attr('value') === '1') {
                            $(this).prop('checked', true);
                        }
                    })


                    $('.case-worker-dropdown').on('change', function () {
                        let caseID = $(this).attr('case-id')
                        $(".loading-div").each(function() {
                            if ($(this).attr('case-id') === caseID) {
                                $(this).prop('style', 'display: inline-block;');
                            }
                        })
                        $.ajax({
                        type: 'POST',
                        url: '<?php echo \yii\helpers\Url::to(['/cases/assign-case']); ?>',
                        data: {
                            caseWorkerID: $(this).val(),
                            caseID: $(this).attr('case-id')
                        },
                        success: function() {
                                    $(".loading-div").each(function() {
                                        $(this).prop('style', 'display: none;');
                                    })
                                    toastr.success("Case Worker Updated!");
                                },
                        });
                    })
                    $('.case-manager-dropdown').on('change', function () {
                        let caseID = $(this).attr('case-id')
                        $(".loading-div-manager").each(function() {
                            if ($(this).attr('case-id') === caseID) {
                                $(this).prop('style', 'display: inline-block;');
                            }
                        })
                        $.ajax({
                        type: 'POST',
                        url: '<?php echo \yii\helpers\Url::to(['/cases/assign-case-manager']); ?>',
                        data: {
                            caseManagerID: $(this).val(),
                            caseID: $(this).attr('case-id')
                        },
                        success: function() {
                                    $(".loading-div-manager").each(function() {
                                        $(this).prop('style', 'display: none;');
                                    })
                                    toastr.success("Case Manager Updated!");
                                },
                        });
                    })
                    $('.mark-as-billed-checkbox').on('change', function() {
                        if ($(this).attr('value') === "0") {
                            $(this).prop('value', "1")
                        } else {
                            $(this).prop('value', "0")
                        }
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo \yii\helpers\Url::to(['cases/mark-as-billed']);?>',
                            data: {
                                checked: $(this).attr('value'),
                                caseID: $(this).attr('case_id')
                            },
                            success: function() {

                            }
                        })
                    })
                }

                $(document).ready(attachScripts);

                $(document).ready(function () {
                    function checkClearAllButton() {
                        if (window.location.search != '') {
                            $('.clear-all-filters').removeClass('disabled-clear-filter')
                        } else {
                            $('.clear-all-filters').addClass('disabled-clear-filter')
                        }
                    }


                    $('.case-status, .clear-filter').on('click', function() {
                        var statusID = $(this).attr('data-id')
                        if (statusID) {
                            //checking and removing case_status from query params
                            if (url.indexOf('CasesSearch%5Bcase_status%5D=') != -1) {
                                url = url.replace(/&?\b\CasesSearch%5Bcase_status%5D=\d+/, "");
                            }

                            //adding query params
                            if (url.endsWith("index")) {
                                url += '?CasesSearch%5Bcase_status%5D=' + statusID;
                            } else if (url.endsWith("index?")) {
                                url += 'CasesSearch%5Bcase_status%5D=' + statusID;
                            } else {
                                url += '&CasesSearch%5Bcase_status%5D=' + statusID;
                            }
                        } else {
                            if (url.indexOf('CasesSearch%5Bcase_status%5D=') != -1) {
                                url = url.replace(/&?\b\CasesSearch%5Bcase_status%5D=\d+/, "");
                            }
                        }
                        $.ajax({
                            type: 'GET',
                            url: url,
                            success: function() {
                                $.pjax.reload({container: '#report-pjax', timeout: 3000, url: url, async: false});
                                $('.case-status').each(function() {
                                    if (statusID == $(this).attr('data-id')) {
                                        $(this).addClass('active-case-status')
                                    } else {
                                        $(this).removeClass('active-case-status')
                                    }

                                    //show/hide clear-filter button
                                    if (statusID) {
                                        $('.clear-filter').show();
                                    } else {
                                        $('.clear-filter').hide();
                                    }

                                    checkClearAllButton()
                                })
                            }
                        })
                    })

                    //handles searching for the date filters
                    function changeDate() {
                        if ($('#to-date').val() && ($('#from-date').val() > $('#to-date').val())) {
                            toastr.warning('From Date cannot be greater than To Date');
                            return;
                        }

                        $('.date-filters-loader').attr('style', 'display: inline-block;')

                        //if both are empty, remove date related query params
                        if (!($('#to-date').val() || ($('#from-date').val()))) {
                            var regexPattern = /&?\bCasesSearch%5Bfrom_date%5D=\d{0,4}-?\d{0,2}-?\d{0,2}&\bCasesSearch%5Bto_date%5D=\d{0,4}-?\d{0,2}-?\d{0,2}/;
                            url = url.replace(regexPattern, "");
                        } else if (url.indexOf('CasesSearch%5Bfrom_date%5D=') != -1 || url.indexOf('CasesSearch%5Bto_date%5D=') != -1) {
                            //if one date is already there, remove and re-add the query params
                            var regexPattern = /&?\bCasesSearch%5Bfrom_date%5D=\d{0,4}-?\d{0,2}-?\d{0,2}&\bCasesSearch%5Bto_date%5D=\d{0,4}-?\d{0,2}-?\d{0,2}/;
                            url = url.replace(regexPattern, "");
                            if (url.endsWith("index")) {
                                url += '?CasesSearch%5Bfrom_date%5D=' + $('#from-date').val() + '&CasesSearch%5Bto_date%5D=' + $('#to-date').val();
                            } else if (url.endsWith("index?")) {
                                url += 'CasesSearch%5Bfrom_date%5D=' + $('#from-date').val() + '&CasesSearch%5Bto_date%5D=' + $('#to-date').val();
                            } else {
                                url += '&CasesSearch%5Bfrom_date%5D=' + $('#from-date').val() + '&CasesSearch%5Bto_date%5D=' + $('#to-date').val();
                            }
                        } else {
                            //if no date related query params are there, add them
                            if (url.endsWith("index")) {
                                url += '?CasesSearch%5Bfrom_date%5D=' + $('#from-date').val() + '&CasesSearch%5Bto_date%5D=' + $('#to-date').val();
                            } else if (url.endsWith("index?"))
                                url += 'CasesSearch%5Bfrom_date%5D=' + $('#from-date').val() + '&CasesSearch%5Bto_date%5D=' + $('#to-date').val();
                            else {
                                url += '&CasesSearch%5Bfrom_date%5D=' + $('#from-date').val() + '&CasesSearch%5Bto_date%5D=' + $('#to-date').val();
                            }
                        }

                        $.ajax({
                            type: 'GET',
                            url: url,
                            success: function() {
                                $.pjax.reload({container: '#report-pjax', timeout: 3000, url: url, async: false});
                                $('.date-filters-loader').attr('style', 'display: none;')
                                checkClearAllButton()
                            }
                        })
                    }

                    //generic filter for searching using dropdown
                    function clientFilter() {
                        $('.' + $(this).attr('id') + '-loader').attr('style', 'display: inline-block;');
                        var attribute = '';
                        var regexPattern = '';

                        //checking for client-filter dropdown, setting attribute and regex pattern
                        if ($(this).attr('id') == 'client-filter') {
                            attribute = 'client_id';
                            regexPattern = /&?\bCasesSearch%5Bclient_id%5D=\d+/;

                            //logic to enable only selected client's client entities
                            var clientID = $(this).val();
                            if (clientID == '') {
                                $('#client-entity-filter').children().each(function() {
                                    $(this).attr('disabled', false);
                                })
                            } else {
                                $('#client-entity-filter').children().each(function() {
                                    if ($(this).attr('data-client-id') != clientID) {
                                        $(this).attr('disabled', true);
                                    } else {
                                        $(this).attr('disabled', false);
                                    }
                                })
                            }
                        } else if ($(this).attr('id') == 'client-entity-filter') {
                            //checking for client entity filter
                            attribute = 'client_entity';
                            regexPattern = /&?\bCasesSearch%5Bclient_entity%5D=\d+/;
                        } else if ($(this).attr('id') == 'case-worker-filter') {
                            //checking for case worker filter
                            attribute = 'case_worker';
                            regexPattern = /&?\bCasesSearch%5Bcase_worker%5D=\d+/;
                        }

                        //if empty, remove the query params
                        if ($(this).val() == "" || $(this).val() == null) {
                            url = url.replace(regexPattern, '');
                        } else if (url.indexOf('CasesSearch%5B' + attribute + '%5D=') != -1) {
                            //if found, replace
                            url = url.replace(regexPattern, '');
                            if (url.endsWith('index')) {
                                url += '?CasesSearch%5B' + attribute + '%5D=' + $(this).val();
                            } else if (url.endsWith('index?')) {
                                url += 'CasesSearch%5B' + attribute + '%5D=' + $(this).val();
                            } else {
                                url += '&CasesSearch%5B' + attribute + '%5D=' + $(this).val();
                            }
                        } else {
                            //add query params
                            if (url.endsWith('index')) {
                                url += '?CasesSearch%5B' + attribute + '%5D=' + $(this).val();
                            } else if (url.endsWith('index?')) {
                                url += 'CasesSearch%5B' + attribute + '%5D=' + $(this).val();
                            } else {
                                url += '&CasesSearch%5B' + attribute + '%5D=' + $(this).val();
                            }
                        }

                        //setting id to hide loader after successful pjax
                        var id = $(this).attr('id')

                        $.ajax({
                            type: 'GET',
                            url: url,
                            success: function() {
                                $.pjax.reload({container: '#report-pjax', timeout: false, url: url, async: false});
                                $('.' + id + '-loader').attr('style', 'display: none;');
                                checkClearAllButton()
                            }
                        })
                    }

                    //attaching listeners
                    $('#from-date, #to-date').on('change', changeDate);
                    $('#client-filter').on('change', clientFilter);
                    $('#client-entity-filter').on('change', clientFilter);
                    $('#case-worker-filter').on('change', clientFilter);
                })
                $(document).on('pjax:end', attachScripts)
            </script>
</div>