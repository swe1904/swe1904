<?php 

use app\components\GlobalConstant;
use yii\grid\GridView;
use yii\widgets\DetailView;
use backend\models\Client;
use backend\models\FileUpload;
use app\models\TempFile;
use yii\data\ArrayDataProvider;
use common\models\User;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use backend\models\Cases;

$this->title = Yii::t('backend', 'Attach Documents');
$this->params['breadcrumbs'][] = $this->title;

$dataProviderRows = [];
if (!empty($model->case_applicant_info)) {
  $caseInfo = (array) json_decode($model->case_applicant_info);
  
  unset($caseInfo['client_id']);
  
  //resolving custom field column names into labels
  $keys = array_keys($caseInfo);
  $initialKeys = $keys;
  for ($index = 0; $index < count($caseInfo); $index += 1) {
      //with file upload, this extra key comes, so unsetting it
      if (strpos($initialKeys[$index], '_upload') !== false || strpos($initialKeys[$index], 'attachment_ids_file_') !== false) {
          unset($keys[$index]);
          continue; 
      } elseif (strpos($initialKeys[$index], 'file') !== false) { //resolving labels of file type inputs
          $files = FileUpload::find()->where('file_id = :file_id', [':file_id' => $caseInfo[$initialKeys[$index]]])->all();
          foreach ($files as $file) {
            $email = null;
            if (!empty($file->uploaded_by)) {
              $email = User::findOne($file->uploaded_by)->email;
            }

            $createdAt = date('Y-m-d', strtotime($file->created_at));
            if (empty($file->created_at)) {
                $createdAt = null;
            }
            array_push($dataProviderRows, [
              'id' => $file->id,
              'name' => $file->name,
              'date' => $createdAt,
              'attached_by' => $email,
              'value' => $file->attachment,
              'issuance_date' => $file->issuance_date,
              'expiry_date' => $file->expiry_date,
               'interval_days_type_id' => $file->interval_days_type_id
            ]);
          }
      } else {
        unset($caseInfo[$initialKeys[$index]]);
        unset($keys[$index]);
      }
  }
} 
if (!empty($model->additional_attachments)) {
  $fileID = $model->additional_attachments;
  $files = FileUpload::find()->where(['file_id' => $fileID])->all();
  if (!empty($files)) {
    foreach($files as $file) {
      $email = null;
      if (!empty($file->uploaded_by)) {
        $email = User::findOne($file->uploaded_by)->email;
      }
      array_push($dataProviderRows, [
        'id' => $file->id,
        'name' => $file->name,
        'date' => date('Y-m-d', strtotime($file->created_at)),
        'attached_by' => $email,
        'value' => $file->attachment,
        'issuance_date' => $file->issuance_date,
        'expiry_date' => $file->expiry_date,
        'interval_days_type_id' => $file->interval_days_type_id
      ]);
    }
  }
} 

$provider = new ArrayDataProvider([
  'allModels' => $dataProviderRows,
  'pagination' => [
      'pageSize' => 20,
  ],
]);

?>

        <style>
            .table-responsive {
                overflow-x: auto; 
            }

            .grid-view table th, 
            .grid-view table td {
                white-space: nowrap; 
                vertical-align: middle;
            }

         
            .grid-view table td.name-column, 
            .grid-view table th.name-column {
                max-width: 150px; 
                word-wrap: break-word;
                white-space: normal; 
            }

         
            .grid-view table td.attached-by-column, 
            .grid-view table th.attached-by-column {
                max-width: 120px; 
                word-wrap: break-word;
                white-space: normal; 
            }

         
            .grid-view table input[type="date"], 
            .grid-view table .form-control {
                width: 100%; 
                min-width: 100px; 
            }

           
            .grid-view .dropdown-menu {
                max-width: 100%; 
            }
        </style>


