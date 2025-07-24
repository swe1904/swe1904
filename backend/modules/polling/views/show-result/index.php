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

$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/show-result-wizard/chart/Chart.bundle.js", ['position' => \yii\web\View::POS_BEGIN]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/show-result-wizard/chart/Chart.bundle.min.js", ['position' => \yii\web\View::POS_BEGIN]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/show-result-wizard/chart/Chart.js", ['position' => \yii\web\View::POS_BEGIN]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/show-result-wizard/chart/Chart.min.js", ['position' => \yii\web\View::POS_BEGIN]);

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
    .nav_buttons{
        position: fixed;
        width: auto;
        top: 0;
        right: 0;
    }

</style>
<script>
    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++ ) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }
    function returnColor(){
        var color=[
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 64, 0.2)',
                'rgba(45, 159, 64, 0.2)',
                'rgba(255, 121, 64, 0.2)',
                'rgba(255, 159, 45, 0.2)',
                'rgba(122, 38, 64, 0.2)',
                'rgba(23, 159, 64, 0.2)'
        ];
    }
</script>
<div class="row">
    <div class="col-md-4 col-lg-4 col-sm-4">
        <ul class="pager wizard nav_buttons" style="left: 0; position: unset !important; text-align: left;">
            <?php
            if($pollingQuiz->show_btn_on_result_page){
                ?>
                <li class=""><a href="<?= Yii::$app->urlManager->createUrl('polling/show-result/index?id='.$pollingQuiz->polling_id)?>">Refresh</a></li>
                <li class="clear-answer" data-url="<?= Yii::$app->urlManager->createUrl('polling/show-result/clear-answer?id='.$pollingQuiz->polling_id)?>"><a href="#">Clear Answers</a></li>
            <?php } ?>
        </ul>
    </div>
</div>
<div class='container'>

    <section id="wizard">

        <div id="rootwizard">
            <div class="navbar" style="position: absolute;top: -1000px;">
                <div class="navbar-inner">
                    <div class="container">
                        <ul>
                            <?php
                            foreach($pollingQuiz->pollingQuizQuestions as $index=>$pollingQuizQuestion) {
                                $tab=$index+1;
                                echo '<li><a id="a" href="#tab'.$tab.'" data-toggle="tab">First</a></li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <?php
                $counter=0;
                foreach($pollingQuiz->pollingQuizQuestions as $key=>$pollingQuizQuestion) {
                    $tabId="tab".($key+1);
                    //echo $key;
                    $fileName="";
                    if($pollingQuizQuestion->team_based==1){
                        $fileName="team_based/";
                    }
                    $PollingQuizResultModel = new PollingQuizResultModel($pollingQuizQuestion);
                    $PollingQuizResultModel->setQuizData();
                    ?>
                    <div class="tab-pane quiz_step" id='<?php echo $tabId; ?>'>
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


                        }elseif($PollingQuizResultModel->questionType==PollingQuizQuestion::UPLOAD_FILE){
                            ?>
                            <?php
                            $id='container_'.$counter;
                            echo $this->render($fileName.'uploadFile', [
                                'PollingQuizResultModel' => $PollingQuizResultModel,
                                'pollingQuizQuestion'=>$pollingQuizQuestion,
                                'pollingQuizTeams'=>$pollingQuiz->pollingQuizTeams,
                                'id'=>$id
                            ]);


                        }
                        ?>
                    </div>
                    <?php
                    $counter++;
                }
                ?>
                <ul class="pager wizard nav_buttons">
                    <li class="previous first" style="display:none;"><a href="#">First</a></li>
                    <li class="previous"><a href="#">Previous</a></li>
                    <li class="next last" style="display:none;"><a href="#">Last</a></li>
                    <li class="next"><a href="#">Next</a></li>
                </ul>
            </div>
        </div>
    </section>
</div>
<script>

    $(document).ready(function() {

        $('#rootwizard').bootstrapWizard();
        $(".navbar-inner li").each(function(index,element){
            if((index+1)==parseInt('<?= $stepKey ?>')){
                $(element).find('a').click();
            }

        });

        $(".clear-answer").click(function(e){
            e.preventDefault();
            var url = $(this).data("url");
            $.ajax({
                "type": "GET",
                "url": url,
                "success": function (data) {
                    console.log(data);
                    if (data.code == 1) {
                        alert(data.message);
                    }
                    else if (data.code == 0) {
                        alert(data.message);
                    }
                }
            });
            return false;
        })

    });

</script>
<style>
    .easyWizardSteps{
        display: none;
    }
    .step{
        min-height: 400px;
    }
    .chart-legend li span{
        display: inline-block;
        width: 12px;
        height: 12px;
        margin-right: 5px;
    }
    .canvas_m{
        width: 100% !important;
    }
    .form_m{
        margin: 0;
    }
</style>
