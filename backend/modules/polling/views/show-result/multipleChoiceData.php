
<?php
echo $this->render('html_content/multipleChoiceData', [
    'PollingQuizResultModel' => $PollingQuizResultModel,
    'pollingQuizQuestion'=>$pollingQuizQuestion,
    'id'=>$id
]);
?>
<script>
    function graphMultipleChoice(answersWithPercArray,correctAnswer,fixedChoices,num_user){
        var id='<?php echo $id ?>';
        var finalData=[];
        for(var i in fixedChoices){
            var user_perc=(answersWithPercArray[fixedChoices[i]]/num_user)*100;
            user_perc=parseInt(user_perc);
            finalData.push({answer :fixedChoices[i],num_user:answersWithPercArray[fixedChoices[i]],user_perc:user_perc});
        }

       console.log(finalData);
        var bridgeDataPoints = [];
        var backGroundColor = [];
        var x_axis_data = [];
        function DataPoint(id, user_perc, num_user, answer) {
            this.id = String(answer);
            this.user_perc = user_perc;
            this.num_user = num_user;
            this.answer = answer;
        }
        DataPoint.prototype.toString = function () {
            return this.id;
        };
        for(var i in finalData){
            var dataPoint = new DataPoint(i,
                finalData[i].user_perc,
                finalData[i].num_user,
                finalData[i].answer);
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
            type: 'pie',
            data: data,
            options: {
                responsive : true,
                tooltips : {

                    callbacks : { // HERE YOU CUSTOMIZE THE LABELS
                        title : function() {
                            // return '';
                        },
                        beforeLabel : function(tooltipItem, data) {
                            console.log(tooltipItem.index);
                            console.log(data.labels[tooltipItem.index]);
                            var numUser="No. of users: "+data.labels[tooltipItem.index].num_user;
                            var answer="Answer: "+data.labels[tooltipItem.index].answer;
                            var userPerc="User percentage: "+data.labels[tooltipItem.index].user_perc;
                            /*'Month ' + ': ' + data.labels[tooltipItem.index].tooltip;*/
                            return  [userPerc,numUser,answer];
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
            if(!empty($questionOptions[$answers])){
                array_push($finalAnswersByUsersArray,$questionOptions[$answers]);
            }
        }
         // echo json_encode($fixedChoices);
    }
    $vals=array_count_values($finalAnswersByUsersArray);
    //--Commented-pangea--
  //  $correctAnswer=$questionOptions[$PollingQuizResultModel->correctAnswer];
    //--Commented-pangea end--
    //print_r($vals);
    ?>
    <script>
        var graphArray = <?php echo json_encode($vals) ?>;
        //--Commented-pangea--
        //var correctAnswer= '<?php //echo $correctAnswer; ?>//';
        //--Commented-pangea end--
        var fixedChoices= <?php echo json_encode($fixedChoices); ?>;
        var num_user='<?php echo count($PollingQuizResultModel->answerByUsers) ?>';
        graphMultipleChoice(graphArray,correctAnswer,fixedChoices,num_user);
    </script>
    <?php
    //echo $vals["true"];
}

?>
