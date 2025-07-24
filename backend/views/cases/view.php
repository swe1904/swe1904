<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Cases;
use backend\models\ClientEntity;
use backend\models\Applicant;
use backend\models\Client;
use app\models\TempFile;
use backend\models\FileUpload;
use common\models\User;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use app\components\GlobalConstant;

/* @var $this yii\web\View */
/* @var $model backend\models\Cases */

$this->title = $model->case_number;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Cases'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

        // $applicantAttributes = Applicant::attributeLabels(); 
        $applicantModel = new Applicant();
        $applicantAttributes = $applicantModel->attributeLabels();
        $applicantAttributeKeys = array_keys($applicantAttributes);
        
        $fileAttributes = array_values(array_filter($applicantAttributeKeys, function($key) {
            return strpos($key, 'file_') !== false;
        }));

        $applicant = $model->applicant;
        
        $fileAttributeValues = [];

        if ($applicant) {
            // Retrieve the values of the attributes in $fileAttributes
            foreach ($fileAttributes as $attribute) {
                if ($applicant->hasAttribute($attribute)) {
                    $fileAttributeValues[$attribute] = $applicant->$attribute;
                }
            }
            // // $fileAttributeValues now contains the attribute values as a normal array
        }


        
        $applicantDocs = FileUpload::find()->where(['in', 'file_id', $fileAttributeValues])->all();


?>

