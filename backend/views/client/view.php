<?php
use app\components\GlobalConstant;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\grid\GridView;
use backend\models\FileUpload;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use common\models\User;
use backend\models\search\ClientEntitySearch;

/* @var $this yii\web\View */
/* @var $model backend\models\Client */

$this->title = $model->client_name;
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$dataProviderRows = FileUpload::find()->where(['file_id'=>$model->additional_attachments])->all();

?>
<div class="client-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="mb-15">
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'client_name',
            [
              'attribute' => 'organisations',
              'label' => 'Northman Entities',
              'value' => function($model) {
                $organisationNames = array_map(function($clientOrganisation) {
                      return $clientOrganisation->organisation->name; // Assuming 'name' is the attribute you want to display
                  }, $model->organisations);
                if($organisationNames)
                return implode(', ', $organisationNames);
              },
          ],
            // 'country',
            // 'email',
            // 'phone',
            // 'address',
            // 'text_1570532600638',
            // [
            //     'label' => 'Company TRN',
            //     'attribute' => 'text_1578126561394'
            // ],
        ],
    ]); ?>
    
<!--    --><?php //if(!(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT))
//    {   ?>
<div style="margin-top: 20px">
    <div class="panel panel-default border-panel card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">Client Entities</h6>
                </div>
                <div class="clearfix"></div>
            </div>
            <?php
              $searchModel = new ClientEntitySearch();
              $dataProvider = $searchModel->search(['client_id'=> $model->id]);
              echo Yii::$app->controller->renderPartial('../client-entity/index', [
              'searchModel' => $searchModel,
              'dataProvider' => $dataProvider,
              ]);
          ?>    
    </div>
  </div>
      <!-- <div>
          
      </div> -->
<!--  --><?php //} ?>
</div>
<?php 
// The following block is for initializing a data provider and creating a GridView widget 
// Uncomment the code below to enable this feature if needed

/*
if ($dataProviderRows) {
    $provider = new ArrayDataProvider([
        'allModels' => $dataProviderRows,
        'pagination' => [
            'pageSize' => 20,
        ],
    ]);
?>
<div class="panel panel-default card-view border-panel panel-refresh mt-15">
    <div class="refresh-container">
        <div class="la-anim-1"></div>
    </div>
    <div class="panel-heading">
        Attachment
    </div>
    <?php Pjax::begin(['id' => 'attach-documents-pjax']); ?>
    <?= GridView::widget([
        'dataProvider' => $provider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['class' => 'abc', 'width' => '5%'],
            ],
            [
                'attribute' => 'name',
                'headerOptions' => [
                    'width' => '40%',
                ]
            ],
            [
                'attribute' => 'date',
                'headerOptions' => [
                    'width' => '10%',
                ],
                'value' => function ($model) {
                    if ($model->created_at)
                        return date('Y-m-d', strtotime($model->created_at));
                    else
                        return '';
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'attached_by',
                'headerOptions' => [
                    'width' => '30%',
                ],
                'value' => function ($model) {
                    if (!empty($model->uploaded_by)) {
                        $email = User::findOne($model->uploaded_by)->email;
                        return $email;
                    }
                    return '';
                },
                'format' => 'raw',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['class' => 'abc'],
                'contentOptions' => ['style' => GlobalConstant::ACTION_STYLE],
                'buttons' => [
                    'view' => function ($url, $model) {
                        $url = $model['attachment'];
                        return '<a class="mr-15" target="_blank" href="' . $url . '" title="View"><i class="fa fa-eye" style="color: #0092ee;"></i></a>';
                    },
                    'download' => function ($url, $model) {
                        $url = Yii::$app->urlManager->createUrl(['cases/download-attachment', 'attachmentID' => $model['id']]);
                        return '<a data-pjax="0" class="mr-15" href="' . $url . '" title="Download"><i class="fa fa-download" style="color: #22af47;"></i></a>';
                    },
                    'delete' => function ($url, $model) {
                        return '<span class="mr-15 delete-file" data-id="' . $model['id'] . '" title="Delete" style="cursor: pointer;"><i class="fa fa-close" style="color: #d20511;"></i></span>';
                    }
                ],
                'template' => '{view} {download} {delete}',
            ],
        ],
    ]) ?>
    <?php Pjax::end(); ?>
</div>
<?php 
} 
*/
?>


<script>
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
    $(document).ready(attachListeners)
    // $(document).ready(removeFile)
    // handleFileUpload()
    $(document).on('pjax:success', attachListeners);
    // $(document).on('pjax:success', removeFile);

</script>
