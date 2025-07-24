<?php 
// use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\data\ArrayDataProvider;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use app\components\GlobalConstant;


$this->title = 'Case Type Prices';
$this->params['breadcrumbs'][] = $this->title;

?>
<style>
    .select2-selection__arrow{
        display: none !important;
    }
    .select2-selection__rendered{
        padding-top: 6px !important;
    }
    .kv-expanded-row{
        width: 100% !important;
    }
    .custom-expanded-row td {
        width: 100% !important;
        display: block;
        padding: 0;
    }

    .custom-expanded-row .kv-expanded-row {
        display: block;
        width: 100%;
    }
</style>
<?php //Pjax::begin(['id' => 'case-type-pricing-pjax']); ?>
<!-- <div class="row"> -->
<?php if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_GLOBAL_FINANCE) {?>
<div class="col-md-12">
        <div class="panel panel-default card-view border-panel panel-refresh">
            <div class="refresh-container">
                <div class="la-anim-1"></div>
            </div>
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark" style="color: #ffffff !important;">Create Case Type Price</h6>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-wrapper collapse in">
                <div class="panel-body" >
                <?php $form = ActiveForm::begin( [
                
                        'options' => [
                            'class' => 'form-group',
                            'id'=>'case-type-pricing-form',
                        ],
                    ],
                    ); ?>
                        <div class="col-md-12 pl-0">
                            <div class="col-md-3">
                                  <?= $form->field($model, 'client_id')->label('Client')->widget(Select2::class, [
                                              'data' => $clients,
                                              'language' => 'en',
                                              'options' => ['placeholder' => 'Select client',
                                                          'class'=>'multiple',
                                                          'style'=>"height:250px",
                                                          'onchange'=>'clientDropDownChanges()'
                                                           ],
                                                        'pluginOptions' => [
                                                                'allowClear' => true,
                                                                // 'multiple' => true,
                                                                // 'closeOnSelect' => false,
                                                                'label' => false,
                                                            ],
                                                  ])
                                                  ?>
                              </div>

                              <div class="col-md-3">
                                  <?= $form->field($model, 'client_entity_id')->label('Client Entity')->widget(Select2::class, [
                                              'data' => [],
                                              //'model' => $model,
                                              // 'attribute' => 'categories',

                                              'language' => 'en',

                                              'options' => ['placeholder' => 'Select client entity',
                                                          'class'=>'multiple',
                                                          'style'=>"height:250px",
                                                         
                                                      ],
                                              'pluginOptions' => [
                                                      'allowClear' => true,
                                                      // 'multiple' => true,
                                                      // 'closeOnSelect' => false,
                                                      'label' => false,
                                                  ],
                                                

                                                  ])
                                                  ?>
                                                  <div class="fa fa-circle-o-notch fa-spin" id="loading-div-client_entity_id" style="display:none;" ></div>
                              </div>

                              <div class="col-md-3">
                                  <?= $form->field($model, 'currency_id')->label('Currency')->widget(Select2::className(), [
                                              'data' => $currency,
                                              'language' => 'en',

                                              'options' => ['placeholder' => 'Select currency',
                                                          'class'=>'multiple',
                                                          'style'=>"height:250px",
                                                         
                                                      ],
                                              'pluginOptions' => [
                                                      'allowClear' => true,
                                                      // 'multiple' => true,
                                                      // 'closeOnSelect' => false,
                                                      'label' => false,
                                                  ],
                                               
                                                  ])
                                                  ?>
                              </div>

                              <div class="col-md-3">
                                  <?= $form->field($model, 'case_type_id')->label('Case Type')->widget(Select2::className(), [
                                              'data' => $caseTypes,
                                              //'model' => $model,
                                              // 'attribute' => 'categories',

                                              'language' => 'en',

                                              'options' => ['placeholder' => 'Select case type',
                                                          'class'=>'multiple',
                                                          'style'=>"height:250px",
                                                          // 'disabled' => 'disabled',
                                                          // 'id'=> 'multiselect',
                                                          // 'onchange'=>'clientDropDownChange()'
                                                          // 'name'=> 'Cases["client_id"]',
                                                          // 'required' => true, // Make the field required
                                                      ],
                                              'pluginOptions' => [
                                                      'allowClear' => true,
                                                      // 'multiple' => true,
                                                      // 'closeOnSelect' => false,
                                                      'label' => false,
                                                  ],
                                                  // 'pluginEvents' => [
                                                  //                     'change' => 'function() { clientDropDownChange(); }',
                                                  //                 ],

                                                  ])
                                                  ?>
                              </div>
                            </div>
                            <div class="col-md-12 pl-0">
                              <div class="col-md-3">
                                  <?= $form->field($model, 'organisation_id')->label('Northman Billing Office')->widget(Select2::className(), [
                                              'data' => [],
                                              //'model' => $model,
                                              // 'attribute' => 'categories',

                                              'language' => 'en',

                                              'options' => ['placeholder' => 'Select northman billing office',
                                                          'class'=>'multiple',
                                                          'style'=>"height:250px",
                                                          // 'disabled' => 'disabled',
                                                          // 'id'=> 'multiselect',
                                                          // 'onchange'=>'clientDropDownChange()'
                                                          // 'name'=> 'Cases["client_id"]',
                                                          // 'required' => true, // Make the field required
                                                      ],
                                              'pluginOptions' => [
                                                      'allowClear' => true,
                                                      // 'multiple' => true,
                                                      // 'closeOnSelect' => false,
                                                      'label' => false,
                                                  ],
                                                  // 'pluginEvents' => [
                                                  //                     'change' => 'function() { clientDropDownChange(); }',
                                                  //                 ],

                                                  ])
                                                      ?>
                                                      <div class="fa fa-circle-o-notch fa-spin" id="loading-div-organisation_id" style="display:none;" ></div>
                                </div>
                                
                                <div class="col-md-2" >
                                    <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'btn btn-sm btn-rounded btn-success mt-25']) ?>
                                </div>
                            </div>
                        
                        </div>
                        <?php ActiveForm::end(); ?>
            </div>
        </div>
        <?php }?>
