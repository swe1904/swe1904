<?php 
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\components\GlobalConstant;
?>
<style>
    /* .modal-footer{
        display: none;
        
    }
     */
</style>
<?//php Pjax::begin(['id' => 'services-pjax','timeout' => 10000, 'enablePushState' => false]); ?>
<div>
    <?= "<b>Client  : </b>". $model->client->client_name."<br>" ?>
    <?= "<b>Client Entity : </b>". $model->clientEntity->name."<br>" ?>
    <?= "<b>Currency : </b>". $model->currency->name." - ".$model->currency->iso."<br>" ?>
    <?= "<b>Case Type  : </b>". $model->caseType->name."<br>" ?>
    <?= "<b>Northman Entity  : </b>". $model->organisation->name."<br>" ?>
</div>

<?php if($dataProvider->getTotalCount() > 0){ ?>
<?= GridView::widget([
        'filterOnFocusOut' => false,
        'layout' => "\n{items}\n",
        'dataProvider' => $dataProvider,
        'responsive' => true,
        'responsiveWrap' => false,
        'bordered' => false,
        'options' => [
            'class' => 'table-responsive',
            "style"=>'width: 90%'
        ],
        'tableOptions' => ['class' => 'table', ],
        // 'tableOptions'=>['class'=> 'table data-table '],
        'columns' => [
            // 'service_name',
            // 'price'
            [
                'attribute' => 'service_name',
                'format' => 'raw',
                'label' => 'Service Name',
                'value' => function($model) {
                    if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN)
                        return '<input type="text" id="service-name-'.$model->id.'" class="form-control case-type-service-name" style="width: 100%" value="'.$model->service_name.'" data-id='.$model->id.'>';
                    else
                    return $model->service_name;
                },
                // 'headerOptions' => ['style' => 'width: 5%']
              ],
            [
                'attribute' => 'price',
                'format' => 'raw',
                'label' => 'Price',
                'value' => function($model) {
                    if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN)
                        return '<input type="number" id="price-'.$model->id.'" class="form-control case-type-price" style="width: 100%" value="'.$model->price.'" data-id='.$model->id.'>';
                    else
                    return $model->price;
                },
                // 'headerOptions' => ['style' => 'width: 5%']
              ],  
            // [
            //     'format' => 'raw',
            //     'label' => null,
            //     'value' => function($model) {
            //         return '<div class="btn-case-type-price" style="border-radius: 10px; cursor: pointer; height: 28px; background-color: #22af47; aspect-ratio: 1/1; display: flex; justify-content: center; align-items: center; z-index: 2;" data-id="'.$model->id.'"><i class="fa fa-check" style="color: white;"></i>';
            //     },
            //     // 'headerOptions' => ['style' => 'width: 10%']
            // ],
            // [
            //     'format' => 'raw',
            //     'label' => null,
            //     'value' => function($model) {
            //         return '<a class="" href="'.$url.'" style="border-radius: 10px; cursor: pointer; height: 28px; background-color: #22af47; aspect-ratio: 1/1; display: flex; justify-content: center; align-items: center; z-index: 2;" data-method="post" data-confirm = "'.Yii::t('yii', 'Are you sure you want to delete this item?').'",  title="Delete"><i class="fa fa-trash text-danger"></i></a>';
            //     },
            //     // 'headerOptions' => ['style' => 'width: 10%']
            // ],

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'padding-top:15px'],
                'template' => Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN ?'{save}{delete}':'',
    
                'buttons' => [
//                    'view' => function ($url, $subtask) {
//                        if(isset($subtask->project->id) && isset($subtask->module_id) && isset($subtask->id))
//                        return Html::a('<span class="ti-search"></span> ', Url::to(['default/view','id'=>$subtask->project->id,'module_id'=>$subtask->module_id,'point_id'=>$subtask->parent_id, 'subtask_id'=>$subtask->id]), [
//                            'title' => Yii::t('app', 'View'),
//                            'class' => 'class-details action-btn btn-success btn-xs px-2',
//                            'data-toggle' => 'tooltip',
//                            'data-placement' => 'top',
//                        ]);
//                    },
                    'save' => function($url, $model){
                            // $url = Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/casetypepricing-delete', 'id' => $model->id]);
                            return '<a class="mr-25 case-type-price-save" href="javascript:void(0);" title="Save" style="background-color: #22af47;" data-id="'.$model->id.'"><i class="fa fa-check" style="color: white;"></i></a>';
                        },
                    'delete' => function($url, $model){
                            $url = Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/service-delete', 'id' => $model->id]);
                            return '<a class="mr-25" href="'.$url.'" data-method="post" data-confirm = "'.Yii::t('yii', 'Are you sure you want to delete this item?').'",  title="Delete"><i class="fa fa-trash text-danger"></i></a>';
                        },
    
                ],               
            ],
        ]
    ]);
    ?>
