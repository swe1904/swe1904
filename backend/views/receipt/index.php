<?php

use app\components\GlobalConstant;
use backend\components\Helper;
use frontend\models\Plan;
use yii\bootstrap\ButtonDropdown;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu;
use yii\widgets\Pjax;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ReceiptSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$quoteActive='';
$invoiceActive='';
$receiptActive='';
$receiptType='Receipt';
if(isset($_GET['Receipt']['quotes']))
   { $this->title = 'Quotes';
$quoteActive='active';
$receiptType='Quote';
   }
elseif(isset($_GET['Receipt']['invoices']))
   { $this->title = 'Invoices';
       $invoiceActive='active';
       $receiptType='Invoice';
   }
else
{  $this->title = 'Receipts';
    $receiptActive='active';
    $receiptType='Receipt';
}

$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    tr td a{
        padding: 5px;
    }
</style>

<!-- Basic design -->
<div class="row">
<div class="col-md-12"><!--col-lg-8 col-lg-offset-2-->
    <div class="panel panel-default card-view panel-refresh">
        <style>
            .disabled-clear-filter {
                visibility: hidden;
            }
        </style>
        <div class="panel-hading">

        </div>
        <div class="container-fluid">
            <ul class="nav nav-tabs">
                <?php if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_FINANCE || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_MANAGER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_WORKER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_GLOBAL_FINANCE): ?>
                    <li>
                        <!--        --><?php //if(isset($_GET[GlobalConstant::GET_QUOTES])): ?>
                        <?php echo Html::a('Create', ['create', 'Receipt[quotes]' => true], ['class' => '']); ?>
                        <!--    --><?php //elseif(isset($_GET[GlobalConstant::GET_INVOICES])): ?>
                        <!--    --><?php //echo Html::a('Create Invoice', ['create', GlobalConstant::GET_INVOICES => 1], ['class' => 'btn btn-success']); ?>
                        <!--    --><?php //else: ?>
                        <!--    --><?php //echo Html::a('Create Receipt', ['create'], ['class' => 'btn btn-success']); ?>
                        <!--    --><?php //endif; ?>
                    </li>
                <?php endif; ?>
                <li class="<?=$quoteActive?>">
                    <?php echo Html::a('Quote', ['index','Receipt[quotes]'=>'true'], ['class' => '']); ?>
                </li>
                <?php if(Yii::$app->user->identity->getRole() != GlobalConstant::ROLE_CASE_WORKER) {?>
                    <li class="<?=$invoiceActive?>">
                        <?php echo Html::a('Invoices', ['index','Receipt[invoices]'=>'true'], ['class' => '']); ?>
                    </li>
                    <li class="<?=$receiptActive?>">
                        <?php echo Html::a('Receipts', ['index'], ['class' => '']); ?>
                    </li>
                    <?php if((Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN) || (Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_SUPERADMIN) || (Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_GLOBAL_FINANCE)) {?>
                        <li>
                            <?php echo Html::a('Case Type Prices', ['case-type-pricing'], ['class' => '']); ?>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </div>
        <?php
        if(isset($receiptCountArray) && !empty($receiptCountArray)){
            $countReceipt = $receiptCountArray[0];
            if($countReceipt>=Plan::FREE_RECEIPT_LIMIT){ ?>
                <div id="w0" class="alert-info alert fade in">
                    <b>Your subscription has been expired<br/>
                        <br/>
                        Click <a href="<?php echo \yii\helpers\Url::to('@frontendUrl/site/pricing') ?>"> Here </a> to subscribe for month / year
                </div>
            <?php }else{
                echo '<p>'.Html::a('Create Receipt', ['create'], ['class' => 'btn btn-success']).'</p>';
            }
        }
        ?>

        <br>

        <?php if (Yii::$app->user->can(GlobalConstant::ROLE_FINANCE) && $dataProvider->getTotalCount() > 0): ?>
            <span class="reciept-filter">Filters</span>
            <?php 
                $resetUrl = Yii::$app->urlManager->createAbsoluteUrl(['receipt/index']);
                if (isset($_GET['Receipt']['quotes'])) {
                    $resetUrl = $resetUrl . '?Receipt%5Bquotes%5D=true';
                } else if (isset($_GET['Receipt']['invoices'])) {
                    $resetUrl = $resetUrl . '?Receipt%5Binvoices%5D=true';
                }
            ?>
    
            <div class="col-md-12" style="margin: 20px 0px;  display: flex; align-items: flex-end;">
                <div class="col-md-2" style="padding-left: 0 !important;">    
                    <label class="control-label" for="from-date">From Date</label>
                    <?php 
                        $fromDate = '';
                        if (isset($_GET['ReceiptSearch']['from_date'])) {
                            $fromDate = $_GET['ReceiptSearch']['from_date'];
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

                <div class="col-md-2">
                    <label class="control-label" for="to-date">To Date</label>
                    <?php 
                        $toDate = '';
                        if (isset($_GET['ReceiptSearch']['to_date'])) {
                            $toDate = $_GET['ReceiptSearch']['to_date'];
                        }
                        echo DateControl::widget([
                            'name' => 'to-date',
                            'id' => 'to-date',
                            'value' => $toDate,
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
          

                <div class="col-md-1">
                    <div class="fa fa-circle-o-notch fa-spin date-filters-loader" style="display: none;"></div>
                </div>
                <?php if (isset($_GET['Receipt']['invoices']) || isset($_GET['Receipt']['receipt'])): ?>
                <div class="col-md-2">
                    <label class="control-label" for="to-date">Filter By Client</label>
                    <?php 
                        $options = [];
                        foreach($clients as $clientID => $clientName) {
                            if (isset($_GET['ReceiptSearch']['client_id']) && $_GET['ReceiptSearch']['client_id'] == $clientID) {
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
                <?php endif; ?>
            
                <div class="col-md-1">
                <a class="<?php echo !isset($_GET['ReceiptSearch']) ? 'disabled-clear-filter' : '' ?> clear-all-filters" style="margin-left: 10px" title="Reset All Filters" href="<?php echo $resetUrl ?>"><i class="fa fa-undo fa-lg" style="color: #d20511; transform: translate(0px, -5px);"></i></a>
                </div>

                <div class="col-md-1">
                    <div class="fa fa-circle-o-notch fa-spin client-filter-loader" style="display: none;"></div>
                </div>
            </div>
        <?php endif; ?>

        <?php
        $template = '{download-pdf}{email-receipt}';
        if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN ||Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_MANAGER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_FINANCE || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_MANAGER)
        $template = '{update}{delete}{download-pdf}{email-receipt}{govt-fee-attachment}';
    //    elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_WORKER )
    //     $template = '{update}{delete}{download-pdf}{email-receipt}';
        else if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_CASE_MANAGER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_CASE_WORKER||Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_GROUP_MANAGER||Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_ENTITY_MANAGER)
            $template = '{download-pdf}';
        ?>
        <?php Pjax::begin(['id' => 'receipts-pjax']); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions'=> ['class'=> 'table data-table'],
            'options' => [
                'class' => 'grid-view'
            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn',
                    'headerOptions' => ['class' => 'abc']],

                //'receipt_number',
                [
                    'attribute'=>'receipt_number',
                    'label' => $receiptType.' Number',
                    'filterInputOptions' => [
                        'class' => 'form-control border search',
                        'placeholder' => 'Serial Number'
                    ]
                ],
                [
                    'attribute'=>'potential_client_name',
                    'label' => 'Client Name',
                    'filterInputOptions' => [
                        'class' => 'form-control border search',
                        'placeholder' => 'Serial Number'
                    ],
                    'format' => 'raw',
                    'visible' => isset($_GET['Receipt']['quotes']),
                ],
                [
                    'attribute'=>'potential_client_email',
                    'label' => 'Client Email',
                    'filterInputOptions' => [
                        'class' => 'form-control border search',
                        'placeholder' => 'Serial Number'
                    ],
                    'format' => 'raw',
                    'visible' => isset($_GET['Receipt']['quotes']),
                ],
                [
                    'attribute'=>'potential_client_address',
                    'label' => 'Client Address',
                    'filterInputOptions' => [
                        'class' => 'form-control border search',
                        'placeholder' => 'Serial Number'
                    ],
                    'format' => 'raw',
                    'visible' => isset($_GET['Receipt']['quotes']),
                ],
                [
                    'attribute'=>'date',
                    'label' => 'Issue Date',
                    'filterInputOptions' => [
                        'class' => 'form-control border search',
                        'placeholder' => 'Serial Number'
                    ],
                    'format' => 'raw',
                    'visible' => isset($_GET['Receipt']['quotes']),
                ],
                [
                    'attribute'=>'due_date',
                    'label' => 'Expiry Date',
                    'filterInputOptions' => [
                        'class' => 'form-control border search',
                        'placeholder' => 'Serial Number'
                    ],
                    'format' => 'raw',
                    'visible' => isset($_GET['Receipt']['quotes']),
                ],
                /*'cheque_number',*/
                ['attribute'=>'case.applicant.first_name',
                    'filterInputOptions' => [
                        'class' => 'form-control border search',
                        'placeholder' => 'Set Applicant Name'
                    ],
                    'label'=>'Applicant Name',
                    'value' => function ($model) {
                        if($model->case && $model->case->applicant)
                            return $model->case->applicant->first_name.' '.$model->case->applicant->last_name;
                    },
                    'format' => 'raw',
                    'visible' => !isset($_GET['Receipt']['quotes']) 
                ],
                /*'set_client_registration_number',*/
//                ['attribute'=>'set_mobile',
//                    'filterInputOptions' => [
//                        'class' => 'form-control border search',
//                        'placeholder' => 'Set Mobile No.'
//                    ]
//                ],
                ['attribute'=>'client_entity_id',
                    'label'=>'Client Entity',
                    'filterInputOptions' => [
                        'class' => 'form-control border search',
                        'placeholder' => ''
                    ],
                    'value' => function ($model) {
                        if($model->case && $model->case->clientEntity)
                            return $model->case->clientEntity->name;
                    },
                    'format' => 'raw',
                    'visible' => !isset($_GET['Receipt']['quotes']) 
                ],
                ['attribute'=>'case_id',
                    'label'=>'Case No',
                    'filterInputOptions' => [
                        'class' => 'form-control border search',
                        'placeholder' => ''
                    ],
                    'value' => function ($model) {
                        return $model->case->case_number;
                    },
                    'format' => 'raw',
                    'visible' => !isset($_GET['Receipt']['quotes']) 
                ],
                ['attribute'=>'case_type_id',
                    'label'=>'Case Type',
                    'filterInputOptions' => [
                        'class' => 'form-control border search',
                        'placeholder' => ''
                    ],
                    'value' => function ($model) {
                        return $model->case->caseType->name;
                    },
                    'format' => 'raw',
                    'visible' => !isset($_GET['Receipt']['quotes']) 
                ],
                // ['attribute'=>'set_email',
                //     'filterInputOptions' => [
                //         'class' => 'form-control border search',
                //         'placeholder' => 'Set Email'
                //     ]
                // ],

                /* [
                     'attribute'=>'set_client_name',
                     'value'=>function($model){
                         return $model->set_client_name;

                     }
                 ],*/
                ['attribute'=>'date',
                    'filterInputOptions' => [
                        'class' => 'form-control border search',
                        'placeholder' => 'Date'
                    ],
                    'format' => 'raw',
                    'visible' => !isset($_GET['Receipt']['quotes']) 
                ],
                /*'receipt_increment_alphabetic_part',
                'receipt_increment_number_part',*/
                ['attribute'=>'currency.name',
                    'label' => 'Currency',
                    'filterInputOptions' => [
                        'class' => 'form-control border search',
                        'placeholder' => 'Currency'
                    ],
                    'format' => 'raw',
                    'visible' => !isset($_GET['Receipt']['quotes']) 
                ],
                ['attribute'=>'amount',
                    'filterInputOptions' => [
                        'class' => 'form-control border search',
                        'placeholder' => 'Amount'
                    ],
                    'format' => 'raw',
                   
                ],
                // 'actual_amount_received',
                // 'date_received',
                // 'service_id',
                // 'payment_mode',
                // 'drawn_on',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'headerOptions' => ['class' => 'abc'],
                    'template'=>$template,
                    
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return  ButtonDropdown::widget([
                                'label' => 'View',
                                'options'=>[
                                    'class' => 'btn btn-default btn-xs waves-effect dropdown-toggle ',
                                ],
                                'dropdown' => [
                                    'items' => [
                                        ['label' => 'with VAT',
                                            'linkOptions' => ['target' => '_blank', 'data-pjax'=>"0"],
                                            'url' => $url.'&template=1'],
                                        ['label' => 'without VAT',
                                            'linkOptions' => ['target' => '_blank', 'data-pjax'=>"0"],
                                            'url' =>$url.'&template=2'],
                                    ],
                                ],
                            ]);
//                        return   Html::dropDownList('download-pdf-button','',['1'=>'template1','2'=>'template2'], ['prompt' => '--- View ---','class'=>'myselect','id'=>'download-pdf-button' ,'onchange'=>'redirectTemplate("'.$url.'",this.value)']) ;
//                        return Html::a('<span class="fa fa-eye"></span> View', $url, [
//                            'title' => Yii::t('app', 'View'),
//                            'class' => 'class-details btn btn-info btn-xs',
//                            'style'=>'margin-top:4px;',
//                        ]);
                        },
                        'update' => function ($url, $model) {
                            return Html::a('<i class="fa fa-pencil text-success"></i>', $url, [
                                'title' => Yii::t('app', 'Update'),
                                'class'=>'mr-10',
                                'style'=>'margin-top:4px;margin-right:2px;'

                            ]);
                        },
                        'delete' => function ($url, $model) {
                            return Html::a('<i class="fa fa-close text-danger"></i>', $url, [
                                'title' => Yii::t('app', 'Delete'),
                                'class'=>'mr-10',
                                'style'=>'margin-top:4px;margin-right:2px;',
                                'data-method'=>'post',
                                'data-confirm'=>'Are you sure you want to delete this item?'
                            ]);
                        },
                        'download-pdf' => function ($url, $model) {
                            $url = \yii\helpers\Url::to(['/receipt/sample-pdf/', 'id' => $model->id, 'options' => 'download', 'template' => 2]); 
                            return Html::a('<i class="fa fa-file-pdf-o"></i>', $url, [
                                'title' => Yii::t('app', 'Download'),
                                'class' => 'download-pdf-button mr-10',
                                'style' => 'margin-top:4px;margin-right:2px;',
                                'data-pjax' => "0"
                            ]);
                        },
                       
                       
                        'email-receipt' => function ($url, $model) {
                            $url = \yii\helpers\Url::to(['/receipt/sample-pdf/', 'id' => $model->id, 'options'=>'send-email','template' => 2]);
                            // return  ButtonDropdown::widget([
                            //     'label' => 'Email',
                            //     'options'=>['class' => 'btn btn-default btn-xs waves-effect dropdown-toggle'],
                            //     'dropdown' => [
                            //         'items' => [
                            //             ['label' => 'with VAT', 'url' => $url.'&template=1'],
                            //             ['label' => 'without VAT', 'url' =>$url.'&template=2'],
                            //         ],
                            //     ],
                            // ]);
                            return Html::a('<i class="fa fa-envelope"></i>', $url, [
                                'title' => Yii::t('app', 'Email'),
                                'class'=>'mr-10',
                                'style'=>'margin-top:4px;margin-right:2px;',
                                'linkOptions' => ['target' => '_blank', 'data-pjax' => "0"]

                            ]);

//                        return Html::a('<span class="fa fa-envelope"></span> Email Receipt', $url, [
//                            'title' => Yii::t('app', 'Download'),
//                            'class'=>'btn btn-primary btn-xs',
//                            'style'=>'margin-top:4px;'
//
//                        ]);
                        },
                       
                        'govt-fee-attachment' => function ($url, $model) {
                            $receiptType='Receipt';
                                if(isset($_GET['Receipt']['quotes']))
                                { 
                                $receiptType='Quote';
                                }
                                elseif(isset($_GET['Receipt']['invoices']))
                                { 
                                    $receiptType='Invoice';
                                }
                                else
                                {  
                                    $receiptType='Receipt';
                                }

                            $url = \yii\helpers\Url::to(['/receipt/govt-fee-attachment/', 'id' => $model->id,'receiptType'=> $receiptType]); 
                            return Html::a('<i class="fa fa-file-text-o"></i>', $url, [
                                'title' => Yii::t('app', 'govt-fee-attachment'),
                                'class'=>'mr-10',
                                'style'=>'margin-top:4px;margin-right:2px;'

                            ]);
                        },

                    ],
                ],
            ],
        ]); ?>
        <?php Pjax::end() ?>
    </div>