<div class="col-md-12">
    <h2 style="margin-bottom: 20px;"><?php echo $model->case_number; ?></h2>

    <div class="panel panel-default card-view border-panel panel-refresh">
        <div class="refresh-container">
            <div class="la-anim-1"></div>
        </div>
        
        <div class="panel-heading">
            <?php Pjax::begin(['id' => 'attach-documents-pjax']); ?>
                <div class="table-responsive">
                <?= GridView::widget([
                        'dataProvider' => $provider,
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'headerOptions' => ['width' => '5%'],
                            ],
                            [
                                'attribute' => 'name',
                                'headerOptions' => ['class' => 'name-column', 'width' => '20%'],
                                'contentOptions' => ['class' => 'name-column'],
                            ],
                            [
                                'attribute' => 'date',
                                'headerOptions' => ['width' => '10%'],
                            ],
                            [
                                'attribute' => 'attached_by',
                                'headerOptions' => ['class' => 'attached-by-column', 'width' => '15%'],
                                'contentOptions' => ['class' => 'attached-by-column'],
                            ],
                            [
                                'attribute' => 'issuance_date',
                                'headerOptions' => ['width' => '10%'],
                                'value' => function ($model) {
                                    return \kartik\date\DatePicker::widget([
                                        'name' => "issuance_date[{$model['id']}]",
                                        'value' => $model['issuance_date'],
                                        'options' => [
                                            'placeholder' => 'Select a date...',
                                            'class' => 'form-control issuance_date',
                                            'data-id' => $model['id'],
                                            'onchange' => "saveDates('issuance_date', this.value, {$model['id']})",
                                        ],
                                        'pluginOptions' => [
                                            'todayHighlight' => true,
                                            'todayBtn' => 'linked',
                                            'format' => 'yyyy-mm-dd',
                                            'autoclose' => true,
                                            'clearBtn' => true,
                                        ],
                                    ]);
                                },
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'expiry_date',
                                'headerOptions' => ['width' => '10%'],
                                'value' => function ($model) {
                                    return \kartik\date\DatePicker::widget([
                                        'name' => "expiry_date[{$model['id']}]",
                                        'value' => $model['expiry_date'],
                                        'options' => [
                                            'placeholder' => 'Select a date...',
                                            'class' => 'form-control expiry_date',
                                            'data-id' => $model['id'],
                                            'onchange' => "saveDates('expiry_date', this.value, {$model['id']})",
                                        ],
                                        'pluginOptions' => [
                                            'todayHighlight' => true,
                                            'todayBtn' => 'linked',
                                            'format' => 'yyyy-mm-dd',
                                            'autoclose' => true,
                                            'clearBtn' => true,
                                        ],
                                    ]);
                                },
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'interval_days_type_id',
                                'label'     => 'Interval Days Type',
                                'headerOptions' => ['width' => '10%'],
                                'value' => function ($model) {
                                    return Html::dropDownList(
                                        "interval_days_type_id[{$model['id']}]",
                                        $model['interval_days_type_id'],
                                        GlobalConstant::INTERVAL_TYPE_ARRAY, 
                                        [
                                            'class' => 'form-control interval_days_type_id',
                                            'prompt' => 'Select Interval',
                                            'onchange' => "saveDates('interval_days_type_id', this.value, {$model['id']})",
                                        ]
                                    );
                                },
                                'format' => 'raw',
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'headerOptions' => ['width' => '10%'],
                                'template' => '{view} {download} {delete}',
                                'buttons' => [
                                    'view' => function ($url, $model) {
                                        return '<a style="margin-top: 23px!important;" class="mr-15" target="_blank" href="' . $model['value'] . '" title="View"><i class="fa fa-eye" style="color: #0092ee;"></i></a>';
                                    },
                                    'download' => function ($url, $model) {
                                        return '<a style="margin-top:23px!important;" class="mr-15" href="' . Yii::$app->urlManager->createUrl(['cases/download-attachment', 'attachmentID' => $model['id']]) . '" title="Download"><i class="fa fa-download" style="color: #22af47;"></i></a>';
                                    },
                                    'delete' => function ($url, $model) {
                                        return '<span style="margin-top:23px!important;" class="mr-15 delete-file" data-id="' . $model['id'] . '" title="Delete" style="cursor: pointer;"><i class="fa fa-close" style="color: #d20511;"></i></span>';
                                    },
                                ],
                            ],
                        ],
                    ]); ?>

                </div>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

    <?php 
      $form = ActiveForm::begin([
        'action' => 'submit-attachments',
      ]);
    ?>
    <div class='form-group'>
        <div class='row margin_unset upload_cont_all'>
         <label class='control-label' style="font-weight: 700;">Attach Documents</label>
          <div class='row margin_unset upload_cont'>
           <div class='row margin_unset'>
    
      <?php if(empty($model->additional_attachments)){   
          $additionalAttachments = \Yii::$app->security->generateRandomString(8).str_replace('.','',microtime(true));
          $model->additional_attachments = $additionalAttachments;
        }   
      ?>

      <?php echo $form->field($model, 'id')->hiddenInput()->label(false); ?>

      <?php echo $form->field($model, 'additional_attachments')->hiddenInput()->label(false); ?>

      <?php echo $form->field($model, 'additional_attachments_upload')->hiddenInput()->label(false); ?>

      </div>

    <?php 
      ?><div class='row margin_unset'>
           <?=\common\components\DropZone::widget([
                "id" => "drop_zone_new_form_project",
                "dropzoneContainer" => "drop_zone_container_new_form_project_additional_attachments",
                "previewsContainer" => "drop_zone_preview_container_new_form_project_additional_attachments",
                "options" => [
                    "url" => \yii\helpers\Url::to(["mii/file-upload/upload-temp-file","session_id"=>$model->additional_attachments]),
                    "paramName" => "attachment",
                    "maxFilesize" => "20",
                    "addRemoveLinks" => true,
                ],
                "clientEvents" => [
                    "complete" => "function(file){
                      handleFileUpload();
                    }",
                    "removedfile" => "function(file){
                      removeFile(file);
                    }",
                    "success" => "function(data){
                    }"
                ],
            ]); ?>
          </div></div></div></div>
          
          <?php echo Html::submitButton('Submit', ['class' => 'btn btn-rounded btn-success mr-10 mb-20', 'id' => 'submit-btn']) ?>
    <?php ActiveForm::end(); ?>
  </div>

  <script>
    function handleFileUpload() {
      var length = $('.dz-hidden-input').length;
      if (length === 0) {
        $('#submit-btn').attr('disabled', true);
      } else {
        $('#submit-btn').attr('disabled', false);
      }
    }
    function removeFile(file) {
      $.ajax({
        'type': 'POST',
        'url': '<?php echo \yii\Helpers\Url::to(['cases/remove-temp-file']); ?>',
        'data': {
          sessionID: '<?php echo $model->additional_attachments; ?>',
          fileName: file.name,
        },
        'success': function(response) {
          var responseData = JSON.parse(response);
          if (responseData.code == 1) {
            toastr.success(responseData.message);
          }
        }
      })
    }

    function attachListeners() {
      $('.delete-file').on('click', function() {
        let fileID = $(this).attr('data-id');
        $(this).html('<div class="fa fa-circle-o-notch fa-spin"></div>');
        $.ajax({
          type: 'POST',
          url: '<?php echo \yii\Helpers\Url::to(['applicant/delete-file']) ?>',
          data: {
            fileID: fileID
          },
          success: function (response) {
            let responseData = JSON.parse(response);
            if (responseData.code === 1) {
              toastr.success(responseData.message);
              $.pjax.reload({container: '#attach-documents-pjax', timeout: 3000, async: false});
            } else {
              toastr.error(responseData.message);
            }
            $('.delete-file').html('<i class="fa fa-close" style="color: #d20511;"></i>');
          }
        })
      })
    }
   // Function to send data via AJAX for the selected date or interval
   function saveDates(type, value, rowId) {
    var data = {
        id: rowId,        
        type: type,     
        date: value      
    };
    console.log(data);
    $.ajax({
        url: '<?= Yii::$app->urlManager->createUrl(["cases/save-dates"]) ?>', 
        type: 'POST',
        data: data,
        success: function(response) {
            var responseData = JSON.parse(response);
            if (responseData.success) {
                var message = '';
                if (responseData.type === 'issuance_date') {
                    message = 'Issuance date saved successfully.';
                } else if (responseData.type === 'expiry_date') {
                    message = 'Expiry date saved successfully.';
                } else if (responseData.type === 'interval_days_type_id') {
                    message = 'Interval days saved successfully.';
                }
                // Show a toast message
                toastr.success(message);
            } else {
                toastr.error('Failed to save the data.');
            }
        },
        error: function() {
            toastr.error('An error occurred while saving the data.');
        }
    });
}


    $(document).ready(attachListeners)
    $(document).ready(removeFile)
    handleFileUpload()
    $(document).on('pjax:success', attachListeners);
    $(document).on('pjax:success', removeFile);

  </script>
</div>