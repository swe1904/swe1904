<?php
use backend\modules\polling\models\PollingQuizResultModel;
use backend\modules\polling\models\PollingQuizQuestion;
//echo Yii::$app->urlManager->baseUrl . "/modules/polling/app/components/angular-xeditable/js/xeditable.min.js";
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/show-result-wizard/jquery.steps.js", ['position' => \yii\web\View::POS_BEGIN]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/show-result-wizard/dimpleD3.js", ['position' => \yii\web\View::POS_BEGIN]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/show-result-wizard/dimple.js", ['position' => \yii\web\View::POS_BEGIN]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/show-result-wizard/bootstrap.js", ['position' => \yii\web\View::POS_BEGIN]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/show-result-wizard/bootstrap.min.js", ['position' => \yii\web\View::POS_BEGIN]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/show-result-wizard/jquery.snippet.min.js", ['position' => \yii\web\View::POS_BEGIN]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/show-result-wizard/jquery.easyWizard.js", ['position' => \yii\web\View::POS_BEGIN]);

$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/show-result-wizard/jquery.bootstrap.wizard.js", ['position' => \yii\web\View::POS_BEGIN]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/show-result-wizard/jquery.bootstrap.wizard.min.js", ['position' => \yii\web\View::POS_BEGIN]);

$this->registerCssFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/css/jquery.steps.css", ['position' => \yii\web\View::POS_HEAD]);
/**
 * Created by PhpStorm.
 * User: OWNER
 * Date: 07-10-2016
 * Time: 03:18 PM
 */
function returnAnswerString($answersArray,$pollingQuizQuestion){
    $answerStringArray=[];
    foreach($answersArray as $id){
        foreach($pollingQuizQuestion->pollingQuizQuestionOptions as $option){
            if($option->id==$id){
                array_push($answerStringArray,$option->value);
                break;
            }
        }
    }
    return implode(" , ",$answerStringArray);

}
function array_diff_custom($array1,$array2){
    $bool=true;
    if(count($array1)!=count($array2)){
        $bool=false;
    }else{
        foreach($array1 as $val){
            if(!in_array($val,$array2)){
                $bool=false;
                break;
            }
        }
    }
    return $bool;
}
function returnAnswerMC($pollingQuizQuestion,$id){
    foreach($pollingQuizQuestion->pollingQuizQuestionOptions as $option){
        if($option->id==$id){
            return $option->value;
            break;
        }
    }
    return null;
}
function returnMRAnswerCombination($pollingQuizQuestion,$PollingQuizResultModel){
    $finalAnswerMR=array();
    foreach($pollingQuizQuestion->pollingQuizQuestionOptions as $option){
       if(in_array($option->id,$PollingQuizResultModel->correctAnswerMR)){
           array_push($finalAnswerMR,array('correct'=>1,'answer'=>$option->value));
       }else{
           array_push($finalAnswerMR,array('correct'=>0,'answer'=>$option->value));
       }
    }
    return $finalAnswerMR;
}
function returnTeamName($pollingQuizTeams,$id){
    foreach($pollingQuizTeams as $team){
        if($team->id==$id){
            return $team->name;
        }
    }
    return null;
}
?>
<style>
    .title_size{
        font-size: 22px;

    }
    .middle_data{
        margin: 0 auto;
        display: table;
        position: relative;
    }
    .easyWizardButtons{
        clear: both;
        position: fixed;
        /* left: 221px; */
        right: 0;
        padding: 20px;
    }
    .prev{
        margin-right: 20px;

    }
    .star_color{
       color:  #dedddb;
        font-size: 20px;
    }
    .correct_star_rating{
        color: #09eb2f;
        font-size: 20px;
    }
    .wrong_star_rating{
        color: #eea236;;
        font-size: 20px;
    }
    .hide_it{
        display: none !important;
    }