<?php 
    //finding Case info and attributes 
    // if (isset($_GET['id'])) {
    //     $case = Cases::findOne($_GET['id']);
    //     if (!empty($case->case_applicant_info)) {
    //         $caseInfo = (array) json_decode($case->case_applicant_info);
    //         $caseInfo['client_name'] = (Client::findOne($caseInfo['client_id']))->client_name;
    //         if (!empty($case->client_entity)) {
    //             $caseInfo['client_entity'] = ClientEntity::findOne($case->client_entity)->name;
    //         }
    //         if (!empty($case->raised_by_id)) {
    //             $raisedBy = User::findOne($case->raised_by_id);
    //             if (!empty($raisedBy)) {
    //                 $raisedByEmail = $raisedBy->email;
    //                 $caseInfo['client_case_worker'] = $raisedByEmail;
    //             }
    //         }
    //         unset($caseInfo['client_id']);

    //         //resolving custom field column names into labels
    //         $keys = array_keys($caseInfo);
    //         $initialKeys = $keys;
    //         $applicantFieldLabels = (new Applicant)->attributeLabels();
    //         for ($index = 0; $index < count($caseInfo); $index += 1) {
    //             //with file upload, this extra key comes, so unsetting it
    //             if (strpos($initialKeys[$index], '_upload') !== false || strpos($initialKeys[$index], 'attachment_ids_file_') !== false) {
    //                 unset($keys[$index]);
    //                 continue; 
    //             } elseif (strpos($initialKeys[$index], 'file') !== false) { //resolving labels of file type inputs
    //                 $file = TempFile::find()->where('session_id=:session_id',[':session_id' => $caseInfo[$initialKeys[$index]]])->all();
    //                 if (empty($file)) {
    //                     $file = FileUpload::find()->where('file_id = :file_id', [':file_id' => $caseInfo[$initialKeys[$index]]])->all();
    //                 }
    //                 $label = $applicantFieldLabels[$initialKeys[$index]];
    //                 $keys[$index] = [
    //                     'attribute' => $label,
    //                     'format' => 'raw',
    //                     'value' => function() use ($file) {
    //                         $returnValue = '';
    //                         foreach($file as $fileObj) {
    //                             if (!empty($fileObj->attachment)) {
    //                                 if (!empty($fileObj->name)) {
    //                                     $returnValue = $returnValue . '<a style="color: #1E90FF" target="_blank" href="'.$fileObj->attachment.'">'.$fileObj->name.'</a><br>';
    //                                 } else {
    //                                     $returnValue = $returnValue . '<a style="color: #1E90FF" target="_blank" href="'.$fileObj->attachment.'">'.$fileObj->attachment.'</a><br>';
    //                                 }
    //                             }
    //                         }
    //                         return $returnValue;
    //                     },
    //                 ];
    //             } else { //handling every other type
    //                 if (array_key_exists($initialKeys[$index], $applicantFieldLabels)) {
    //                     $label = $applicantFieldLabels[$initialKeys[$index]];
    //                     $keys[$index] = [
    //                         'attribute' => $initialKeys[$index],
    //                         'label' => $label,
    //                     ];
    //                 }
    //             }
    //         }
    //         $attributes = $keys;
    //     } else { 
    //         $caseInfo = $model;
    //         $attributes = array_keys((new Applicant)->attributeLabels());
    //     }
    // }
            $attributes = [
                                'id',
                                'case_number',
                                [
                                    'attribute' => 'client_id',
                                    'label' => 'Client Name',
                                    'value' => function($model) {
                                        if($model->client->client_name)
                                            return  $model->client->client_name;
                                    },
                                ],
                                [
                                    'attribute' => 'applicant_id',
                                    'value' => function($model) {
                                        
                                        return  $model->applicant->first_name.' '.$model->applicant->last_name;
                                    },
                                ],
                                [
                                    'attribute' => 'case_type_id',
                                    'value' => function($model) {
                                        
                                        return  $model->caseType->name;
                                    },
                                ],
                                [
                                    'attribute' => 'organisation_id',
                                    'label' => 'Northman Billing Office',
                                    'value' => function($model) {
                                        if($model->organisation)
                                            return  $model->organisation->name;
                                    },
                                ],
                                [
                                    'attribute' => 'case_work_office_id',
                                    'label' => 'Case Work Office',
                                    'value' => function($model) {
                                        if($model->caseWorkOffice)
                                            return  $model->caseWorkOffice->name;
                                    },
                                ],
                                [
                                  'attribute' => 'client_entity',
                                  'label' => 'Client Entity',
                                  'value' => function($model) {
                                      if($model->clientEntity)
                                          return  $model->clientEntity->name;
                                  },
                              ],
                              [
                                'attribute' => 'client_billing_entity',
                                'label' => 'Client billing Entity',
                                'value' => function($model) {
                                    if($model->client_billing_entity)
                                        return  $model->client_billing_entity;
                                },
                            ],
                                'target_completion_date',
                               
                                [
                                    'attribute' => 'assigned_to',
                                    // 'visible'=> !empty($model->assigned_to),
                                    'value' => function($model) {
                                        if(!empty($model->assigned_to) && $model->caseWorker)
                                            return  $model->caseWorker->username.' ('.$model->caseWorker->email.')' ;
                                        else
                                            return '-Case Worker not assigned-';
                                    },
                                ],
                                [
                                    'attribute' => 'client_case_manager_id',
                                    'visible'=> !empty($model->assigned_to),
                                    'value' => function($model) {
                                        if(!empty($model->client_case_manager_id) && $model->clientCaseManager)
                                            return  $model->clientCaseManager->username.' ('.$model->clientCaseManager->email.')' ;
                                        else
                                            return '-Client Case Manager not assigned-';
                                    },
                                ],
                             
                                [
                                    'attribute' => 'client_case_worker_id',
                                    // 'visible'=> !empty($model->assigned_to),
                                    'value' => function($model) {
                                       
                                        if(!empty($model->client_case_worker_id) && $model->clientCaseWorker)
                                     
                                            return  $model->clientCaseWorker->username.' ('.$model->clientCaseWorker->email.')' ;
                                        else
                                            return '-Client Case Worker not assigned-';
                                    },
                                ],
                            ];
                            if (!in_array(Yii::$app->user->identity->getRole(), [
                            GlobalConstant::ROLE_CLIENT_CASE_MANAGER,
                            GlobalConstant::ROLE_CLIENT_CASE_WORKER
                        ])) {
                            $attributes[] = [
                               'attribute' => 'case_manager_id',
                                    // 'visible'=> !empty($model->case_manager_id),
                                    'value' => function($model) {
                                        if(!empty($model->case_manager_id) && $model->caseManager)
                                            return  $model->caseManager->username.' ('.$model->caseManager->email.')' ;
                                        else
                                            return '-Case Manager not assigned-';
                                    },
                            ];
}

    ?>

