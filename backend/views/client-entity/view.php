<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\ClientEntity $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Client Entities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="client-entity-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('backend', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'client_id',
                'label' => 'Client',
                'value' => $model->client->client_name,
            ],
            'name',
            'address',
            'country',
            'email',
            'phone',
            'company_vat',
            [
                "format" => "raw",
                "attribute" => "additional_attachments",
                "value" => function($model) {
                    $dataProviderRows = backend\models\FileUpload::find()->where(["file_id"=>$model->additional_attachments])->all();
                    $attachmentsArrayFinalfile_1609222030883=[];

                foreach ($dataProviderRows as $upload){
                $attachmentsArrayfile_1609222030883=[];
                $attachmentsArrayfile_1609222030883["id"]=$upload->id;
                $attachmentsArrayfile_1609222030883["attachment"]=$upload->attachment;
                    $attachmentsArrayfile_1609222030883["extension"]=$upload->extension;
                    $attachmentsArrayfile_1609222030883["name"]=$upload->name;
                    array_push($attachmentsArrayFinalfile_1609222030883, $attachmentsArrayfile_1609222030883);
                }

                return \backend\widgets\attachmentGallery\AttachmentGallery::widget(
                    [
                        // "label"=> false,
                        "attachmentArray" => $attachmentsArrayFinalfile_1609222030883,
                        "module_id"=>$model->id,
                        "cancel"=>true,
                        "uId"=>"attachment_file_1609222030883",
                        "cancelButton"=>"function onClickCancel(modelId,objectId,moduleId){
                                                    console.log('Test click');
                                                    
                                            }",
                        "imageButton"=>"function onClickImage(modelId,object){
                                                    handleImageClickEvent(modelId,object);
                                                    
                                            }",
                    ]
                );
                                }
                
            ],

            // 'cr_number',
            // 'unified_national_number',
        ],
    ]) ?>

</div>
