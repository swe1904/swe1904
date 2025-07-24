<?php
echo $this->render('html_content/numberData', [
    'PollingQuizResultModel' => $PollingQuizResultModel,
    'pollingQuizQuestion'=>$pollingQuizQuestion,
]);
?>
<div class="col-md-12"  style="width: 80%">
    <canvas class="canvas_m" id='<?php echo $id ?>' style="width: 100%;height: 50%;"></canvas>
</div>

<script>
    function graphNumber(answersWithPercArray,correctAnswer,num_user){
        var id='<?php echo $id ?>';
        var finalData=[];
        for(var i in answersWithPercArray){
            var user_per=(answersWithPercArray[i]/num_user)*100;
            user_perc=parseInt(user_perc);
            finalData.push({number :i,num_user:answersWithPercArray[i],user_perc:user_per});
        }
        console.log("ioioioioio");
        console.log(finalData);
        var bridgeDataPoints = [];
        var backGroundColor = [];
        var x_axis_data = [];
        function DataPoint(id, user_perc, num_user, number) {
            this.id = String(number);
            this.user_perc = user_perc;
            this.num_user = num_user;
            this.number = number;
        }
        DataPoint.prototype.toString = function () {
            return this.id;
        };
        for(var i in finalData){
            var dataPoint = new DataPoint(i,
                finalData[i].user_perc,
                finalData[i].num_user,
                finalData[i].number);
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
                            var number="Number: "+data.labels[tooltipItem.index].number;
                            var userPerc="User percentage: "+data.labels[tooltipItem.index].user_perc;
                            /*'Month ' + ': ' + data.labels[tooltipItem.index].tooltip;*/
                            return  [userPerc,numUser,number];
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
    //print_r($vals);
    $correctAnswer=$PollingQuizResultModel->correctAnswer;
    ?>
    <script>
        var graphArray = <?php echo json_encode($vals) ?>;
        var correctAnswer= '<?php echo $correctAnswer; ?>';
        var num_user='<?php echo count($PollingQuizResultModel->answerByUsers) ?>'
        graphNumber(graphArray,correctAnswer,num_user);
    </script>
    <?php
    //echo $vals["true"];
}
?>
