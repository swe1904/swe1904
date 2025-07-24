<?php
$teamArray=[];
$finalTeamArray=[];
foreach($PollingQuizResultModel->teamAnswerArray as $answerArray){
    $teamArray[$answerArray->polling_quiz_team_id][]=$answerArray;
    //echo $answerArray->polling_quiz_team_id."</br>";
}
?>
<div class="col-md-12" style="padding-top: 60px;padding-bottom: 20px;">
<!--    <h3 style="text-align: center"><strong>Question Type: </strong>--><?//= $pollingQuizQuestion->pollingQuizQuestionType->name ?><!-- </h3>-->
    <div class="col-md-12">
        <form class="form-horizontal">
            <div class="form-group">
                <label class="control-label col-sm-2" style="font-size: 23px;" for="email">Question:</label>
                <div class="col-sm-10">
                    <p type="password" class="form-control" id="pwd" style="font-size: 23px;border: 0"><?= $pollingQuizQuestion->question ?></p>
                </div>
            </div>
        </form>

    </div>
</div>
<div class="col-md-12">
    <div class="col-md-10" style="border: 1px solid #dddddd;
    background-color: rgba(221, 221, 221, 0.23);">
        <h4>Answers by Users: </h4>

       <?php
         foreach($teamArray as $teamId=>$answers){
             ?>
            <div class="col-md-12">
                <strong>Answers by team: <?= returnTeamName($pollingQuizTeams,$teamId) ?></strong>
                <div class="col-md-12">
                    <ul class="list-group">
                        <?php
                        if(!empty($answers)){
                            foreach( $answers as $answer){
                                echo '<li class="list-group-item ">'.$answer->answer.'</li>';
                            }
                        }

                        ?>
                    </ul>
                </div>
            </div>
             <?php
         }
       ?>

    </div>
</div>