<!-- </div> -->
<div class="case-type-pricing mt-20">
  <!-- Button to open the modal -->
<!-- <button type="button" class="btn btn-primary" onclick = "openModal();">
    Open Modal
</button> -->
    

    <?= GridView::widget([
        'filterOnFocusOut' => false,
        'layout' => "\n{summary}\n{items}\n{pager}",
        'dataProvider' => $dataProvider,
        'responsive' => true,
        'responsiveWrap' => false,
        'bordered' => false,
        'options' => [
            'class' => 'table-responsive'
        ],
        // 'tableOptions' => ['class' => 'table'],
        'tableOptions'=>['class'=> 'table data-table '],
        'tableOptions' => [
        'class' => 'table kv-grid-table',
        'style' => 'width: 100%;', // Ensure the table takes the full width
    ],
        'columns' => [
            //****** ExpangRow's detail/detailUrl can be used with the actionServices fro rendering expandrow. The UI needs to be fixed before using the expandrow
            
            // [
            //     'class' => 'kartik\grid\ExpandRowColumn',
            //     'width' => '5%',
            //     'value' => function ($model, $key, $index, $column) {
            //         return GridView::ROW_COLLAPSED;
            //     },
            //     'detailUrl' => Url::to(['services','params'=>Yii::$app->request->queryParams]),
            //     // 'detail' => function ($model, $key, $index, $column) {
            //     //     return $this->renderPartial('_services',['id'=> $model->id,'params'=>$_GET['params']]);
            //     // },

            //     'headerOptions' => ['class' => 'kartik-sheet-style'],
            //     'expandOneOnly' => true,
            //     'enableRowClick' => false,
            //     'expandIcon' => '<span class="fa fa-caret-right fa-lg"></span>',
            //     'collapseIcon' => '<span class="fa fa-caret-down fa-lg"></span>',
            //     'mergeHeader' => false,
            //     'detailOptions' => [
            //         'class' => 'kv-expanded-row', // Apply custom class
            //     ],
            // ],

//            'client_id',
//            'client_entity_id',
//            'currency_id',
//            'case_type_id',
//            'organisation_id',

            [
              'attribute' => 'client_id', 
              'label' => 'Client',
              'value' => function ($model) {
                  if (isset($model->client)) {
                      return $model->client->client_name;
                  }
              }
            ],
            [
              'attribute' => 'client_entity_id', 
              'label' => 'Client Entity',
              'value' => function ($model) {
                  if (isset($model->clientEntity)) {
                      return $model->clientEntity->name;
                  }
              }
            ],
            [
              'attribute' => 'currency_id', 
              'label' => 'Currency',
              'value' => function ($model) {
                  if (isset($model->currency)) {
                      return $model->currency->name.' - '.$model->currency->iso;
                  }
              }
            ],
            [
              'attribute' => 'case_type_id', 
              'label' => 'Case Type',
              'value' => function ($model) {
                  if (isset($model->caseType)) {
                      return $model->caseType->name;
                  }
              }
            ],
            [
              'attribute' => 'organisation_id', 
              'label' => 'Northman Entity',
              'value' => function ($model) {
                  if (isset($model->organisation)) {
                      return $model->organisation->name;
                  }
              }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                // 'contentOptions' => ['style' => 'min-width:120px'],
                'template' => (Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN || 
                Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_GLOBAL_FINANCE) 
               ? '{add-service}{delete}' 
               : '{add-service}',
    
                'buttons' => [

                    'add-service' => function($url, $model){
                            // $url = Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/casetypepricing-delete', 'id' => $model->id]);
                            return '<a class="mr-25" href="javascript:void(0);" title="'.(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN ? 'View/Add service' : 'View service').'" onclick="openAddServiceModal('.$model->id.')"><i class="fa fa-gear" style="color: orange;"></i></a>';
                        },
                    'delete' => function($url, $model){
                            $url = Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/casetypepricing-delete', 'id' => $model->id]);
                            return '<a class="mr-25" href="'.$url.'" data-method="post" data-confirm = "'.Yii::t('yii', 'Are you sure you want to delete this item?').'",  title="Delete"><i class="fa fa-trash text-danger"></i></a>';
                        },
    
                ],               
            ],
//            [ 'attribute' =>  'name',
//              'filterInputOptions' => [
//                'style' => 'border-left: 1px solid #eee;border-right: 1px solid #eee;border-top: 1px solid #eee;',
//                'class' => 'form-control search',
//              ],
//              'headerOptions' => ['style' => 'width: 15%']
//            ],
//
//            [
//              'attribute' => 'price',
//              'format' => 'raw',
//              'label' => 'Price (in SAR)',
//              'value' => function($model) {
//                return '<input type="number" class="form-control case-type-price" style="width: 100%" value='.$model->price.' data-id='.$model->id.'>';
//              },
//              'headerOptions' => ['style' => 'width: 5%']
//            ],
//
//						[
//							'format' => 'raw',
//							'label' => null,
//							'value' => function($model) {
//								return '<div class="btn-case-type-price" style="border-radius: 10px; cursor: pointer; height: 28px; background-color: #22af47; aspect-ratio: 1/1; display: flex; justify-content: center; align-items: center; z-index: 2;" data-id="'.$model->id.'"><i class="fa fa-check" style="color: white;"></i>';
//							},
//              'headerOptions' => ['style' => 'width: 10%']
//						]
        ],
    ]); ?>
 <?//php Pjax::end() ?>
