<div class="col-md-12">
<!--    <h3 style="text-align: center"><strong>Question Type: </strong>--><?php //echo  $pollingQuizQuestion->pollingQuizQuestionType->name ?><!-- </h3>-->
    <div class="row">
        <form class="form-horizontal">
            <div class="form-group form_m">
                <label class="col-sm-3" for="email" style="font-size: 23px;">Question :</label>
                <div class="clearfix">
                </div>
                <div class="col-sm-12">
                    <p type="password" id="pwd" style="border: 0;font-size: 23px;"><?= $pollingQuizQuestion->question ?></p>
                </div>
            </div>

            <div class="form-group form_m">
                <label class="col-sm-3" style="font-size: 23px;" for="pwd">Maximum :</label>
                <div class="clearfix">
                </div>
                <div class="col-sm-9">
                    <p type="password" id="pwd" style="border: 0;font-size: 23px;">
                        <?php
                        if(!empty($pollingQuizQuestion->pollingQuizQuestionOptions)){
                            $maxVal=(int)$pollingQuizQuestion->pollingQuizQuestionOptions[0]->value;
                            for($i=0;$i<$maxVal;$i++){
                                echo '<span class="glyphicon glyphicon-star star_color" aria-hidden="true"></span>';
                            }
                        }
                        ?>
                    </p>
                </div>
            </div>
            <?php
            if(isset($pollingQuizQuestion->is_correct)):
            if($pollingQuizQuestion->is_correct=='1'):?>
            <!--<div class="form-group form_m">

                <label class="col-sm-3" for="pwd" style="font-size: 23px;">Correct : </label>

                <div class="col-sm-12">
                    <p type="password" id="pwd" style="border: 0;font-size: 23px;">
                        <?php
/*                        if(!empty($PollingQuizResultModel->correctAnswer)){
                            $maxVal=(int)$PollingQuizResultModel->correctAnswer;
                            for($i=0;$i<$maxVal;$i++){
                                echo '<span class="glyphicon glyphicon-star correct_star_rating" aria-hidden="true"></span>';
                            }
                        }
                        */?>
                    </p>
                </div>
            </div>-->
            <?php endif;
            endif; ?>
        </form>

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