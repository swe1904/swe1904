<?php

use backend\models\Receipt;
use yii\helpers\Html;
use yii\grid\GridView; 

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\CaseTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$receiptId = $_GET['id'];
$receipt = Receipt::findOne($receiptId);
$receiptType = 'Receipt';
if(isset($_GET['receiptType']))
{
$receiptType = $_GET['receiptType'];
}
else{

$receiptType = 'Receipt';
}



$this->title = Yii::t('backend', 'Government Fee Details ');
$this->params['breadcrumbs'][] = ['label' => 'Government Fees / '.$receiptId .'/' . $this->title];
$recei
?>


<!--WorkOnProgress-->


 



<!--DisplayCases-->
<div class="col-md-12 ">
  <h3 style="margin-bottom: 20px;"><?= $receiptType . ' ' . $receipt->receipt_number; ?></h3>
        <div class="panel panel-default card-view panel panel-refresh mt-20">
            <div class="refresh-container">
                <div class="la-anim-1"></div>
            </div>
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark"style="color: #ffffff !important;">Government Fees</h6>
                </div>
               
                <div class="clearfix"></div>
            </div>

                            <?= 
                                GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'tableOptions'=>['class'=>'table data-table'],
                               
                                'columns' => [
                                   

                                   'description',
                                       [
                                          'attribute' => 'price',
                                          'format' => ['decimal', 2],
                                          
                                       ],
                                       [
                                        'attribute' => 'Amount',
                                        'label' => 'Amount',
                                        'value' => function ($model) {
                                            return number_format($model->quantity * $model->price, 2); // Calculate and format Amount
                                        },
                                        'format' => 'raw', // Ensure the formatted number is displayed as is
                                    ],
                                    ['class' => 'yii\grid\ActionColumn',
                                        'headerOptions' => ['class' => 'abc'],
                                        'buttons'=>[
                                            
                                            'attach-docs' => function ($url, $model) {
                                   $receiptType = 'Receipt';
                                   if(isset($_GET['receiptType']))
                                   {
                                    $receiptType = $_GET['receiptType'];
                                   }
                                   else{
                                    $receiptType = 'Receipt';
                                   }
                                   $url = Yii::$app->urlManager->createUrl(['receipt/attach-documents', 'id' => $model->id, 'receiptType'=>$receiptType ]);
                                    return'<a class="mr-15" href="'.$url.'" title="Attach Documents"><i class="fa fa-file-text-o"></i></a>';
                            
                                 },
                                           
                                        ],
                                        'template' => '{attach-docs}',
                                        'header' => '<strong>ACTION</strong>'
                                    ],
                                ],
                                
                            ]); ?>
                            
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
        </div>
    </div>
    </div>

    <style>
        .select2-selection{
            height:auto;
        }
        .select2-selection--multiple{
            height:auto;
        }
        .select2-selection__rendered{
            height:auto;
        }
        .data-table tbody tr {
            cursor: grab;
        }

        .data-table tbody tr.ui-sortable-helper {
            cursor: grabbing !important;
        }
    </style>
    <script>
        $(document).ready(function(){
            dropDownChange();

        })
        function dropDownChange()
        {
            let list1 = $('#multiselect').val();
            let list2 = $('#multiselect2').val();
            $('#multiselect2').children().each(function () {
                                
                                        ($(this).attr("disabled",false));
            })
            $('#multiselect2').children().each(function () {
                                if(list1.includes($(this).attr('value')))
                                        ($(this).attr("disabled","disabled"));
            })
            $('#multiselect').children().each(function () {
                                
                                        ($(this).attr("disabled",false));
            })
            $('#multiselect').children().each(function () {
                                if(list2.includes($(this).attr('value')))
                                        ($(this).attr("disabled","disabled"));
            })
        }


    function updateCaseType(id) {
            $('.customForm-container').html('')
        $.ajax({
            'type': 'GET',
            'url': '<?php echo Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/update'])?>?id=' + id ,
            success: function(data) {
                $('body').append(data);
                $('.customForm-container').css('right','0');
            }
        });
    }

    $('#open-case-type-form').click(function (e) { 
        createCaseType()
    
    });
    function createCaseType() {
        $('.customForm-container').html('') 
        $.ajax({
            'type': 'GET',
            'url': '<?php echo Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/create'])?> ',
            success: function(data) {
                $('body').append(data);
                $('.customForm-container').css('right','0');
            }
        });
    }


       
    </script>
<!--/DisplayCases-->

<!--/WorkOnProgress-->
