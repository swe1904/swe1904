<?php

use app\components\GlobalConstant;
use backend\models\Applicant;
use backend\models\Cases;
use backend\models\Client;
use backend\models\CaseType;
use backend\models\CaseSteps;   //Nemanja
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use common\models\User;
use backend\models\Organisation;
use yii\widgets\ActiveForm;
use backend\models\CaseStatus;
use yii\widgets\Pjax;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\CasesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

if (isset($_GET['CasesSearch']['applicant_id']) && !empty($_GET['CasesSearch']['applicant_id'])) {
    $applicant_id = $_GET['CasesSearch']['applicant_id'];
     if(isset( $applicant_id)){
        $applicant_firstname = Applicant::findOne($applicant_id)->first_name;
     }
    
} else $applicant_firstname = '';

$this->title = Yii::t('backend', 'Cases');
if (!empty($searchModel->applicant_id)) {
    $this->title .= ": " . $applicant_firstname;
}
$this->params['breadcrumbs'][] = $this->title;

?>
<?php
if (Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_MANAGER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_WORKER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_MANAGER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_CASE_MANAGER) {
    if (Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN) {
        $organisationID = Organisation::findOne(['user_id' => Yii::$app->user->id])->id;
    } else {
        $organisationID = User::findOne(Yii::$app->user->id)->organisation_id;
    }

    $connection = Yii::$app->getDb();
    $command = $connection->createCommand("
            SELECT username, id 
            FROM tbl_user, tbl_rbac_auth_assignment
            WHERE tbl_user.id = tbl_rbac_auth_assignment.user_id 
                AND tbl_rbac_auth_assignment.item_name = :role 
                AND tbl_user.organisation_id = :org_id; 
        ", [':role' => 'Case Worker', ':org_id' => $organisationID]);

    $caseWorkers = $command->queryAll();
    if (empty($searchModel->assigned_to)) {
        array_unshift($caseWorkers, ["username" => "Select Case Worker"]);
    }
} else {
    $caseWorkers = [];
}

// Case Manager
if (Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_MANAGER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_WORKER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_MANAGER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_CASE_MANAGER) {
    if (Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN) {
        $organisationID = Organisation::findOne(['user_id' => Yii::$app->user->id])->id;
    } else {
        $organisationID = User::findOne(Yii::$app->user->id)->organisation_id;
    }

    $connection = Yii::$app->getDb();
    $command = $connection->createCommand("
            SELECT username, id 
            FROM tbl_user, tbl_rbac_auth_assignment
            WHERE tbl_user.id = tbl_rbac_auth_assignment.user_id 
                AND tbl_rbac_auth_assignment.item_name = :role 
                AND tbl_user.organisation_id = :org_id; 
        ", [':role' => 'Case Manager', ':org_id' => $organisationID]);
        
        $caseManager = $command->queryAll();
        if (empty($searchModel->assigned_to)) {
            array_unshift($caseManager, ["username" => "Select Case Manager"]);
        }
        
    } else {
        $caseManager = [];
    }
?>

<div class="row">
    <div class="col-md-12 ">
        <style>
            .my-input {
                border-radius: 12px;
                box-shadow: .5px .5px 8px lightgray;
                outline: none;
                padding: 5px 14px;
                cursor: pointer;
            }
  .filters {
                display: contents !important;
            } 

            .my-input1 {
                border-radius: 12px;
                box-shadow: .2px .2px 8px lightgray;
                outline: none;
                padding: 0 5px;
                font-size: 13px;
                cursor: pointer;
            }
            .case-status{
                background-color: #E6E6E6;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0px 10px;
                border-radius: 7px;
                border: none;
                padding: 10px 20px;
                color: #111;
                transition: 0.2s ease-in;
            }

            .case-status span {
                transition: 0.2s ease-in;
            }

            .case-status:hover {
                background-color: #5e6366;
            }

            .case-status:hover span {
                color: #fff;
            }

            .active-case-status {
                background-color: #6B6B6B;
            }

            .active-case-status span {
                color: white !important;
            }

            #w0 {
                display: flex;
                align-items: center;
            }

            .disabled-clear-filter {
                visibility: hidden;
            }
        </style>
        <?php
        $style = "";
        if (!in_array(Yii::$app->user->identity->getRole(), [GlobalConstant::ROLE_ORGANISATION_ADMIN, GlobalConstant::ROLE_ORGANISATION_MANAGER, GlobalConstant::ROLE_CLIENT, GlobalConstant::ROLE_CLIENT_HR, GlobalConstant::ROLE_CASE_MANAGER, GlobalConstant::ROLE_CASE_WORKER])) {
            $style = "display: none;";
        } ?>
        <div class="panel panel-default card-view border-panel panel-refresh" style="<?php echo $style ?>">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">Create Case</h6>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-wrapper collapse in">
                <div class="panel-body test">
                    <?php $form = ActiveForm::begin(
                        [
                            'action' => \yii\helpers\Url::to(['applicant/index']),
                            'fieldConfig' => [
                                'options' => [
                                    'options' => ['class' => 'form-group invisible'],
                                ],
                            ]
                        ],
                    ) ?>

                    <!-- <div class="col-md-4">
                                <?php // echo $form->field($model, 'case_type_id')
                                //->dropDownList(ArrayHelper::map(CaseType::find()->all(), 'id', 'name'), ['prompt' => 'Select Case Type', 'class' => 'case_type_dropdown my-input', 'required' => true])
                                //->label('Case Type'); 
                                ?>
                            </div>
                            <div class="col-md-2">
                                <p>
                                    <? //= Html::submitButton(Yii::t('backend', 'Create'), ['class' => 'btn btn-sm btn-rounded btn-success mt-20']); 
                                    ?>
                                </p>
                            </div> -->
                    <?= Html::a(Yii::t('backend', 'Create'), ['cases/create'], ['class' => 'btn btn-sm btn-rounded btn-success mt-20']); ?>


                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>

    </div>
</div>
<style>
    table tr td:last-of-type {
        margin-top: 10px;
    }
</style>
<div class="panel panel-default card-view border-panel panel-refresh" style="display: flex; flex-direction: column;">
    <?php if (in_array(Yii::$app->user->identity->getRole(), [GlobalConstant::ROLE_SUPERADMIN, GlobalConstant::ROLE_ORGANISATION_ADMIN, GlobalConstant::ROLE_ORGANISATION_MANAGER, GlobalConstant::ROLE_FINANCE, GlobalConstant::ROLE_ORGANISATION_MANAGER, GlobalConstant::ROLE_CASE_WORKER, GlobalConstant::ROLE_CLIENT, GlobalConstant::ROLE_CLIENT_CASE_MANAGER, GlobalConstant::ROLE_CLIENT_CASE_WORKER, GlobalConstant::ROLE_CASE_MANAGER, GlobalConstant::ROLE_CLIENT_ENTITY_MANAGER,GlobalConstant::ROLE_CLIENT_GROUP_MANAGER])): ?>
        <div class="panel-heading" style="padding-left: 28px;">
            <span class="reciept-filter"> Filters </span>
            <a class="<?php echo count($_GET) == 0 ? 'disabled-clear-filter' : '' ?> clear-all-filters" style="margin-left: 10px" title="Reset All Filters" href="<?php echo Yii::$app->urlManager->createAbsoluteUrl(['cases/index']) ?>"><i class="fa fa-undo fa-lg" style="color: #d20511;"></i></a>
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
                    'type' => DateControl::FORMAT_DATE,
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
                    'type' => DateControl::FORMAT_DATE,
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
            <?php if (!in_array(Yii::$app->user->identity->getRole(), [GlobalConstant::ROLE_CLIENT_CASE_WORKER, GlobalConstant::ROLE_CLIENT, GlobalConstant::ROLE_CLIENT_CASE_MANAGER, GlobalConstant::ROLE_CASE_WORKER, GlobalConstant::ROLE_CASE_MANAGER,GlobalConstant::ROLE_CLIENT_GROUP_MANAGER,GlobalConstant::ROLE_CLIENT_ENTITY_MANAGER])) {

            ?>
                <div class="col-md-4 filter-fields">
                    <label class="control-label" for="to-date">Filter By Client</label>
                    <?php
                    $options = [];
                    foreach ($clients as $clientID => $clientName) {
                        if (isset($_GET['CasesSearch']['client_name']) && $_GET['CasesSearch']['client_name'] == $clientID) {
                            $options[$clientID] = ['Selected' => 'selected'];
                        } else {
                            $options[$clientID] = [];
                        }
                    }
                    echo Select2::widget([
                        'name' => 'client-filter',
                        'class' => 'form-group',
                        'data' => $filterClient,
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

                <div class="col-md-1">
                    <div class="fa fa-circle-o-notch fa-spin client-entity-filter-loader" style="display: none;"></div>
                </div>

                <div class="col-md-4 filter-fields">
                    <label class="control-label" for="to-date">Filter By Case Worker</label>
                    <?php
                    $options = [];
                    foreach ($caseworkers as $caseworkerID => $caseworkerEmail) {
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
            <?php } ?>

            <!-- <div class="col-md-2">
                    <a class="<?php //echo count($_GET) == 0 ? 'disabled-clear-filter' : '' 
                                ?> clear-all-filters" href="<?php //echo Yii::$app->urlManager->createAbsoluteUrl(['cases/index']) 
                                                                                                                        ?>">Clear All Filters</a>
                </div> -->
        </div>

        <?php if (!in_array(Yii::$app->user->identity->getRole(), [GlobalConstant::ROLE_FINANCE])) { ?>
            <ul style="display: flex; margin-bottom: 25px; flex-flow: row wrap;">
                <?php
                $statuses = CaseStatus::find()->all();
                if (!empty($statuses)) {
                    foreach ($statuses as $status) {
                        $class = 'case-status';
                        if (isset($_GET['CasesSearch']['case_status']) && ($_GET['CasesSearch']['case_status'] == $status->id)) {
                            $class = $class . ' active-case-status';
                        }
                        echo '<li style="margin: 5px 0px;"><button class="' . $class . '" data-id="' . $status->id . '">';
                        echo '<span>' . $status->name . '</span>';
                        echo '</button></li>';
                    }
                }
                ?>
                <?php
                if (isset($_GET['CasesSearch']['case_status'])) {
                    $style = 'display: inline-block;';
                } else {
                    $style = 'display: none;';
                }
                ?>
                <li style="align-self: center; cursor: pointer">
                    <span class="clear-filter" title="Clear Case Status Filter" style="<?php echo $style ?>">
                        <i class="fa fa-close" style="color: #d20511; font-size: 1.5em;" alt="Clear"></i>
                    </span>
                </li>
            </ul>
        <?php } ?>
        <div class="refresh-container">
            <div class="la-anim-1"></div>
        </div>
    <?php endif; ?>
    <div class="panel-heading">
        Cases
    </div>
    <?php
    $template = '{update}{steps} {view} {delete} {history} {download-case-file} {attach-docs}';
    if (Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_WORKER)
        $template = '{steps} {view} {delete} {history}{download-case-file}    {attach-docs}';
    elseif (Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_CASE_WORKER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_CASE_MANAGER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_GROUP_MANAGER||Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_ENTITY_MANAGER)
        $template = '{steps} {view}';
    
    ?>
 <?php if (isset($_GET['filtered']) && $_GET['filtered'] === 'true'): ?>
    <!-- Export Button -->
    <div class="export-section justify-content-end" style="margin-bottom: 15px;">
        <?= Html::button('<i class="fas fa-file-excel"></i> Export', [
            'class' => 'btn btn-success btn-lg',
            'id' => 'exportBtn',
            'style' => 'padding: 10px 20px; font-size: 16px; font-weight: bold; border-radius: 8px;',
        ]); ?>
    </div>
<?php endif; ?>

   <?php Pjax::begin(['id' => 'cases-pjax']); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table data-table'],
        'responsiveWrap' => false,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['class' => 'abc']
            ],
            [
                'attribute' => 'client_billing_entity',
                'filterInputOptions' => [
                    'class' => 'form-control border search',
                    'placeholder' => (new Cases)->getAttributeLabel('client_billing_entity'),
                ],
                'value' => function ($model) {
                    return $model->client_billing_entity;
                },
                'format' => 'raw',
                'visible' => Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_CASE_MANAGER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_CASE_WORKER||Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_ENTITY_MANAGER|| Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_GROUP_MANAGER,
                'headerOptions' => ['style' => 'width: 70%!important;'], // Adjust width as needed
                'contentOptions' => ['style' => 'width: 250px; white-space: normal;'], // Allow text wrapping
            ],
            
            [
                'attribute' => 'client_id',
                'label' => 'Client Name',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'client_id',
                    'data' => Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_SUPERADMIN ? $clients : $filterClient,
                    'options' => [
                        'placeholder' => 'Search client', 
                        'class' => 'form-control border search', 
                    ],
                    'pluginOptions' => [
                        'allowClear' => true, 
                    ],
                ]),
                'value' => function ($model) {
                    return $model->client ? $model->client->client_name : null; // Fallback if client is not set
                },
                'format' => 'raw',
                'visible' => (Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_MANAGER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_WORKER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_MANAGER||Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_SUPERADMIN||Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_ENTITY_MANAGER),
            ],
            [
                'attribute' =>  'case_number',
                'filterInputOptions' => [
                    'class' => 'form-control border search',
                    'placeholder' => (new Cases)->getAttributeLabel('case_number'),
                ]
            ],
            //combined applicant first name and last name into one gridview column
            // [ 'attribute' =>  'applicant_first_name',
            //     'label' => 'Applicant Name',
            //     'filterInputOptions' => [
            //         'class' => 'form-control search',
            //         'placeholder' => ((new Cases)->getAttributeLabel('applicant_first_name')),
            //     ],
            //     'value' => function($model) {
            //         $applicant = Applicant::findOne($model->applicant_id);
            //         return $applicant->first_name . ' ' . $applicant->last_name;
            //     }
            // ],

            // [ 'attribute' =>  'applicant_last_name',
            //     'filterInputOptions' => [
            //         'class' => 'form-control search',
            //         'placeholder' => (new Cases)->getAttributeLabel('applicant_last_name'),
            //     ]],

            // [ 'attribute' =>  'client_name',
            //     'filterInputOptions' => [
            //         'class' => 'form-control search',
            //         'placeholder' => (new Cases)->getAttributeLabel('client_name'),
            //     ]],
            [
                'attribute' => 'applicant_id',
                'label' => 'Applicant Name',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'applicant_id',
                    'data' => $applicants, 
                    'options' => [
                        'placeholder' => 'Select Applicant', 
                        'class' => 'form-control',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true, 
                    ],
                ]),
                'value' => function ($model) {
                    if (isset($model->applicant_id) && !empty($model->applicant_id)) {
                        if ($model->applicant) {
                            if (!empty($model->applicant->first_name) || !empty($model->applicant->last_name)) {
                                return $model->applicant->first_name . " " . $model->applicant->last_name;
                            }
                            if (!empty($model->applicant->email)) {
                                return $model->applicant->email;
                            }
                        }
                    }
                    return null;
                },
            ],

            // [
            //     'attribute' => 'client_entity', 
            //     'label' => 'Client Entity Name',
            //     'value' => function ($model) {
            //         if (isset($model->client_entity)) {
            //             return \backend\models\ClientEntity::findOne($model->client_entity)->name;
            //         }
            //     }
            // ],
            [
                'attribute' =>  'organisation_id',
                'label' => 'Organisation',

                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'organisation_id',
                    'data' => $organisations, 
                    'options' => [
                        'placeholder' => 'Select Organisation', 
                        'class' => 'form-control', 
                    ],
                    'pluginOptions' => [
                        'allowClear' => true, 
                    ],
                ]),

                'value' => function ($model) {
                    if (isset($model->organisation)) {
                        return Organisation::findOne($model->organisation_id)->name;
                    }
                }
            ],


            [
                'attribute' => 'case_type_id',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'case_type_id',
                    'data' => ArrayHelper::map(CaseType::find()->asArray()->all(), 'id', 'name'),
                    'options' => [
                        'placeholder' => 'Select case type',
                        'class' => 'form-control',
                        'id' => 'case-type-filter', 
                    ],
                    'pluginOptions' => [
                        'allowClear' => true, 
                    ],
                ]),
                'value' => 'caseType.name',
            ],
            [
                'attribute' =>  'case_work_office_id',
                'label' => 'CaseWork Office',

                // 'filter' => Html::activeDropDownList(
                //     $searchModel,
                //     'organisation_id',
                //     $organisations,
                //     ['class' => 'form-control', 'prompt' => 'Select Organisation']
                // ),
                // 'filterInputOptions' => [
                //     'class' => 'form-control border search',
                //     'prompt' => (new Cases)->getAttributeLabel('organisation_name'),
                // ],
                'value' => function ($model) {
                    if (isset($model->caseWorkOffice)) {
                        return Organisation::findOne($model->caseWorkOffice)->name;
                    }
                }
            ],

            // [ 'attribute' =>  'last_status_update',
            //     'filterInputOptions' => [
            //         'class' => 'form-control search',
            //         'placeholder' => (new Cases)->getAttributeLabel('last_status_update'),
            //     ]
            // ],
            [
                'attribute' => 'case_status',
                'value' => 'caseStatus.name'
            ],

            [
                'attribute' => 'created_at',
                'label' => 'Created Date',
                'value' => function ($model) {
                    return date('Y-m-d', strtotime($model->created_at));
                }
            ],
            
            [
                'attribute' => 'assigned_to',
                'label' => 'Case Worker',
                'filter' => Yii::$app->user->identity->getRole() !== GlobalConstant::ROLE_CASE_WORKER ? Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'assigned_to',
                    'data' => $filterCaseWorkers,
                    'options' => [
                        'placeholder' => 'Select Case Worker',
                        'class' => 'form-control caseworker',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,   
                        'multiple' => false, 
                    ],
                ]) : false,  

                'contentOptions' => ['style' => GlobalConstant::ACTION_STYLE],
                'value' => function ($data) {
                    if ($data->client_id) {
                        // Fetching the organization IDs associated with the client
                        $orgsIds = ArrayHelper::getColumn(
                            Organisation::find()
                                ->leftJoin('tbl_client_organisation', 'tbl_client_organisation.organisation_id = tbl_organisation.id')
                                ->andWhere(['tbl_client_organisation.client_id' => $data->client_id])
                                ->all(),
                            'id'
                        );
            
                        // Fetching case workers based on the organization IDs
                        $caseWorkers = ArrayHelper::map(
                            User::find()
                                ->join('LEFT JOIN', 'tbl_rbac_auth_assignment', 'tbl_rbac_auth_assignment.user_id = id')
                                ->andWhere(['tbl_rbac_auth_assignment.item_name' => GlobalConstant::ROLE_CASE_WORKER])
                                ->andWhere(['in', 'organisation_id', $orgsIds])
                                ->all(),
                            'id',
                            function ($model) {
                                if ($model->userProfile) {
                                    $firstName = $model->userProfile->firstname;
                                    $lastName = $model->userProfile->lastname;
            
                                    if (!empty($firstName) || !empty($lastName)) {
                                        return trim($firstName . ' ' . $lastName);
                                    }
                                }
                                return $model->username; // Fallback to username if profile details are not present
                            }
                        );
                    } else {
                        $caseWorkers = [];
                    }
                            // Check if the user is a Client Case Manager
                        if (Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_CASE_MANAGER) {
                            // Display the dropdown but make it disabled for the Client Case Manager
                            return Html::dropDownList(
                                'assigned_to',
                                $data->assigned_to,
                                $caseWorkers,
                                [
                                    'class' => 'form-group case-worker-dropdown my-input1',
                                    'disabled' => 'disabled', // Disable the dropdown
                                    'value' => $data["assigned_to"],
                                    'prompt' => 'Select a Case Worker',
                                ]
                            );
                        }
                    // Dropdown for case worker selection
                    return (
                        Html::dropDownList(
                            'assigned_to',
                            $data->assigned_to,
                            $caseWorkers,
                            [
                                'class' => 'form-group case-worker-dropdown my-input1',
                                'case-id' => $data["id"],
                                'value' => $data["assigned_to"],
                                'prompt' => 'Select a Case Worker',
                            ]
                        )
                        . '<div class="fa fa-circle-o-notch fa-spin loading-div" case-id="' . $data["id"] . '"></div>'
                    );
                },
                'format' => 'raw',
                'visible' => (Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_MANAGER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_WORKER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_MANAGER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_CASE_MANAGER),
            ],
            
            [
                'attribute' => 'case_manager_id',
                  'filter' => Yii::$app->user->identity->getRole() !== GlobalConstant::ROLE_CASE_MANAGER ? Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'case_manager_id',
                    'data' => $filterCaseManagers,
                    'options' => [
                        'placeholder' => 'Select Case Manager',
                        'class' => 'form-control',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true, 
                        'multiple' => false,   
                    ],
                ]): false,  
               
                'value' => function ($data) {
                    if ($data->client_id) {
                        $orgsIds = ArrayHelper::getColumn(Organisation::find()->leftJoin('tbl_client_organisation', 'tbl_client_organisation.organisation_id = tbl_organisation.id')->andWhere(['tbl_client_organisation.client_id' => $data->client_id])->all(), 'id');

                        $caseManagers = ArrayHelper::map(
                            User::find()->join('LEFT JOIN', 'tbl_rbac_auth_assignment', 'tbl_rbac_auth_assignment.user_id = id')->andWhere(['tbl_rbac_auth_assignment.item_name' => GlobalConstant::ROLE_CASE_MANAGER])->andWhere(['in', 'organisation_id', $orgsIds])->all(),
                            'id',
                            function ($model) {
                                if ($model->userProfile) {
                                    $firstName = $model->userProfile->firstname;
                                    $lastName = $model->userProfile->lastname;

                                    if (!empty($firstName) || !empty($lastName)) {
                                        return trim($firstName . ' ' . $lastName);
                                    }
                                }
                                return $model->username; //will return if $model->userProfile doesn't exists or first or last name doesn't exist

                            }
                        );
                    } else
                        $caseManagers = [];
                    return (
                        Html::dropDownList(         
                            'case_manager_id',
                            $data->case_manager_id,
                            $caseManagers,
                            [
                                'class' => 'form-group case-manager-dropdown my-input1',
                                'case-id' => $data['id'],
                                'value' => $data['case_manager_id'],
                                'prompt' => 'Select a Case Manager',
                                'style' => 'width: 200px !important;', 
                               
                            ],
                        ) . '<div class="fa fa-circle-o-notch fa-spin loading-div-manager" case-id="' . $data['id'] . '"></div>'
                    );
                },
                'format' => 'raw',
                'visible' => (Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_MANAGER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_WORKER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_MANAGER  ),
            ],
                // client case worker
                [
                    'attribute' => 'client_case_worker_id',
                'filter' => Yii::$app->user->identity->getRole() !== GlobalConstant::ROLE_CASE_MANAGER ? Select2::widget([
                'model' => $searchModel,
                'attribute' => 'client_case_worker_id',
                'data' => $filterClientCaseWorkers,
                'options' => [
                    'placeholder' => 'Select Client Case Worker',
                    'class' => 'form-control',
                ],
                'pluginOptions' => [
                    'allowClear' => true, 
                    'multiple' => false,   
                ],
                ]): false,  
                    'value' => function ($data) use ($dataProvider) {
                    
                        $clientCaseWorkers = ArrayHelper::map(
                            $dataProvider->models, 
                            'client_case_worker_id',
                            function ($case) {
                                return $case->clientCaseWorker 
                                    ? ($case->clientCaseWorker->username) 
                                    : null;
                            }
                        );

                        return Html::dropDownList(
                            'client_case_worker_id',
                            $data->client_case_worker_id,
                            $clientCaseWorkers,
                            [
                                'class' => 'form-group client-case-worker-dropdown my-input1',
                                'case-id' => $data->id,
                                'prompt' => 'Select Client Case Worker',
                                'style' => 'width: 200px !important;',
                            ]
                        );
                    },
                    'format' => 'raw',
                    'visible' => Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_ENTITY_MANAGER ||Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_GROUP_MANAGER,
                ],
                // client case manager
                [
                    'attribute' => 'client_case_manager_id',
                    'filter' => Yii::$app->user->identity->getRole() !== GlobalConstant::ROLE_CASE_MANAGER ? Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'client_case_manager_id',
                    'data' => $filterClientCaseManagers,
                    'options' => [
                        'placeholder' => 'Select Client Case Manager',
                        'class' => 'form-control',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true, 
                        'multiple' => false,   
                    ],
                ]): false,  
                'value' => function ($data) use ($dataProvider) {
                 
                    $clientCaseManagers = ArrayHelper::map(
                        $dataProvider->models, 
                        'client_case_manager_id',
                        function ($case) {
                            return $case->clientCaseManager 
                                ? ($case->clientCaseManager->username) 
                                : null;
                        }
                    );

                    return Html::dropDownList(
                        'client_case_manager_id',
                        $data->client_case_manager_id,
                        $clientCaseManagers,
                        [
                            'class' => 'form-group client-case-manager-dropdown my-input1',
                            'case-id' => $data->id,
                            'prompt' => 'Select Client Case Manager',
                            'style' => 'width: 200px !important;',
                        ]
                    );
                },
                'format' => 'raw',
                'visible' => Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_ENTITY_MANAGER ||Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_GROUP_MANAGER  ,
            ],

            
         

            [
                'attribute' => 'is_billed',
                'label' => 'Mark as billed',
                'value' => function ($data) use ($searchModel) {
                    return Html::checkbox('is_billed', false, [
                        'class' => 'mark-as-billed-checkbox',
                        'value' => $data->is_billed,
                        'case_id' => $data->id,
                    ]);
                },
                'format' => 'raw',
                'visible' => Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_FINANCE,
            ],


            /*  [ 'attribute' =>  'last_action_taken',
                      'filterInputOptions' => [
                          'class' => 'form-control search',
                          'placeholder' => (new Cases)->getAttributeLabel('last_action_taken'),
                      ]],


                  [ 'attribute' =>  'sending_country',
                      'filterInputOptions' => [
                          'class' => 'form-control search',
                          'placeholder' => (new Cases)->getAttributeLabel('sending_country'),
                      ]],

                  [ 'attribute' =>  'receiving_country',
                      'filterInputOptions' => [
                          'class' => 'form-control search',
                          'placeholder' => (new Cases)->getAttributeLabel('receiving_country'),
                      ]],


                  [ 'attribute' =>  'applicant_first_name',
                      'filterInputOptions' => [
                          'class' => 'form-control search',
                          'placeholder' => (new Cases)->getAttributeLabel('applicant_first_name'),
                      ]],

                  [ 'attribute' =>  'applicant_last_name',
                      'filterInputOptions' => [
                          'class' => 'form-control search',
                          'placeholder' => (new Cases)->getAttributeLabel('applicant_last_name'),
                      ]],

                  [ 'attribute' =>  'date_of_birth',
                      'filterInputOptions' => [
                          'class' => 'form-control search',
                          'placeholder' => (new Cases)->getAttributeLabel('date_of_birth'),
                      ]],

                  [ 'attribute' =>  'passport_number',
                      'filterInputOptions' => [
                          'class' => 'form-control search',
                          'placeholder' => (new Cases)->getAttributeLabel('passport_number'),
                      ]],

                  [ 'attribute' =>  'mobile_number',
                      'filterInputOptions' => [
                          'class' => 'form-control search',
                          'placeholder' => (new Cases)->getAttributeLabel('mobile_number'),
                      ]],

                  [ 'attribute' =>  'office_address',
                      'filterInputOptions' => [
                          'class' => 'form-control search',
                          'placeholder' => (new Cases)->getAttributeLabel('office_address'),
                      ]],*/


            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['class' => 'abc', 'width' => '17%'],
                'contentOptions' => ['style' => GlobalConstant::ACTION_STYLE],
                'header' => 'Case Steps',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        if (in_array(Yii::$app->user->identity->getRole(), [GlobalConstant::ROLE_CLIENT, GlobaLConstant::ROLE_CLIENT_HR,])) {
                            return;
                        }
                        if (Yii::$app->user->can(GlobalConstant::ROLE_ORGANISATION_ADMIN) || Yii::$app->user->can(GlobalConstant::ROLE_ORGANISATION_MANAGER)) {
                            $url = Yii::$app->urlManager->createUrl([Yii::$app->controller->id . '/delete', 'id' => $model->id]);
                            return '<a class="mr-15" href="' . $url . '" data-method="post" data-confirm = "' . Yii::t('yii', 'Are you sure you want to delete this item?') . '",  title="Delete"><i class="fa fa-close text-danger"></i></a>';
                        } else {
                            return '<i class="mr-15 fa fa-close" style="color: lightgray;"></i>';
                        }
                    },
                    'update' => function ($url, $model) {

                        if (in_array(Yii::$app->user->identity->getRole(), [GlobalConstant::ROLE_SUPERADMIN, GlobalConstant::ROLE_CLIENT, GlobalConstant::ROLE_CASE_WORKER, GlobalConstant::ROLE_CLIENT_CASE_WORKER])) //ROLES NOT ALLOWED UPDATE ACCESS
                        {
                            return;
                        }
                        if ($model->case_status == 41) {
                            return '<a><i class="fa fa-pencil" style="color: lightgray;"></i></a>';
                        }
                        // $case = Cases::findOne($model->id);
                        $url = Yii::$app->urlManager->createUrl(['cases/update', 'id' => $model->id]);
                        return '<a class="mr-15" href="' . $url . '" title="Update"><i class="fa fa-pencil text-success text-inverse"></i></a>';
                    },
                    'view' => function ($url, $model) {
                        $url = Yii::$app->urlManager->createUrl(['cases/view', 'id' => $model->id]);
                        return '<a class="mr-15" href="' . $url . '" title="View Details"><i class="fa fa-eye" style="color: orange;"></i></a>';
                    },
                    'steps' => function ($url, $model) {
                        // if (in_array(Yii::$app->user->identity->getRole(), [GlobalConstant::ROLE_CLIENT, GlobaLConstant::ROLE_CLIENT_HR])) {
                        // if (in_array(Yii::$app->user->identity->getRole(), [GlobaLConstant::ROLE_CLIENT_HR])) {
                        //     return;
                        // }
                        $url = Yii::$app->urlManager->createUrl(['/case-steps/index', 'CaseStepsSearch[case_id]' => $model->id]);
                        return '<a class="mr-15" href="' . $url . '" title="Show Steps"><i class="fa fa-list text-primary"></i></a>';
                    },

                    'history' => function ($url, $model) {
                        if (in_array(Yii::$app->user->identity->getRole(), [GlobalConstant::ROLE_CLIENT, GlobaLConstant::ROLE_CLIENT_HR])) {
                            return;
                        }
                        $url = Yii::$app->urlManager->createUrl(['/case-history/', 'CaseHistorySearch[case_id]' => $model->id]);
                        return '<a class="mr-15" href="' . $url . '" title="history"><i class="fa fa-undo text-grey"></i></a>';
                    },

                    'download-case-file' => function ($url, $model) {
                        if (in_array(Yii::$app->user->identity->getRole(), [GlobalConstant::ROLE_CLIENT, GlobaLConstant::ROLE_CLIENT_HR])) {
                            return;
                        }
                        $url = Yii::$app->urlManager->createUrl(['cases/download-case-file', 'caseID' => $model->id]);
                        return '<a data-pjax="0" class="mr-15" href="' . $url . '" title="Download Case File"><i class="fa fa-info-circle"></i></a>';
                    },

                    'attach-docs' => function ($url, $model) {
                        if (
                            Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN) ||
                            Yii::$app->user->can(GlobalConstant::ROLE_ORGANISATION_ADMIN) ||
                            Yii::$app->user->can(GlobalConstant::ROLE_ORGANISATION_MANAGER) ||
                            Yii::$app->user->can(GlobalConstant::ROLE_CASE_WORKER) ||
                            Yii::$app->user->can(GlobalConstant::ROLE_CASE_MANAGER) ||
                            Yii::$app->user->can(GlobalConstant::ROLE_CLIENT) ||
                            Yii::$app->user->can(GlobalConstant::ROLE_CLIENT_HR) ||
                            Yii::$app->user->can(GlobalConstant::ROLE_FINANCE)
                        ) {
                            $url = Yii::$app->urlManager->createUrl(['cases/attach-documents', 'caseID' => $model->id]);
                            return '<a class="mr-15" href="' . $url . '" title="Attach Documents"><i class="fa fa-file-text-o"></i></a>';
                        }
                    },
                ],
                // 'template' => '{steps} {update} {view} {delete} {history} {download-case-file} {attach-docs}',
                'template' => $template,
            ],
        ],
    ]);
    ?>
    <?php Pjax::end() ?>
    <!-- Include SheetJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        // var url = '<?php //echo \yii\helpers\Url::to(['cases/index']); 
                        ?>';
        var url = window.location.href;

        function attachScripts() {
            $(".case-worker-dropdown").each(function() {
                const dropdown = $(this);
                if (dropdown.attr('value')) {
                    const value = dropdown.attr('value')
                    dropdown.children().each(function() {
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
                    dropdown.children().each(function() {
                        if ($(this).attr('value') === value) {
                            $(this).prop('selected', true);
                        }
                    })
                }
            });
            $(".client-case-worker-dropdown").each(function() {
                const dropdown = $(this);
                if (dropdown.attr('value')) {
                    const value = dropdown.attr('value')
                    dropdown.children().each(function() {
                        if ($(this).attr('value') === value) {
                            $(this).prop('selected', true);
                        }
                    })
                }
            });
            $(".client-case-manager-dropdown").each(function() {
                const dropdown = $(this);
                if (dropdown.attr('value')) {
                    const value = dropdown.attr('value')
                    dropdown.children().each(function() {
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


            $('.case-worker-dropdown').on('change', function() {
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
            // client case worker
            $('.client-case-worker-dropdown').on('change', function() {
                let caseID = $(this).attr('case-id')
                $(".loading-div").each(function() {
                    if ($(this).attr('case-id') === caseID) {
                        $(this).prop('style', 'display: inline-block;');
                    }
                })
                $.ajax({
                    type: 'POST',
                    url: '<?php echo \yii\helpers\Url::to(['/cases/assign-client-case']); ?>',
                    data: {
                        clientCaseWorkerID: $(this).val(),
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
            // case-manager
            $('.case-manager-dropdown').on('change', function() {
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
            // client case-manager
            $('.client-case-manager-dropdown').on('change', function() {
                let caseID = $(this).attr('case-id')
                $(".loading-div-manager").each(function() {
                    if ($(this).attr('case-id') === caseID) {
                        $(this).prop('style', 'display: inline-block;');
                    }
                })
                $.ajax({
                    type: 'POST',
                    url: '<?php echo \yii\helpers\Url::to(['/cases/assign-client-case-manager']); ?>',
                    data: {
                        clientCaseManagerID: $(this).val(),
                        caseID: $(this).attr('case-id')
                    },
                    success: function() {
                        $(".loading-div-manager").each(function() {
                            $(this).prop('style', 'display: none;');
                        })
                        toastr.success("Client Case Manager Updated!");
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
                    url: '<?php echo \yii\helpers\Url::to(['cases/mark-as-billed']); ?>',
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

        $(document).ready(function() {
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
                        $.pjax.reload({
                            container: '#cases-pjax',
                            timeout: 3000,
                            url: url,
                            async: false
                        });
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
                        $.pjax.reload({
                            container: '#cases-pjax',
                            timeout: 3000,
                            url: url,
                            async: false
                        });
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
                        $.pjax.reload({
                            container: '#cases-pjax',
                            timeout: false,
                            url: url,
                            async: false
                        });
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
        });

        
        $(document).on('pjax:end', attachScripts)
        
        function exportToExcel() {
            var urlParams = new URLSearchParams(window.location.search);
            // Remove 'filtered' parameter if it's unnecessary
            urlParams.delete('filtered');
        $.ajax({
            url: "<?= Yii::$app->urlManager->createUrl(['cases/export-all'])?>" + '?' + urlParams.toString(),
            type: 'GET',
            dataType: 'json', // Expect JSON response
            success: function(data) {
                if (!data || data.length === 0) {
                    alert('No data available to export.');
                    return;
                }

                // Convert JSON data to worksheet
                var ws = XLSX.utils.json_to_sheet(data);

                // Create a new workbook and append the worksheet
                var wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'Cases');

                // Save the file
                XLSX.writeFile(wb, 'Cases.xlsx');
            },
            error: function(xhr, status, error) {
                alert('Failed to export data. Error: ' + error);
            }
       });
}
        
    $('#exportBtn').on('click', function() {
        
        exportToExcel();
    });
    </script>
    <!-- <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Check if the user's role is "CASE_WORKER"
        var userRole = "<?php echo Yii::$app->user->identity->getRole(); ?>";
        
        if (userRole === "<?php echo GlobalConstant::ROLE_CASE_WORKER; ?>") {
            // Find the input field and hide it completely
            var inputField = document.querySelector('input[name="CasesSearch[assigned_to]"]');
            if (inputField) {
                inputField.style.display = "none";
            }
        }
        if (userRole === "<?php echo GlobalConstant::ROLE_CASE_MANAGER; ?>") {
            // Find the input field and hide it completely
            var inputField = document.querySelector('input[name="CasesSearch[case_manager_id]"]');
            if (inputField) {
                inputField.style.display = "none";
            }
        }
    });
</script> -->

<script>
  


        
</script>

</div>