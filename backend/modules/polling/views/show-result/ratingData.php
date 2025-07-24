<?php
echo $this->render('html_content/ratingData', [
    'PollingQuizResultModel' => $PollingQuizResultModel,
    'pollingQuizQuestion'=>$pollingQuizQuestion,
]);
?>
<div class="col-md-12"  style="width: 75%">
    <canvas id='<?php echo $id ?>'></canvas>
</div>
<script>
    function graphRating(answersWithPercArray,noOfUsersArray){
        //Chart.defaults.global.legend.display = false;
        var id='<?php echo $id ?>';
        console.log(answersWithPercArray);
        var svg = dimple.newSvg("#"+id, 690, 500);
        var axisData={'User percentage':null,"Ratings":null};
        var finalData=[];
        for(var i in answersWithPercArray){
            //alert(answersWithPercArray[i]+"      "+i);
            var user_perc=parseInt(answersWithPercArray[i]);
            finalData.push({'user_perc':user_perc,"rating":i,"num_user":noOfUsersArray[i+'_user']});
        }
        console.log("neewwwww");
        console.log(finalData);
        var bridgeDataPoints = [];
        var backGroundColor = [];
        var x_axis_data = [];
        function DataPoint(id, user_perc, num_user, rating) {
            this.id = String(rating);
            this.user_perc = user_perc;
            this.num_user = num_user;
            this.rating = rating;
        }
        DataPoint.prototype.toString = function () {
            return this.id;
        };
        for(var i in finalData){
            var dataPoint = new DataPoint(i,
                finalData[i].user_perc,
                finalData[i].num_user,
                finalData[i].rating);
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
                            var numUser="No. of users: "+data.labels[tooltipItem.index].num_user;
                            var rating="Ratings: "+data.labels[tooltipItem.index].rating;
                            var userPerc="User percentage: "+data.labels[tooltipItem.index].user_perc;
                            /*'Month ' + ': ' + data.labels[tooltipItem.index].tooltip;*/
                            return  [userPerc,numUser,rating];
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
$noOfUsersArray=[];
 if(!empty($pollingQuizQuestion->pollingQuizQuestionOptions)){
     $maxRatingVal=$pollingQuizQuestion->pollingQuizQuestionOptions[0]->value;
     if(!empty($PollingQuizResultModel->answerByUsers)){
         $countTotalAnswers=count($PollingQuizResultModel->answerByUsers);
         //echo $countTotalAnswers;
         //echo json_encode($PollingQuizResultModel->answerByUsers);
         $vals = array_count_values($PollingQuizResultModel->answerByUsers);
         for($i=1;$i<=$maxRatingVal;$i++){
             if(!empty($vals[$i])){
                 // calculate percentage of users given the same answers
                 $percnetage=($vals[$i]/$countTotalAnswers)*100;
                 $answersWithPercArray[$i]=$percnetage;
                 $noOfUsersArray[$i."_user"]=$vals[$i];
             }else{
                 $answersWithPercArray[$i]=0;
                 $noOfUsersArray[$i."_user"]=0;
             }
         }
         //echo json_encode($answersWithPercArray);
        ?>
         <script>
             var answersWithPercArray = <?php echo json_encode($answersWithPercArray) ?>;
             var noOfUsersArray = <?php echo json_encode($noOfUsersArray) ?>;
             graphRating(answersWithPercArray,noOfUsersArray);
         </script>
        <?php
     }
 }
?>
