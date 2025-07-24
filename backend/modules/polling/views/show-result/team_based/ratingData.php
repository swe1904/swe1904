
<?php
echo $this->render('../html_content/ratingData', [
    'PollingQuizResultModel' => $PollingQuizResultModel,
    'pollingQuizQuestion'=>$pollingQuizQuestion,
]);
?>
<div class="col-md-12" style="width: 75%">
    <canvas class="canvas_m" id='<?php echo $id ?>'></canvas>
</div>
<script>
    function graphRating2(answersWithPercArray){
       // Chart.defaults.global.legend.display = false;
        var id='<?php echo $id ?>';
        console.log(answersWithPercArray);
        var svg = dimple.newSvg("#"+id, '100%', '100%');
        var axisData={'User percentage':null,"Ratings":null};
        var finalData=[];
        for(var i in answersWithPercArray){
            //alert(answersWithPercArray[i]+"      "+i);
            finalData.push({'user_perc':answersWithPercArray[i]['correct_ans_perc'],
                            "team_name":answersWithPercArray[i]['team_id'],
                            "num_user":answersWithPercArray[i]['user_appeared'],
                            "num_correct_ans":answersWithPercArray[i]['correct_answer']});
        }
        console.log("rate data")
        console.log(finalData);

        var bridgeDataPoints = [];
        var backGroundColor = [];
        var x_axis_data = [];
        function DataPoint(id, user_perc, team_name, num_user, num_correct_ans) {
            this.id = String(team_name);
            this.user_perc = user_perc;
            this.team_name = team_name;
            this.num_user = num_user;
            this.num_correct_ans=num_correct_ans;
        }
        DataPoint.prototype.toString = function () {
            return this.id;
        };
        for(var i in finalData){
            var dataPoint = new DataPoint(i,
                                           finalData[i].user_perc,
                                           finalData[i].team_name,
                                           finalData[i].num_user,
                                           finalData[i].num_correct_ans);
            x_axis_data.push(finalData[i].user_perc);
            bridgeDataPoints.push(dataPoint);
            backGroundColor.push(getRandomColor());
        }
        console.log("rrrrrrr");
        //console.log(getRandomColor());
        var data = {
            labels: bridgeDataPoints,
            datasets: [
                {
                    backgroundColor: backGroundColor,
                    borderWidth: 1,
                    data: x_axis_data
                }
            ]
        };
        // Any of the following formats may be used
        var ctx = document.getElementById(id);
        var ctx = document.getElementById(id).getContext("2d");
        var ctx = $("#"+id);
        var myBarChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            max: 100,
                            min: 0,
                            stepSize: 10
                        }
                    }]
                },
                legend:{
                    display:false
                },
                responsive : true,
                tooltips : {

                    callbacks : { // HERE YOU CUSTOMIZE THE LABELS
                        title : function() {
                           // return '';
                        },
                        beforeLabel : function(tooltipItem, data) {
                            console.log(tooltipItem.index);
                            console.log(data.labels[tooltipItem.index]);
                            var teamName="Team: "+data.labels[tooltipItem.index].team_name;
                            var numUser="No. of users appeared in this team: "+data.labels[tooltipItem.index].num_user;
                            var numCorrectAns="No. of users given correct answer: "+data.labels[tooltipItem.index].num_correct_ans;
                            var userPerc="User percentage: "+data.labels[tooltipItem.index].user_perc;
                            /*'Month ' + ': ' + data.labels[tooltipItem.index].tooltip;*/
                            return  [teamName,numUser,numCorrectAns,userPerc];
                        },
                        label : function(tooltipItem, data) {
                            //return data.datasets[tooltipItem.datasetIndex].label + ': ' + tooltipItem.yLabel;
                        },
                        afterLabel : function(tooltipItem, data) {
                            //return '';
                        },
                    }

                },
            }
        });
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
        $precentage=(int)$precentage;
    }
    $finalTeamArray[]=['team_id'=>returnTeamName($pollingQuizTeams,$teamId),'correct_ans_perc'=>$precentage,'user_appeared'=>$totalUsers,'correct_answer'=>$correctAnswer];
}

?>
<div class="col-md-12 is_correct hide_it">
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
                                echo '<li class="list-group-item ">';
                                $color_css='wrong_star_rating';
                                if($answer->answer==$PollingQuizResultModel->correctAnswer)
                                    $color_css='correct_star_rating';
                                 if(!empty($pollingQuizQuestion->pollingQuizQuestionOptions)){
                                     $maxVal=(int)$pollingQuizQuestion->pollingQuizQuestionOptions[0]->value;
                                     for($i=1;$i<=$maxVal;$i++){
                                         if($i<=$answer->answer){
                                             echo '<span class="glyphicon glyphicon-star star_color '. $color_css.'" aria-hidden="true"></span>';
                                         }else{
                                             echo '<span class="glyphicon glyphicon-star star_color" aria-hidden="true"></span>';
                                         }

                                     }
                                 }
                                echo '</li>';
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
<script>
    var is_correct=parseInt('<?= $pollingQuizQuestion->is_correct ?>');
    var answersWithPercArray = <?php echo json_encode($finalTeamArray) ?>;
    if(is_correct==1){
        $('.is_correct').removeClass('hide_it');
    }else{
        $('.is_correct').addClass('hide_it');
        graphRating2(answersWithPercArray);
    }

</script>