<?php }?>
<?php if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN){?>

<div class="clocking-list">
    <table id='service-table' class="clocking-table table w-100">
        <tr>
            <th style="width: 60%;">Service</th>
            <th style="width: 20%;">Price</th>
            <th style="width: 10%;">
                <!-- <button class="action_btn btn btn-danger cancel" title="Cancel"
                style="width: 26px;height: 26px;display: flex;justify-content: center;align-items: center;border-radius: 4px;margin: 0px !important;">
                    <span class="ti-minus"></span>
                </button> -->
            </th>
        </tr>
    </table>
</div>


<!-- READ ME - This is add_on input box to add service and its price -->

<div class="col-md-12" style="display: flex;justify-content: center;align-items: center;">
    <div class="col-md-8">
        <input name="service" type="text" class="form-control" placeholder="Service Name" required>
    </div>
    <div class="col-md-3">
        <input name="price" type="number" class="form-control" placeholder="Price" required>
    </div>
    <div class="col-md-1">
        <button type="submit" id="btn-add-service" class="action_btn btn btn-success add" title="Add service" style="width: 26px;height: 26px;display: flex;justify-content: center;align-items: center;border-radius: 4px;margin: 0px !important;background: #22af47;border: 0px;">
            <span class="ti-plus"></span>
        </button>
    </div>
</div>
<span class="text-danger ml-3" id="logout-popup-error"></span>
<div class="text-centre" style="text-align: center;">
<?php echo \yii\helpers\Html::Button('Submit ',['name'=>'add_service_submit_btn', 'class' =>'btn btn-sm btn-rounded btn-success mt-10', 'id'=>'btn-submit', 'onclick'=>'submitServices()', 'data-loading-text' => '<i class="fa fa-spinner fa-spin "></i> Submitting'])?>
</div>
<?php }?>
<?//php Pjax::end() ?>
<!-- <button type="button" id="add" class="btn btn-success btn-sm add" title="Add Task"><span class="glyphicon glyphicon-plus"></span></button> -->
 <script>

    $(document).ready(function() {
        $('#btn-submit').button(); // Initialize the button
    });
    $('#btn-add-service').click(function (e)
    {
            e.preventDefault();
        service = $('input[name="service"]').val();
        price = $('input[name="price"]').val();
        if(!service.length)
        {
            $('#logout-popup-error').css('display', 'block').text("Service name cannot be blank");
            return;
        }
        if(!price)
        {
            $('#logout-popup-error').css('display', 'block').text("Price cannot be blank");
            return;
        }

        
            var html = '';
            // html += '<tr><td name="item_serial[]" style="width: 5%;">'+maxItems+'.</td>';

            html += '<tr><td name="item_service[]" style="width: 60%;">'+service+'</td>';

            html += '<td name="item_price[]" style="width: 26%;">'+price+'</td>';

            // html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm remove" title="Delete task"><span class="glyphicon glyphicon-minus"></span></button></td></tr>';
            // style="width: 10%;"
            html += '<td ><button type="button"class="action_btn btn btn-danger cancel remove" title="Cancel" style="width: 26px;height: 26px;display: flex;justify-content: center;align-items: center;border-radius: 4px;margin: 0px !important;"><span class="ti-minus"></span></button></td> </td></tr>';
            $('#service-table').append(html);
        
        $('input[name="service"]').val('');
        $('input[name="price"]').val('');
    })

    $(document).on('click', '.remove', function()
    {
        $(this).closest('tr').remove();
        // var serial=1;
        // $("td[name='item_serial[]']").each(function() {
        //     $(this).text((serial++)+". ");
        // });
        $('#logout-popup-error').css('display', 'none').text('');
        // maxItems--;
    });

    $(document).on('change', 'input[name=service],input[name=price]', function()
    {
        $('#logout-popup-error').css('display', 'none').text('');
        $('#btn-submit').attr('disabled',false)
    });

    function submitServices() {
        // $('#btn-submit').button('loading');
        $('#btn-submit').prop('disabled', true);
        if ($("td[name='item_service[]']").length === 0 && $("td[name='item_price[]']").length === 0) {
            toastr.error("Add at least one service to submit");
            return false;
        }
        // Optionally, you can also change the text or add a spinner to indicate that the process is ongoing
        $('#btn-submit').html('<i class="fa fa-spinner fa-spin"></i> Submitting');
        var service = [];
        var price = [];
        $("td[name='item_service[]']").each(function() {
            service.push($(this).text());
        });

        $("td[name='item_price[]']").each(function() {
            price.push($(this).text());
        });
        // console.log('Price', price);
        // return;
        var pricingId = "<?= $model->id?>"
        $.ajax({
            method: 'POST',
            url: '<?= Url::to(['add-service'])?>',
            data: {
                caseTypePricingId:pricingId,
                service: service,
                price: price,
            },
            success: function (response) {
                data = JSON.parse(response);
                if (data.code === 1) {
                    toastr.success(data.message);
                    $("#add_services").modal('hide');
                    if ($('tr[data-key="'+pricingId+'"]').length === 0) {
                        // If it case type price doesn't exist, reload the page
                        location.reload();
                    }
                    // $.pjax.reload({container: '#services-pjax', async: false});
                }
                else
                    toastr.error(data.message);
            },
            error: function(xhr, textStatus, errorThrown) {
                toastr.error("Something Went Wrong!!");
            }
        })
    }

    $('.case-type-price-save').on('click', function(){
        
        var serviceID = $(this).attr('data-id')

        var serviceName = $('#service-name-'+serviceID).val()
        var price = $('#price-'+serviceID).val();
        if(!serviceName || !price)
        {
            toastr.error("Service name or price cannot be blank")
            return;
        }

        $.ajax({
          type: 'POST',
          url: 'update-case-type-service-price',
          data: {
            'serviceId': serviceID,
            'serviceName': serviceName,
            'price': price,
          },
          success: function(response) {
            var data = JSON.parse(response)
            if (data.code === 1) {
              toastr.success(data.message)
            } else {
              toastr.error(data.message)
            }
          }
        })
    })

 </script>