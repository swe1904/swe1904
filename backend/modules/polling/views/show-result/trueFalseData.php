<?php
echo $this->render('html_content/trueFalse', [
    'PollingQuizResultModel' => $PollingQuizResultModel,
    'pollingQuizQuestion'=>$pollingQuizQuestion,
]);
?>
<div class="col-md-12"  style="width: 80%">
    <canvas id='<?php echo $id ?>'></canvas>
</div>
<script>
    function graphTrueFalse(answersWithPercArray,correctAnswer){

        var id='<?php echo $id ?>';
        var finalData=[];
        var true_prec=(answersWithPercArray["true"]/(answersWithPercArray["true"]+answersWithPercArray["false"]))*100;
        var false_prec=(answersWithPercArray["false"]/(answersWithPercArray["true"]+answersWithPercArray["false"]))*100;
        true_prec=parseInt(true_prec);
        false_prec=parseInt(false_prec);
        finalData.push({answer:"True",num_user:answersWithPercArray["true"],user_perc:true_prec},{answer:"False",num_user:answersWithPercArray["false"],user_perc:false_prec});
        console.log("fffffff");
        console.log(finalData);
        var bridgeDataPoints = [];
        var backGroundColor = [];
        var x_axis_data = [];
        function DataPoint(id, answer, num_user, user_perc) {
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
                finalData[i].answer,
                finalData[i].num_user,
                finalData[i].user_perc);
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
$answersWithPercArray=[];
//echo json_encode(array_count_values($PollingQuizResultModel->answerByUsers));
if(!empty($PollingQuizResultModel->answerByUsers)){
    $vals=array_count_values($PollingQuizResultModel->answerByUsers);
    $correctAnswer=$PollingQuizResultModel->correctAnswer;
    ?>
    <script>
        var graphArray = <?php echo json_encode($vals) ?>;
        var correctAnswer= '<?php echo $correctAnswer; ?>';
        graphTrueFalse(graphArray,correctAnswer);
    </script>
    <?php
    //echo $vals["true"];
}
?>