<div class="cases-view">

    <h2><?= Html::encode($model->case_number) ?></h2>

    <!-- <p>
        <? //= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <? //= Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->id], [
            //'class' => 'btn btn-danger',
            //'data' => [
             //   'confirm' => Yii::t('backend', 'Are you sure you want to delete this item?'),
             //   'method' => 'post',
            //],
        //]) ?>
    </p> -->
            
    <?= DetailView::widget([
        // 'model' => $caseInfo,
        'model' => $model,
        'attributes' => $attributes,
    ]) ?>
</div>
<?php 
if($applicantDocs) {

$provider = new ArrayDataProvider([
    'allModels' => $applicantDocs,
    'pagination' => [
        'pageSize' => 20,
    ],
  ]);?>

<div class="panel panel-default card-view border-panel panel-refresh mt-15">
    <div class="refresh-container">
      <div class="la-anim-1"></div>
    </div>
    <div class="panel-heading">
    Applicant Document(s)
    </div>
      <?php //Pjax::begin(['id' => 'attach-documents-pjax']); ?>
        <?= GridView::widget([
            'dataProvider' => $provider,
            'columns' => [
              [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['class' => 'abc', 'width' => '5%'],
              ],
              [
                // 'attribute' => 'date',
                'label' => 'File Type',
                'headerOptions' => [
                  'width' => '20%'
                ],
                'value' => function ($model) use($applicantAttributes,$fileAttributeValues){

                    $key = array_search($model->file_id, $fileAttributeValues);
                    return $applicantAttributes[$key];
                    // if($model->created_at)
                    //     return date('Y-m-d', strtotime($model->created_at));
                    // else
                    //     return '';
                },
                'format' => 'raw',
              ],
              [
                'attribute' => 'name',
                'headerOptions' => [
                  'width' => '40%'
                ]
              ],
              
            //   [
            //     'attribute' => 'date',
            //     'headerOptions' => [
            //       'width' => '10%'
            //     ],
            //     'value' => function ($model) {
            //         if($model->created_at)
            //             return date('Y-m-d', strtotime($model->created_at));
            //         else
            //             return '';
            //     },
            //     'format' => 'raw',
            //   ],
            //   [
            //     'attribute' => 'attached_by',
            //     'headerOptions' => [
            //       'width' => '30%'
            //     ],
            //     'value' => function ($model) {
            //         if (!empty($model->uploaded_by)) {
            //             $email = User::findOne($model->uploaded_by)->email;
            //             return $email;
            //           }
            //         else
            //             return '';
            //     },
            //     'format' => 'raw',
            //   ],
              [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['class' => 'abc'],
                'contentOptions' => ['style' => GlobalConstant::ACTION_STYLE],
                'buttons'=>[
                    'view' => function ($url, $model) {
                      $url = $model['attachment'];
                      return '<a class="mr-15" target="_blank" href="' . $url . '" title="View"><i class="fa fa-eye" style="color: #0092ee;"></i></a>';
                    },
                    'download' => function($url, $model) {
                      $url = Yii::$app->urlManager->createUrl(['cases/download-attachment', 'attachmentID' => $model['id']]);
                      return '<a data-pjax="0" class="mr-15" href="' . $url . '" title="Download"><i class="fa fa-download" style="color: #22af47;"></i></a>';
                    },
                    'delete' => function ($url, $model) {
                      return '<span class="mr-15 delete-file" data-id="' . $model['id'] . '"title="Delete" style="cursor: pointer;"><i class="fa fa-close" style="color: #d20511;"></i></a>';
                    }
                ],
                // 'template' => '{view} {download} {delete}',
                'template' => '{view} {download}',
              ],
            ]
    ]) ?>
<?php //Pjax::end() ?>

    </div>
<?php } ?>