</div>

</div>





<?php
//Fixing issue of dropdowns not closing after opening
$this->registerJs(
    <<<JS
       $('html').on('click', function (e) {
        if (!$('.dropdown-animating').is(e.target)
            && $('.dropdown-animating').has(e.target).length === 0
            && $('.open').has(e.target).length === 0
        ) {
            $('.dropdown-animating').removeClass('open');
        }
    });
JS
);
?>


<script>
    function redirectTemplate(url,value) {
      window.location.href=url+'&template='+value;
    }

    $(document).ready(function() {
        function checkClearAllButton() {
            if ($('#from-date').val() || $('#to-date').val() || $('#client-filter').val()) {
                $('.clear-all-filters').removeClass('disabled-clear-filter')
            } else {
                $('.clear-all-filters').addClass('disabled-clear-filter')
            }
        }

        var url = window.location.href;
        function changeDate() {
            if ($('#to-date').val() && ($('#from-date').val() > $('#to-date').val())) {
                toastr.warning('From Date cannot be greater than To Date');
                return;
            }

            $('.date-filters-loader').attr('style', 'display: inline-block;')

            //removing search parameters if both dates are empty
            if (!($('#to-date').val() || ($('#from-date').val()))) {
                var regexPattern = /&?\bReceiptSearch%5Bfrom_date%5D=\d{0,4}-?\d{0,2}-?\d{0,2}&\bReceiptSearch%5Bto_date%5D=\d{0,4}-?\d{0,2}-?\d{0,2}/;
                url = url.replace(regexPattern, "");
            } else if (url.indexOf('ReceiptSearch%5Bfrom_date%5D=') != -1 || url.indexOf('ReceiptSearch%5Bto_date%5D=') != -1) {
                //if one date is set, replacing and re-adding the query parameter
                var regexPattern = /&?\bReceiptSearch%5Bfrom_date%5D=\d{0,4}-?\d{0,2}-?\d{0,2}&\bReceiptSearch%5Bto_date%5D=\d{0,4}-?\d{0,2}-?\d{0,2}/;
                url = url.replace(regexPattern, "");
                if (url.endsWith("index")) {
                    url += '?ReceiptSearch%5Bfrom_date%5D=' + $('#from-date').val() + '&ReceiptSearch%5Bto_date%5D=' + $('#to-date').val();
                } else if (url.endsWith("index?")) {
                    url += 'ReceiptSearch%5Bfrom_date%5D=' + $('#from-date').val() + '&ReceiptSearch%5Bto_date%5D=' + $('#to-date').val();
                } else {
                    url += '&ReceiptSearch%5Bfrom_date%5D=' + $('#from-date').val() + '&ReceiptSearch%5Bto_date%5D=' + $('#to-date').val();
                }
            } else {
                //adding both dates from scratch
                if (url.endsWith("index")) {
                    url += '?ReceiptSearch%5Bfrom_date%5D=' + $('#from-date').val() + '&ReceiptSearch%5Bto_date%5D=' + $('#to-date').val();
                } else if (url.endsWith("index?")) {
                    url += 'ReceiptSearch%5Bfrom_date%5D=' + $('#from-date').val() + '&ReceiptSearch%5Bto_date%5D=' + $('#to-date').val();
                } else {
                    url += '&ReceiptSearch%5Bfrom_date%5D=' + $('#from-date').val() + '&ReceiptSearch%5Bto_date%5D=' + $('#to-date').val();
                }
            }

            $.ajax({
                type: 'GET',
                url: url,
                success: function() {
                    $.pjax.reload({container: '#receipts-pjax', timeout: 3000, url: url, async: false});
                    $('.date-filters-loader').attr('style', 'display: none;')
                    checkClearAllButton()
                }
            })
        }

        //generic function for searching based on dropdown
        function clientFilter() {
            $('.' + $(this).attr('id') + '-loader').attr('style', 'display: inline-block;');
            var attribute = '';
            var regexPattern = '';
            if ($(this).attr('id') == 'client-filter') {
                attribute = 'client_id';
                regexPattern = /&?\bReceiptSearch%5Bclient_id%5D=\d+/;
            }

            //same logic as changeDate function
            if ($(this).val() == "" || $(this).val() == null) {
                url = url.replace(regexPattern, '');
            } else if (url.indexOf('ReceiptSearch%5B' + attribute + '%5D=') != -1) {
                url = url.replace(regexPattern, '');
                if (url.endsWith('index')) {
                    url += '?ReceiptSearch%5B' + attribute + '%5D=' + $(this).val();
                } else if (url.endsWith('index?')) {
                    url += 'ReceiptSearch%5B' + attribute + '%5D=' + $(this).val();
                } else {
                    url += '&ReceiptSearch%5B' + attribute + '%5D=' + $(this).val();
                }
            } else {
                if (url.endsWith('index')) {
                    url += '?ReceiptSearch%5B' + attribute + '%5D=' + $(this).val();
                } else if (url.endsWith('index?')) {
                    url += 'ReceiptSearch%5B' + attribute + '%5D=' + $(this).val();
                } else {
                    url += '&ReceiptSearch%5B' + attribute + '%5D=' + $(this).val();
                }
            }

            var id = $(this).attr('id')

            $.ajax({
                type: 'GET',
                url: url,
                success: function() {
                    $.pjax.reload({container: '#receipts-pjax', timeout: false, url: url, async: false});
                    $('.' + id + '-loader').attr('style', 'display: none;');
                    checkClearAllButton()
                }
            })
        }

        //attaching listeners
        $('#from-date, #to-date').on('change', changeDate);
        $('#client-filter').on('change', clientFilter);
    })
            $(document).on('click', '.download-pdf-button', function(e) {
            e.preventDefault(); 
            let url = $(this).attr('href'); 
            window.open(url, '_blank'); 
        });
</script>
<style>
    .table-responsive table,.table-responsive{
        overflow:visible;
    }
</style>