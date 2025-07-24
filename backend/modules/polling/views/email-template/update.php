<?php

use backend\modules\handyrecruiter\models\EmailAttachment;
use backend\widgets\attachmentGallery\AttachmentGallery;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\modules\polling\models\EmailTemplate */

$this->title = Yii::t('app', 'Update {modelClass} ', [
    'modelClass' => 'Email Template',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Email Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="email-template-update">

<!--    <h3 style="text-align:center;color: #fc7d07a6">--><?php //echo Html::encode($this->title) ?><!--</h3>-->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default card-view panel-refresh">
            <div class="panel-hading">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
            </div>
        </div>
    </div>
</div>
    

</div>
<?php \yii\widgets\Pjax::begin(['id' => "attachment-grid"]); ?>
<div class="col-md-12">

<?php

/*$attachments = $attachmentModel;

     foreach ($attachments as $attachment) {
         $extension = pathinfo(parse_url($attachment["image"], PHP_URL_PATH), PATHINFO_EXTENSION);
         if ($extension == 'jpg' || $extension == 'png' || $extension == 'gif' || $extension == 'tif' || $extension == 'jpeg') {
             echo Html::a(Html::img($attachment["image"], ['class' => 'tempattach', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-title' => '']), $attachment["image"], [
                 'target' => '_blank',
                 'data-pjax' => '0',
             ]);
             $url = Url::to(['email-template/delete-attachment', 'id' => $attachment->id]);
             echo Html::a('<i class="fa fa-times cross-blue" ></i>', '#', [
                 'class' => 'show-on-image',
                 'title' => Yii::t('yii', 'Delete Attachment'),
                 'onclick' => "
                                    if (confirm('Are you sure want to delete?')) {
                                        $.ajax('$url', {
                                            type: 'POST'
                                        }).done(function(data) {
                                         
                                        });
                                    }
                                    return false;
                                ",
             ]);
         }
         elseif ($extension == 'pdf') {
             echo Html::a(Html::img(Yii::getAlias('@storageUrl' . '/source/pdf.png'), ['class' => 'tempattach', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-title' => '']), $attachment["image"], [
                 'target' => '_blank',
                 'data-pjax' => '0',
             ]);
             $url = Url::to(['email-template/delete-attachment', 'id' => $attachment->id]);
             echo Html::a('<i class="fa fa-times cross-blue" ></i>', '#', [
                 'class' => 'show-on-image',
                 'title' => Yii::t('yii', 'Delete Attachment'),
                 'onclick' => "
                                    if (confirm('Are you sure want to delete?')) {
                                        $.ajax('$url', {
                                            type: 'POST'
                                        }).done(function(data) {
                                         
                                        });
                                    }
                                    return false;
                                ",
             ]);
         }
         elseif ($extension == 'docx') {
             echo Html::a(Html::img(Yii::getAlias('@storageUrl' . '/source/word.png'),['class'=>'tempattach','data-toggle'=>'tooltip','data-placement'=>'top','data-title'=>'']), $attachment["image"], [
                 'target' => '_blank',
                 'data-pjax' => '0',
             ]);
             $url = Url::to(['module-attachment/delete', 'id' => $attachment->id]);
             echo Html::a('<i class="fa fa-times cross-blue" ></i>', '#', [
                 'class' => 'show-on-image hover',
                 'title' => Yii::t('yii', 'Delete Attachment'),
                 'onclick' => "
                                    if (confirm('Are you sure want to delete?')) {
                                        $.ajax('$url', {
                                            type: 'POST'
                                        }).done(function(data) {
                                       
                                        });
                                    }
                                    return false;
                                ",
             ]);
         } elseif ($extension == 'xlsx') {
             echo Html::a(Html::img(Yii::getAlias('@storageUrl' . '/source/Excel.png'),['class'=>'tempattach','data-toggle'=>'tooltip','data-placement'=>'top','data-title'=>'']), $attachment["image"], [
                 'target' => '_blank',
                 'data-pjax' => '0',
             ]);
             $url = Url::to(['email-template/delete-attachment', 'id' => $attachment->id]);
             echo Html::a('<i class="fa fa-times cross-blue" ></i>', '#', [
                 'class' => 'show-on-image',
                 'title' => Yii::t('yii', 'Delete Attachment'),
                 'onclick' => "
                                    if (confirm('Are you sure want to delete?')) {
                                        $.ajax('$url', {
                                            type: 'POST'
                                        }).done(function(data) {
                                    
                                        });
                                    }
                                    return false;
                                ",
             ]);
         } elseif ($extension == 'sql') {
             echo Html::a(Html::img(Yii::getAlias('@storageUrl' . '/source/sql.png'),['class'=>'tempattach','data-toggle'=>'tooltip','data-placement'=>'top','data-title'=>'']), $attachment["image"], [
                 'target' => '_blank',
                 'data-pjax' => '0',
             ]);
             $url = Url::to(['email-template/delete-attachment', 'id' => 'Delete Attachment']);
             echo Html::a('<i class="fa fa-times cross-blue" ></i>', '#', [
                 'class' => 'show-on-image',
                 'title' => Yii::t('yii', 'Delete Attachment'),
                 'onclick' => "
                                    if (confirm('Are you sure want to delete?')) {
                                        $.ajax('$url', {
                                            type: 'POST'
                                        }).done(function(data) {
                                    
                                        });
                                    }
                                    return false;
                                ",
             ]);
         } elseif ($extension == 'bmpr') {
             echo Html::a(Html::img(Yii::getAlias('@storageUrl' . '/source/balsamiq.png'),['class'=>'tempattach','data-toggle'=>'tooltip','data-placement'=>'top','data-title'=>'']), $attachment["image"], [
                 'target' => '_blank',
                 'data-pjax' => '0',
             ]);
             $url = Url::to(['email-template/delete-attachment', 'id' => 'Delete Attachment']);
             echo Html::a('<i class="fa fa-times cross-blue" ></i>', '#', [
                 'class' => 'show-on-image',
                 'title' => Yii::t('yii', 'Delete Attachment'),
                 'onclick' => "
                                    if (confirm('Are you sure want to delete?')) {
                                        $.ajax('$url', {
                                            type: 'POST'
                                        }).done(function(data) {
                                       
                                        });
                                    }
                                    return false;
                                ",
             ]);
         } elseif ($extension == 'csv') {
             echo Html::a(Html::img(Yii::getAlias('@storageUrl' . '/source/csv.png'),['class'=>'tempattach','data-toggle'=>'tooltip','data-placement'=>'top','data-title'=>'']), $attachment["image"], [
                 'target' => '_blank',
                 'data-pjax' => '0',
             ]);
             $url = Url::to(['email-template/delete-attachment', 'id' => $attachment->id]);
             echo Html::a('<i class="fa fa-times cross-blue" ></i>', '#', [
                 'class' => 'show-on-image',
                 'title' => Yii::t('yii', 'Delete Attachment'),
                 'onclick' => "
                                    if (confirm('Are you sure want to delete?')) {
                                        $.ajax('$url', {
                                            type: 'POST'
                                        }).done(function(data) {
                                    
                                        });
                                    }
                                    return false;
                                ",
             ]);
         }
         else{
             echo Html::a(Html::img(Yii::getAlias('@storageUrl' . '/source/unknown.png'),['class'=>'tempattach','data-toggle'=>'tooltip','data-placement'=>'top','data-title'=>'']), $attachment["image"], [
                 'target' => '_blank',
                 'data-pjax' => '0',
             ]);
             $url = Url::to(['email-template/delete-attachment', 'id' => $attachment->id]);
             echo Html::a('<i class="fa fa-times cross-blue" ></i>', '#', [
                 'class' => 'show-on-image',
                 'title' => Yii::t('yii', 'Delete Attachment'),
                 'onclick' => "
                                    if (confirm('Are you sure want to delete?')) {
                                        $.ajax('$url', {
                                            type: 'POST'
                                        }).done(function(data) {
                                       
                                        });
                                    }
                                    return false;
                                ",
             ]);
         }

     }*/

?>
</div>
<?php \yii\widgets\Pjax::begin(['id' => "personal_info"]); ?>
<br>
<br>
<style>
    .tempattach{
        width: 100px;
        height: 100px;
        margin-right: 15px;}
    .show-on-image {
        display: inline-block;
        position: relative;
        margin-left: -30px;
        font-size: 17px;
    }
</style>
