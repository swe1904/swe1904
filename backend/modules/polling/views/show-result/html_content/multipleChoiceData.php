<div class="col-md-12" style="padding-top: 60px;padding-bottom: 20px;">
<!--    <h3 style="text-align: center"><strong>Question Type: </strong>--><?php //echo $pollingQuizQuestion->pollingQuizQuestionType->name ?><!-- </h3>-->
    <div class="col-md-12">
        <form class="form-horizontal">
            <div class="form-group form_m">
                <label class="control-label col-sm-3" style="font-size: 23px;" for="email">Question:</label>
                <div class="col-sm-9">
                    <p type="password" class="form-control" id="pwd" style="border: 0;font-size: 23px;"><?= $pollingQuizQuestion->question ?></p>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="col-md-12">
    <div class="col-md-4" style="border: 1px solid #dddddd;
    background-color: rgba(221, 221, 221, 0.23);">
        <h4>Options given: </h4>
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
            foreach($pollingQuizQuestion->pollingQuizQuestionOptions as $option){
                if($PollingQuizResultModel->correctAnswer==$option->id){
                    echo '<li class="list-group-item list-group-item-success">'.$option->value.'</li>';
                }else{
                    echo '<li class="list-group-item ">'.$option->value.'</li>';
                }

            }
            ?>
        </ul>
    </div>
    <div class="col-md-8">
        <canvas class="canvas_m" id='<?php echo $id ?>'></canvas>
    </div>
    <div class="col-md-12">
        <div class="col-md-10" style="border: 1px solid #dddddd;
    background-color: rgba(221, 221, 221, 0.23);">
            <h4>Answers by Users: </h4>
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
                        echo '<li class="list-group-item ">'.$answer->answer.'</li>';
                    }
                }

                ?>
            </ul>
        </div>
    </div>

</div>
