<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\search\ApplicantSearch;

/* @var $this yii\web\View */
/* @var $model backend\models\Applicant */

$this->title = $model->first_name ? $model->first_name: $model->email ;


$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$attributes = [
            'select_1717755396737',
            'first_name',
            'last_name',
            'mobile_number',
            'email',
            'textarea_1716885445830',
            'office_address',
            'passport_number',
            'date_of_birth',
            'select_1716885518762',
            'nationality',
            'sending_country',
[
                        "format" => "raw",
                        "attribute" => "file_1609222030883",
                        "value" => function($model) {
                            $dataProviderRows = backend\models\FileUpload::find()->where(["file_id"=>$model->file_1609222030883])->all();
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
                        
                    ],             'date_1716885690490',
            'date_1716885716345',
            'select_1716885772442',
            'date_1674644208007',
[
                        "format" => "raw",
                        "attribute" => "file_1716885886753",
                        "value" => function($model) {
                            $dataProviderRows = backend\models\FileUpload::find()->where(["file_id"=>$model->file_1716885886753])->all();
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
                        
                    ], [
                        "format" => "raw",
                        "attribute" => "file_1716885947331",
                        "value" => function($model) {
                            $dataProviderRows = backend\models\FileUpload::find()->where(["file_id"=>$model->file_1716885947331])->all();
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
                        
                    ], [
                        "format" => "raw",
                        "attribute" => "file_1716886041312",
                        "value" => function($model) {
                            $dataProviderRows = backend\models\FileUpload::find()->where(["file_id"=>$model->file_1716886041312])->all();
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
                        
                    ], [
                        "format" => "raw",
                        "attribute" => "file_1716886071776",
                        "value" => function($model) {
                            $dataProviderRows = backend\models\FileUpload::find()->where(["file_id"=>$model->file_1716886071776])->all();
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
                        
                    ],         ];
        //Removing the attribute 'select_1717159945674'(on dev) && ['select_1717755396737' (on live)] which is corresponding to Relationship select dropdown.
        if(!$model->parent_id){
            //UNCOMMENT BELOW LINE FOR DEV AND COMMENT FOR LIVE
            //$attributes = array_values(array_filter($attributes, fn($item) => $item !== 'select_1717159945674'));

            //COMMENT BELOW LINE FOR DEV AND UNCOMMENT FOR LIVE
            //$attributes = array_values(array_filter($attributes, fn($item) => $item !== 'select_1717755396737'));
        }

?>
<div class="applicant-view" style="margin-bottom:10px">

    <h1><?= Html::encode($this->title) ?></h1>
<!---->
<!--    <p>-->
<!--        --><!--Html::a(--><!--, ['update', --><!--], ['class' => 'btn btn-primary']) ?>-->
<!--        --><!--Html::a(--><!--, ['delete', --><!--], [-->
<!--            'class' => 'btn btn-danger',-->
<!--            'data' => [-->
<!--                'confirm' => --><!--,-->
<!--                'method' => 'post',-->
<!--            ],-->
<!--        ]) ?>-->
<!--    </p>-->

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $attributes,
    ]) ?>

</div>
<?php if(!$model->parent_id){?><div>
    <?php
    $searchModel = new ApplicantSearch();
    $dataProvider = $searchModel->search(['parent_id'=> $model->id]);
    echo Yii::$app->controller->renderPartial('index_dependent', [
    'searchModel' => $searchModel,
    'dataProvider' => $dataProvider,
    ]);
    ?>    
</div>
<?php } ?>