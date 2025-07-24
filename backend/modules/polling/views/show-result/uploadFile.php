<div class="col-md-12" style="padding-top: 60px;padding-bottom: 20px;">
    <!--    <h3 style="text-align: center"><strong>Question Type: </strong>--><?php //echo  $pollingQuizQuestion->pollingQuizQuestionType->name ?><!-- </h3>-->
    <div class="col-md-12">
        <form class="form-horizontal">
            <div class="form-group">
                <label class="control-label col-sm-2" for="email" style="font-size: 23px;">Question:</label>
                <div class="col-sm-10">
                    <p type="password" class="form-control" id="pwd" style="border: 0;font-size: 23px;"><?= $pollingQuizQuestion->question ?></p>
                </div>
            </div>
        </form>

    </div>
</div>
<div class="col-md-12">
    <div class="col-md-10" style="border: 1px solid #dddddd;
    background-color: rgba(221, 221, 221, 0.23);">
        <h4>Uploads by Users: </h4>
        <!--<ul class="fa-ul">
       <?php
        /*        foreach($pollingQuizQuestion->pollingQuizQuestionOptions as $option){
                    echo '<li><i class="fa-li fa fa-square"> </i>'.$option->value.'</li>';
                }
               $answerCorrect=returnAnswerMC($pollingQuizQuestion,$PollingQuizResultModel->correctAnswer);
               echo $answerCorrect;
               */?>
        </ul>-->
        <ul class="list-group">
            <?php
            if(!empty($pollingQuizQuestion->pollingQuizQuestionAnswers)){
                foreach( $pollingQuizQuestion->pollingQuizQuestionAnswers as $answer){
                    $uploads=\backend\models\FileUpload::find()->where('file_id=:file_id',[':file_id'=>$answer->answer])->all();
                    if(!empty($uploads)){
                        $attachmentArrayFinal=[];
                       foreach ($uploads as $upload){
                           $attachmentArray=[];
                           $attachmentArray['id']=$upload->id;
                           $attachmentArray['attachment']=$upload->attachment;
                           $attachmentArray['extension']=$upload->extension;
                           $attachmentArray['name']=$upload->name;
                           array_push($attachmentArrayFinal, $attachmentArray);
                       }
                        $html= \backend\widgets\attachmentGallery\AttachmentGallery::widget(
                            [
                                'label'=>'Attachments',
                                'attachmentArray' => $attachmentArrayFinal,
                                'module_id'=>$answer->id,
                                'cancel'=>true,
                                'uId'=>'attachment_file_1529059071527',
                                'cancel'=>false,
                                'style'=>'width:100px;box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);',
                                'imageButton'=>'function onClickImage(modelId,object){
                                                     handleImageClickEvent(modelId,object);
                                                    
                                             }',
                            ]
                        );
                        echo '<li class="list-group-item ">'.$html.'</li>';
                    }
                }
            }

            ?>
        </ul>
    </div>
</div>
<?php

?>