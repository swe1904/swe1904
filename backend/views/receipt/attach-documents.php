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
use backend\models\ReceiptItem;

$this->title = Yii::t('backend', 'Fee Attachment');
$this->params['breadcrumbs'][] = $this->title;

$dataProviderRows = [];


if (!empty($model->additional_attachments)) {
  $fileID = $model->additional_attachments;
  $files = FileUpload::find()->where(['file_id' => $fileID])->all();

  if (!empty($files)) {
    foreach ($files as $file) {
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
;
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


<div class="col-md-12 ">
  <h2 style="margin-bottom: 20px;">Upload Documents</h2>

  <div class="panel panel-default card-view border-panel panel-refresh">
    <div class="refresh-container">
      <div class="la-anim-1">

      </div>
    </div>
    

    <!-- <div class="card shadow-sm mt-4"> -->
<?php
$receiptType = 'Receipt';
if(isset($_GET['receiptType']))
{
 $receiptType = $_GET['receiptType'];
}
else{
 $receiptType = 'Receipt';
}
 ?>
      <div class="card-body">
        <div class="mb-3">
          <p class="mb-1 fs-5">
            <strong class="text-dark"><?=$receiptType?> Number:</strong>
            <span class="text-primary font-weight-bold"><?= $receipt->receipt_number; ?></span>
          </p>
        </div>
        <div>
          <p class="mb-1 fs-5">
            <strong class="text-dark">Description:</strong>
            <span class="text-primary"><?= $model->description; ?></span>
          </p>
        </div>
      </div>

    <!-- </div> -->
    <div class="panel-heading mt-5">
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
                                'class' => 'yii\grid\ActionColumn',
                                'headerOptions' => ['width' => '10%'],
                                'template' => '{view} {download} {delete}',
                                'buttons' => [
                                    'view' => function ($url, $model) {
                                        return '<a  class="mr-15" target="_blank" href="' . $model['value'] . '" title="View"><i class="fa fa-eye" style="color: #0092ee;"></i></a>';
                                    },
                                    'download' => function ($url, $model) {
                                        return '<a  class="mr-15" href="' . Yii::$app->urlManager->createUrl(['receipt/download-attachment', 'attachmentID' => $model['id']]) . '" title="Download"><i class="fa fa-download" style="color: #22af47;"></i></a>';
                                    },
                                    'delete' => function ($url, $model) {
                                        return '<span  class="mr-15 delete-file" data-id="' . $model['id'] . '" title="Delete" style="cursor: pointer;"><i class="fa fa-close" style="color: #d20511;"></i></span>';
                                    },
                                ],
                            ],
                        ],
                    ]); ?>

                </div>
            <?php Pjax::end(); ?>
        </div>
    <?php
    $form = ActiveForm::begin([
      'action' => 'submit-attachments',
    ]);
    ?>
    <?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger">
        <?= Yii::$app->session->getFlash('error') ?>
    </div>
    <?php endif; ?>
    <div class='form-group'>
      <div class='row margin_unset upload_cont_all'>
        <label class='control-label' style="font-weight: 700;">Attach Documents</label>
        <div class='row margin_unset upload_cont'>
          <div class='row margin_unset'>

            <?php if (empty($model->additional_attachments)) {
              $additionalAttachments = \Yii::$app->security->generateRandomString(8) . str_replace('.', '', microtime(true));
              $model->additional_attachments = $additionalAttachments;
            }
            ?>

            <?php echo $form->field($model, 'id')->hiddenInput()->label(false); ?>

            <?php echo $form->field($model, 'additional_attachments')->hiddenInput()->label(false); ?>



          </div>

          <?php

          ?><div class='row margin_unset'>
           <?= \common\components\DropZone::widget([
    "id" => "drop_zone_new_form_project",
    "dropzoneContainer" => "drop_zone_container_new_form_project_additional_attachments",
    "previewsContainer" => "drop_zone_preview_container_new_form_project_additional_attachments",
    "options" => [
        "url" => \yii\helpers\Url::to(["mii/file-upload/upload-temp-file", "session_id" => $model->additional_attachments]),
        "paramName" => "attachment",
        "maxFilesize" => "20", // Maximum file size in MB
        "addRemoveLinks" => true,
        "acceptedFiles" => ".jpeg,.jpg,.png,.gif,.bmp,.webp", // Allow only these formats
        "dictInvalidFileType" => "Only image files (.jpeg, .jpg, .png, .gif, .bmp, .webp) are allowed.", // Custom error message
    ],

    "clientEvents" => [
        "complete" => "function(file) {
            handleFileUpload(); // Custom function to handle the uploaded file
        }",
        "removedfile" => "function(file) {
            removeFile(file); // Custom function to handle file removal
        }",
       
        "addedfile" => "function(file) {
           
            const validExtensions = ['jpeg', 'jpg', 'png', 'gif', 'bmp', 'webp'];
            const fileExtension = file.name.split('.').pop().toLowerCase();
            if (!validExtensions.includes(fileExtension)) {
                this.removeFile(file); // Remove the invalid file
                toastr.error('Invalid file format. Please upload an image file.'); // Show error message
            }
        }",
    ],
]); ?>

          </div>
        </div>
      </div>
    </div>

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
        'url': '<?php echo \yii\Helpers\Url::to(['receipt/remove-temp-file']); ?>',
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
          success: function(response) {
            let responseData = JSON.parse(response);
            if (responseData.code === 1) {
              toastr.success(responseData.message);
              $.pjax.reload({
                container: '#attach-documents-pjax',
                timeout: 3000,
                async: false
              });
            } else {
              toastr.error(responseData.message);
            }
            $('.delete-file').html('<i class="fa fa-close" style="color: #d20511;"></i>');
          }
        })
      })
    }
    $(document).ready(attachListeners)
    $(document).ready(removeFile)
    handleFileUpload()
    $(document).on('pjax:success', attachListeners);
    $(document).on('pjax:success', removeFile);
  </script>
</div>