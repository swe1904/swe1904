<?php

use backend\models\CaseType;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use backend\models\Applicant;
use himiklab\sortablegrid\SortableGridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\CaseTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Case Types');
$this->params['breadcrumbs'][] = ['label' => 'Cases / ' . $this->title];
?>

<?php
$data= Applicant::instance()->attributeLabels(); 
unset($data["id"]);
unset($data["client_id"]);

//prefilling static fields
$model_case->applicant_field_key = ['date_1674644208007', 'text_1674644226784', 'text_1674644240628', 'text_1674644253091', 'text_1674644269635'];
?>
<!--WorkOnProgress-->


  <div class="row">

    <div class="col-md-12">
            <div class="panel panel-default card-view border-panel panel-refresh">
                <div class="refresh-container">
                    <div class="la-anim-1"></div>
                </div>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark" style="color: #ffffff !important;">Create Case Type</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body" >
                    <?php $form = ActiveForm::begin( [
                    
                            'options' => [
                                'class' => 'form-group',
                                'id'=>'case-type-form',
                            ],
                        ],
                        ); ?>
                            <div class="col-md-12 pl-0">
                                <div class="col-md-6">
                                    <?= $form->field($model, 'name',
                                        ['template' =>'
                                        <div class="form-group">
                                        <label class="control-label"> Case Type </label>
                                        <div class="form-line border">{input}</div>
                                        </div>'
                                        ])->textInput(['maxlength' => true, 'placeholder' => 'Create Case Type', 'label' => 'Case Type', 'required' => true])
                                        ?>
                                </div>
                                
                            <div class="col-md-2" >
                                <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'btn btn-sm btn-rounded btn-success mt-25']) ?>
                            </div>
                            </div>
                            <!-- <div class="col-md-12 pl-0">
                            <div class="col-md-6">
                                <?= $form->field($model_case, 'applicant_field_key')->label('Required fields')->widget(Select2::className(), [
                                    'data' => $data,
                                    'model' => $model,
                                    // 'attribute' => 'categories',
                                
                                    'language' => 'en',
                                
                                    'options' => ['placeholder' => 'Select required fields','class'=>'multiple','style'=>"height:250px",  'id'=> 'multiselect', 'onchange'=>'dropDownChange()'],
                                
                                    'pluginOptions' => [
                                            'allowClear' => true,
                                            'multiple' => true,
                                            'closeOnSelect' => false,
                                            'label' => false,
                                        ],
                                    
                                        ])
                                        ?>
                                    
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model_case, 'applicant_field_value')->label('Optional fields')->widget(Select2::className(), [
                                    'data' => $data,
                                    'model' => $model,
                                
                                    // 'attribute' => 'categories',
                                    'language' => 'en',
                                
                                    'options' => ['placeholder' => 'Select non-required fields','class'=>'multiple','style'=>"height:250px", 'id'=> 'multiselect2', 'onchange'=>'dropDownChange()'],
                                
                                    'pluginOptions' => [
                                            'allowClear' => true,
                                            'multiple' => true,
                                            'closeOnSelect' => false,
                                            'label' => false,
                                        ],
                                    
                                        ])
                                        ?>
                                    
                            </div>
                            </div>
                            <div class = "col-md-12 pl-2">
                                ** If no field is selected in any dropdown then all the fields will be optional    
                            </div> -->

                   

                    
                            <?php ActiveForm::end(); ?>
                        </div>

                </div>
            </div>
    </div>



