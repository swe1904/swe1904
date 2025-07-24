<?php
/* @var \backend\modules\messagesystem\models\MessageInbox $model */
?>

<?php
 if($model->sender_id==Yii::$app->user->id):
?>
     <div class="col-md-10 col-md-offset-1 pad_unset receiver-message message">
         <div class="header">

             <img class="avatar" src="<?=$model->sender->userProfile->getAvatar()?>">


             <div class="from">
                 <span><?=$model->sender->userProfile->getFullName()?></span>
                 <?=$model->sender->email?>
             </div>
             <div class="date"><span class="fa fa-paper-clip"></span><p><i class="fa fa-calendar"></i><?php echo Yii::$app->formatter->format( $model->created_at, 'relativeTime') ?></p></b></div>

             <div class="menu"></div>

         </div>

         <div class="contents">
             <blockquote>
                 <?=$model->message?>
             </blockquote>
             <div class="row margin_unset">
                 <div class="form-group">
                     <?php
                     $attachmentsArrayFinal=[];
                         foreach ($model->messageFileUploads as $attachment){
                             $attachmentsArray=[];
                             $attachmentsArray['id']=$attachment->id;
                             $attachmentsArray['attachment']=$attachment->attachment;
                             $attachmentsArray['extension']=$attachment->extension;
                             $attachmentsArray['name']=$attachment->name;
                             array_push($attachmentsArrayFinal,$attachmentsArray);
                         }
                         echo \backend\widgets\attachmentGallery\AttachmentGallery::widget(
                             [
                                 'label'=>'Attachments',
                                 'attachmentArray' => $attachmentsArrayFinal,
                                 'module_id'=>$model->id,
                                 'cancel'=>false,
                                 'imageButton'=>'function onClickImage(modelId,object){
                                             handleImageClickEvent(modelId,object);
                                            
                                     }',
                             ]
                         );
                     ?>
                 </div></div>
         </div>
     </div>
<?php
else:
?>
    <div class="col-md-10 col-md-offset-1 pad_unset sender-message message">
        <div class="header">

            <img class="avatar" src="<?=$model->sender->userProfile->getAvatar()?>">


            <div class="from">
                <span><?=$model->sender->userProfile->getFullName()?></span>
                <?=$model->sender->email?>
            </div>
            <div class="date"><span class="fa fa-paper-clip"></span> <p><i class="fa fa-calendar"></i><?php echo Yii::$app->formatter->format( $model->created_at, 'relativeTime') ?></p></b></div>

            <div class="menu"></div>

        </div>

        <div class="contents">
            <blockquote>
                <?=$model->message?>
            </blockquote>
        </div>
    </div>
<?php
endif;
?>
