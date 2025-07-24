<div class="col-md-12">
    <h3 style="text-align: center"><strong>Question Type: </strong><?= $pollingQuizQuestion->pollingQuizQuestionType->name ?> </h3>
    <div class="col-md-6">
        <form class="form-horizontal">
            <div class="form-group">
                <label class="control-label col-sm-2" for="email">Question title:</label>
                <div class="col-sm-10">
                    <p type="password" class="form-control" id="pwd" style="border: 0"><?= $pollingQuizQuestion->question ?></p>
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
        <div class='middle_data_multiple' id='<?php echo $id ?>'>
        </div>
    </div>
</div>

<script>
    function graphMultipleChoice(answersWithPercArray,correctAnswer,fixedChoices){
        var id='<?php echo $id ?>';
        var finalData=[];
        for(var i in fixedChoices){
            finalData.push({Choice :fixedChoices[i],Users:answersWithPercArray[fixedChoices[i]]});
        }

        var svg = dimple.newSvg("#"+id, 590, 400);
        var myChart = new dimple.chart(svg, finalData);
        //myChart.setBounds(20, 20, 460, 360)
        myChart.addMeasureAxis("p", "Users");
        myChart.addSeries("Choice", dimple.plot.pie);
        myChart.addLegend(500, 20, 90, 300, "left");
        myChart.setMargins("10%", "10%", "10%", "10%");
        myChart.draw();
        //alert(correctAnswer);
        console.log(answersWithPercArray);
    }
</script>
<?php
$finalAnswersByUsersArray=[];
$questionOptions=[];
$fixedChoices=[];
//echo json_encode(array_count_values($PollingQuizResultModel->answerByUsers));
if(!empty($PollingQuizResultModel->answerByUsers)){
    if(!empty($pollingQuizQuestion->pollingQuizQuestionOptions)){
        foreach($pollingQuizQuestion->pollingQuizQuestionOptions as $option){
            $questionOptions[$option->id]=$option->value;
            array_push($fixedChoices,$option->value);
            //array_push($questionOptions,$option->value);
        }
        foreach($PollingQuizResultModel->answerByUsers as $answers){
            array_push($finalAnswersByUsersArray,$questionOptions[$answers]);
        }
         // echo json_encode($fixedChoices);
    }
    $vals=array_count_values($finalAnswersByUsersArray);
    $correctAnswer=$questionOptions[$PollingQuizResultModel->correctAnswer];
    //print_r($vals);
    ?>
    <script>
        var graphArray = <?php echo json_encode($vals) ?>;
        var correctAnswer= '<?php echo $correctAnswer; ?>';
        var fixedChoices= <?php echo json_encode($fixedChoices); ?>;
        graphMultipleChoice(graphArray,correctAnswer,fixedChoices);
    </script>
    <?php
    //echo $vals["true"];
}

?>