<!--DisplayCases-->
<div class="col-md-12">
        <div class="panel panel-default card-view panel panel-refresh mt-20">
            <div class="refresh-container">
                <div class="la-anim-1"></div>
            </div>
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark"style="color: #ffffff !important;">Case Type</h6>
                </div>
                <!-- <div class="pull-right">
                    <a href="#" class="pull-left inline-block refresh mr-15">
                        <i class="zmdi zmdi-replay"></i>
                    </a>
                    <a href="#" class="pull-left inline-block full-screen mr-15">
                        <i class="zmdi zmdi-fullscreen"></i>
                    </a>
                    <div class="pull-left inline-block dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="zmdi zmdi-more-vert"></i></a>
                        <ul class="dropdown-menu bullet dropdown-menu-right" role="menu">
                            <li role="presentation"><a href="javascript:void(0)" role="menuitem"><i class="icon wb-reply" aria-hidden="true"></i>option 1</a></li>
                            <li role="presentation"><a href="javascript:void(0)" role="menuitem"><i class="icon wb-share" aria-hidden="true"></i>option 2</a></li>
                            <li role="presentation"><a href="javascript:void(0)" role="menuitem"><i class="icon wb-trash" aria-hidden="true"></i>option 3</a></li>
                        </ul>
                    </div>
                </div> -->
                <div class="clearfix"></div>
            </div>
<!--            <div class="panel-wrapper collapse in">-->
<!--                <div class="panel-body pa-0 row">-->
<!--                    <div class="table-wrap">-->
<!--                        <div class="table-responsive">-->
                            <?= 
                                SortableGridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'tableOptions'=>['class'=>'table data-table'],
//                                'layout'=>"<div class='pull-right'>{summary}</div>\n{items}",
                                // 'layout'=>"<div class='pull-right'></div>\n{items}",
                                'columns' => [
                                    // ['class' => 'yii\grid\SerialColumn',
                                    //     'headerOptions' => ['style' => 'width: 10%']
                                    // ],

                                    /*[ 'attribute' =>  'id',
                                        'filterInputOptions' => [
                                            'class' => 'form-control search',
                                            'placeholder' => (new CaseType)->getAttributeLabel('id'),
                                        ]],*/

                                    [ 'attribute' =>  'name',
                                        'filterInputOptions' => [
                                            'style' => 'border-left: 1px solid #eee;border-right: 1px solid #eee;border-top: 1px solid #eee;',
                                            'class' => 'form-control search',
                                            'placeholder' => (new CaseType)->getAttributeLabel('search'),
                                        ]],


                                    ['class' => 'yii\grid\ActionColumn',
                                        'headerOptions' => ['class' => 'abc'],
                                        'buttons'=>[
                                            'delete' => function($url, $model){
                                                $url = Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/delete', 'id' => $model->id]);
                                                return '<a class="mr-25" href="'.$url.'" data-method="post" data-confirm = "'.Yii::t('yii', 'Are you sure you want to delete this item?').'",  title="Delete"><i class="fa fa-close text-danger"></i></a>';
                                            },
                                            'update' => function($url, $model){
                                                $url = Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/update', 'id' => $model->id, 'page' => isset($_GET['page'])?$_GET['page']:1]);
                                                // return'<a class="mr-25" href="javascript:"  onclick="updateCaseType(' . $model->id . ')" title="Update"><i class="fa fa-pencil text-success text-inverse m-r-10"></i></a>';
                                                return'<a class="mr-25" href="'.$url.'" data-method="post" title="Update"><i class="fa fa-pencil text-success text-inverse m-r-10"></i></a>';
                                            },
                                            'steps'=>function($url, $model){
                                                $url=Yii::$app->urlManager->createUrl(['/case-type-step/index','CaseTypeStepSearch[case_type_id]'=> $model->id]);
                                                return'<a class="mr-25" href="'.$url.'" title="Show Steps"><i class="fa fa-list text-primary m-r-10"></i></a>';
                                            },
                                            'create'=>function($url, $model){
                                                $url=Yii::$app->urlManager->createUrl(['/applicant/create', 'id' => $model->id]);
                                                return'<a class="mr-25" href="'.$url.'" title="Create"><i class="fa fa-plus m-r-10" style="color:orange;"></i></a>';
                                            },
                                            'queries' => function($url, $model) {
                                                $url = Yii::$app->urlManager->createUrl(['/knowledge-module/view', 'caseTypeID' => $model->id]);
                                                return'<a class="mr-25" href="'.$url.'" title="Queries"><i class="fa fa-question m-r-10" style="color:purple;"></i></a>';
                                            }

                                        ],
                                        'template' => ' {steps} {update} {delete} {queries}',
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