</style>
<section id="demos">
    <div id="myWizard" class="form-horizontal">
        <?php
        $counter=0;
            foreach($pollingQuiz->pollingQuizQuestions as $pollingQuizQuestion) {
                $fileName="";
                if($pollingQuizQuestion->team_based==1){
                    $fileName="team_based/";
                }
                $PollingQuizResultModel = new PollingQuizResultModel($pollingQuizQuestion);
                $PollingQuizResultModel->setQuizData();
                ?>
                <section class="step quiz_step">
                <?php
                 if($PollingQuizResultModel->questionType==PollingQuizQuestion::RATING){
                    ?>
                    <?php
                     $id='container_'.$counter;
                     echo $this->render($fileName.'ratingData', [
                         'PollingQuizResultModel' => $PollingQuizResultModel,
                         'pollingQuizQuestion'=>$pollingQuizQuestion,
                         'pollingQuizTeams'=>$pollingQuiz->pollingQuizTeams,
                         'id'=>$id
                     ]);

                  }
                elseif($PollingQuizResultModel->questionType==PollingQuizQuestion::TRUE_FALSE){
                    ?>
                    <?php
                    $id='container_'.$counter;
                    echo $this->render($fileName.'trueFalseData', [
                        'PollingQuizResultModel' => $PollingQuizResultModel,
                        'pollingQuizQuestion'=>$pollingQuizQuestion,
                        'pollingQuizTeams'=>$pollingQuiz->pollingQuizTeams,
                        'id'=>$id
                    ]);

                }
                 elseif($PollingQuizResultModel->questionType==PollingQuizQuestion::SHOW_NUMBER){
                     ?>
                     <?php
                     $id='container_'.$counter;
                     echo $this->render($fileName.'shortAnswerData', [
                         'PollingQuizResultModel' => $PollingQuizResultModel,
                         'pollingQuizQuestion'=>$pollingQuizQuestion,
                         'pollingQuizTeams'=>$pollingQuiz->pollingQuizTeams,
                         'id'=>$id
                     ]);

                 }
                 elseif($PollingQuizResultModel->questionType==PollingQuizQuestion::NUMBER){
                     ?>
                     <?php
                     $id='container_'.$counter;
                     echo $this->render($fileName.'numberData', [
                         'PollingQuizResultModel' => $PollingQuizResultModel,
                         'pollingQuizQuestion'=>$pollingQuizQuestion,
                         'pollingQuizTeams'=>$pollingQuiz->pollingQuizTeams,
                         'id'=>$id
                     ]);

                 }elseif($PollingQuizResultModel->questionType==PollingQuizQuestion::MULTIPLE_CHOICE_QUESTION){
                     ?>
                     <?php
                     $id='container_'.$counter;
                     echo $this->render($fileName.'multipleChoiceData', [
                         'PollingQuizResultModel' => $PollingQuizResultModel,
                         'pollingQuizQuestion'=>$pollingQuizQuestion,
                         'pollingQuizTeams'=>$pollingQuiz->pollingQuizTeams,
                         'id'=>$id
                     ]);


                 }elseif($PollingQuizResultModel->questionType==PollingQuizQuestion::MULTIPLE_RESPONSE){
                     ?>
                     <?php
                     $id='container_'.$counter;
                     echo $this->render($fileName.'multipleResponseData', [
                         'PollingQuizResultModel' => $PollingQuizResultModel,
                         'pollingQuizQuestion'=>$pollingQuizQuestion,
                         'pollingQuizTeams'=>$pollingQuiz->pollingQuizTeams,
                         'id'=>$id
                     ]);


                 }
                ?>
                    </section>
                    <?php
                $counter++;
                 }
                ?>
    </div>
</section>
<script>
    $( document ).ready(function() {
        $('#navbar').affix({
            offset: {
                top: 200
            }
        });

        $("pre.html").snippet("html", {style:'matlab'});
        $("pre.css").snippet("css", {style:'matlab'});
        $("pre.javascript").snippet("javascript", {style:'matlab'});

        $('#myWizard').easyWizard({
            buttonsClass: 'btn',
            submitButtonClass: 'btn btn-info'
        });
        $('#myWizard').easyWizard('goToStep', '<?= $stepKey ?>');
        $('#myWizard2').easyWizard({
            buttonsClass: 'btn',
            submitButtonClass: 'btn btn-info',
            before: function(wizardObj, currentStepObj, nextStepObj) {
                alert('Hello, I\'am the before callback');
            },
            after: function(wizardObj, prevStepObj, currentStepObj) {
                alert('Hello, I\'am the after callback');
            },
            beforeSubmit: function(wizardObj) {
                alert('Hello, I\'am the beforeSubmit callback');
            }
        });

        $('#myWizard3').easyWizard({
            showSteps: false,
            showButtons: false,
            submitButton: false
        });
        $('#myWizard3Pager .previous a').bind('click', function(e) {
            e.preventDefault();
            $('#myWizard3').easyWizard('prevStep');
        });
        $('#myWizard3Pager .page a').bind('click', function(e) {
            e.preventDefault();
            $('#myWizard3').easyWizard('goToStep', $(this).attr('rel'));
        });
        $('#myWizard3Pager .next a').bind('click', function(e) {
            e.preventDefault();
            $('#myWizard3').easyWizard('nextStep');
        });
    });
    var width = $(window).width();
    console.log(width);
    window.onresize = function () {
        $("section.quiz_step").each(function(index, element){

            var width = $(window).width();
            $(element).css('width',width+"px");
            console.log(width);
        })
    };
</script>
<style>
    .easyWizardSteps{
        display: none;
    }
    .step{
        min-height: 400px;
    }
</style>
