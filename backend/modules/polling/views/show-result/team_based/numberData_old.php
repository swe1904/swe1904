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
        <div class="form-group">
            <label class="control-label col-sm-2" for="email">Correct Answer: </label>
            <div class="col-sm-10">
                <p type="password" class="form-control" id="pwd" style="border: 0"><?= $PollingQuizResultModel->correctAnswer ?></p>
            </div>
        </div>
</form>
    </div>
</div>
<div class="col-md-12">
    <div class='col-md-12' id='<?php echo $id ?>' style="height: 500px;"></div>
</div>
<script>
    function graphRating2(answersWithPercArray){
        var id='<?php echo $id ?>';
        console.log(answersWithPercArray);
        var svg = dimple.newSvg("#"+id, '100%', '100%');
        var axisData={'User percentage':null,"Ratings":null};
        var finalData=[];
        for(var i in answersWithPercArray){
            //alert(answersWithPercArray[i]+"      "+i);
            finalData.push({'User percentage':answersWithPercArray[i]['correct_ans_perc'],
                "Team":answersWithPercArray[i]['team_id'],
                "No. of users appeared in this Team":answersWithPercArray[i]['user_appeared'],
                "No. of users given correct answer":answersWithPercArray[i]['correct_answer']});
        }
        //console.log(finalData);
        var myChart = new dimple.chart(svg, finalData);
        //myChart.setBounds(60, 30, 510, 305)
        var x = myChart.addCategoryAxis("x", "Team");
        /*x.tickFormat = ',.1f';addCategoryAxis
         x.addOrderRule("Order");*/
        x.addOrderRule("Ratings");
        var y = myChart.addMeasureAxis("y", "User percentage");
        y.overrideMax = 100;
        myChart.setMargins("10%", "10%", "10%", "10%");
        myChart.addSeries(['Team','No. of users appeared in this Team','No. of users given correct answer'], dimple.plot.bar);
        myChart.draw();
        /*window.onresize = function () {
            // As of 1.1.0 the second parameter here allows you to draw
            // without reprocessing data.  This saves a lot on performance
            // when you know the data won't have changed.
            console.log("chart draw");
            myChart.draw(0, true);
        };*/
        console.log(answersWithPercArray[3]);
    }
</script>
<?php
$teamArray=[];
$finalTeamArray=[];
foreach($PollingQuizResultModel->teamAnswerArray as $answerArray){
    $teamArray[$answerArray->polling_quiz_team_id][]=$answerArray;
    //echo $answerArray->polling_quiz_team_id."</br>";
}
foreach($teamArray as $teamId=>$answers){
    $totalUsers=count($answers);
    $correctAnswer=0;
    foreach($answers as $answer){
        if($answer->answer==$PollingQuizResultModel->correctAnswer){
            $correctAnswer++;
        }
    }
    $precentage=0;
    if($correctAnswer!=0){
        $precentage=($correctAnswer/$totalUsers)*100;
    }
    $finalTeamArray[]=['team_id'=>returnTeamName($pollingQuizTeams,$teamId),'correct_ans_perc'=>$precentage,'user_appeared'=>$totalUsers,'correct_answer'=>$correctAnswer];
}

?>
<script>
    var answersWithPercArray = <?php echo json_encode($finalTeamArray) ?>;
    graphRating2(answersWithPercArray);
</script>