</div>

 <!-- Modal for adding services -->

<div class="modal fade" id="add_services" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add service(s)</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
<!--                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                
            </div>
        </div>

    </div>
</div>
<!-- /.modal end-->
<?php
Modal::begin([
    'header' => Yii::t('backend', 'Delete Confirmation'),
    'id' => 'delete-box',
    'size' => 'model-lg',
    ]);
    ?>
    <div class="container-fluid " style="margin-bottom:-20px">
    <p class="">Are you sure you want to delete this item?</p>

    <div class="modal-footer mt-3" style="float: right;">
        <?php echo Html::button(Yii::t('backend', 'Yes →'), ['class' => 'btn btn-close waves-effect', 'onclick' => 'deleteConfirmed()']) ?>
        <?php echo Html::button(Yii::t('backend', 'No →'), ['class' => 'btn btn-close waves-effect', 'onclick' => "$('#delete-box').modal('hide');"]) ?>
    </div>
    </div>

<?php
Modal::end();
?>
    <script>
        var deleteId;
        var itemType;
        var pjaxContainerId;
      function openModal(){
        // console.log("Clicked");
        //   $.pjax.reload({container: '#case-type-pricing-pjax'});
        // $('#case-type-pricing-form')[0].reset();
        data = {caseTypePricingId: 24};
        $.ajax({
            type: 'POST',
            url: '<?php echo \yii\helpers\Url::to(['add-service-template']); ?>',
            data: data,
            success: function(data) {
                $("#add_services").find(".modal-body").html(data.html);
                $("#add_services").modal('show');
            },

        });
        // return false;
        // // $("#add_services").modal('show');

      }
      function deleteClicked(item,id, pjaxId=null)
    {
        itemType = item;
        deleteId = id;
        pjaxContainerId = pjaxId;
    }

    function deleteConfirmed()
    {
        $('#delete-box').modal('hide');
        if(itemType == 'service')
        {
            $.ajax({
                    url: '<?= Url::to(['service-delete'])?>',
                    method: "POST",
                    data: {id : deleteId}, 
                        
                    success: function(response) {
                    data = JSON.parse(response);
                    console.log(data);
                    
                        if (data.code === 1) {
                            toastr.success(data.message)
                            } else {
                            toastr.error(data.message)
                            }
                    //    $.pjax.reload({container: "case-type-pricing-pjax"});
                    
                    }
                })
        
        }
        // else if(itemType == 'service')
      }

    // function reloadPjaxContainer() {
    //    // $.pjax.reload({container: '#case-type-pricing-pjax'});
    // }

    function clientDropDownChange() {

            var clientId = $('#casetypepricing-client_id').val();

            enableLoading('client_entity_id');
            enableLoading('organisation_id');

            $.ajax({
                url: '../helper/get-client-entities',
                data: "clientId="+clientId,
                type: 'GET',
                dataType: 'json',
                success: function(data, textStatus) {
                    console.log("Client Enttities : ",data);
                    
                    if(data)
                    {
                           
                        var jsondata = JSON.parse(JSON.stringify(data));
                       
                        var keys= Object.keys(jsondata);
                        
                        $('#casetypepricing-client_entity_id').append(`<option value="" selected disabled>Select Client Entity</option>`);
                        keys.forEach((key)=>{
                            $('#casetypepricing-client_entity_id').append(`<option value="${key}">
                                            ${jsondata[key]}</option>`); 
                        });
                    }
                    disableLoading('client_entity_id');
                },
        });

        $.ajax({
                url: '../helper/get-client-orgs',
                data: "clientId="+clientId,
                type: 'GET',
                dataType: 'json',
                success: function(data, textStatus) {
                    console.log("Client Enttities : ",data);
                    
                    if(data)
                    {
                           
                        var jsondata = JSON.parse(JSON.stringify(data));
                       
                        var keys= Object.keys(jsondata);
                        
                        $('#casetypepricing-organisation_id').append(`<option value="" selected disabled>Select Organisation</option>`);
                        keys.forEach((key)=>{
                            $('#casetypepricing-organisation_id').append(`<option value="${key}">
                                            ${jsondata[key]}</option>`); 
                        });
                    }
                    disableLoading('organisation_id');
                },
        });
    }
    //   function updateCaseTypePrice() {
    // 		var caseTypeID = $(this).attr('data-id')
    //     var price = $('input[data-id=' + caseTypeID + ']').val()

    //     if (!price) {
    //       price = 0;
    //     }

    //     $.ajax({
    //       type: 'POST',
    //       url: 'update-case-type-price',
    //       data: {
    //         'case_type_id': caseTypeID,
    //         'case_type_price': price
    //       },
    //       success: function(response) {
    //         var data = JSON.parse(response)
    //         if (data.code === 1) {
    //           toastr.success(data.message)
    //         } else {
    //           toastr.error(data.message)
    //         }
    //       }
    //     })

    //   }


      //$('.case-type-price').on('change', updateCaseTypePrice)
      // $('.btn-case-type-price').on('click', updateCaseTypePrice)

      function enableLoading(inputType){
        $('#casetypepricing-'+inputType).html("");
        $('#casetypepricing-'+inputType).prop('disabled', true);
        $('#loading-div-'+inputType).show();
      } 
      function disableLoading(inputType){
          $('#casetypepricing-'+inputType).prop('disabled', false);
          $('#loading-div-'+inputType).hide();
      }


      $('#case-type-pricing-form').on('beforeSubmit', function (event) {
        // Prevent the default form submission
        event.preventDefault();

        // Serialize the form data
            var formData = $(this).serialize();

        // Log the serialized form data
        // console.log('Serialized Form Data:', formData);

        $.ajax({
          type: 'POST',
          url: 'add-case-type-pricing',
          data: formData,
          success: function(response) {
           
            var data = JSON.parse(response);
           
            if (data.code === 1) {
              toastr.success(data.message+". Add services for the Case Type Pricing.");
                openAddServiceModal(data.caseTypePricingId)
              
            } else {
            //   console.log("Message : ",data.message);
                if (data.message && data.message.indexOf('already been taken') !== -1) 
                    toastr.error("Entry already exists");
                else
                    toastr.error(data.message);
          }
        }
        })
        return false;
      });

      function openAddServiceModal(id)
      {
        data = {caseTypePricingId: id};
        
        $.ajax({
                type: 'POST',
                url: '<?php echo \yii\helpers\Url::to(['add-service-template']); ?>',
                data: data,
                success: function(data) {
                    
                    $("#add_services").find(".modal-body").html(data.html);
                    $("#add_services").modal('show');

                    //$('#case-type-pricing-form')[0].reset();
                },

            });
      }

      function clientDropDownChanges() {
    var clientId = $('#casetypepricing-client_id').val(); // Get selected client ID

    alert("Client ID selected: " + clientId); // Check if this alert appears

    if (!clientId) {
        return; // Stop execution if no client is selected
    }

    // AJAX request to fetch Client Entities & Organizations
    $.ajax({
        url: '<?= Yii::$app->urlManager->createUrl(["receipt/get-client-data"]) ?>',
        type: 'GET',
        data: { clientId: clientId },
        dataType: 'json',
        success: function(response) {
            console.log("Received Data:", response); // Debugging: Check received data

            // Populate Client Entities Dropdown
            $('#casetypepricing-client_entity_id').empty().append('<option value="">Select Client Entity</option>');
            $.each(response.clientEntities, function(index, entity) {
                $('#casetypepricing-client_entity_id').append('<option value="' + entity.id + '">' + entity.name + '</option>');
            });

            // Populate Organizations Dropdown
            $('#casetypepricing-organisation_id').empty().append('<option value="">Select Organization</option>');
            $.each(response.organizations, function(index, org) {
                $('#casetypepricing-organisation_id').append('<option value="' + org.id + '">' + org.name + '</option>');
            });
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
            alert('Failed to load data.');
        }
    });
}


    </script>

</div>