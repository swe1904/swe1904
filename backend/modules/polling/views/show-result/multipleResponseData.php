<?php
echo $this->render('html_content/multipleResponseData', [
    'PollingQuizResultModel' => $PollingQuizResultModel,
    'pollingQuizQuestion'=>$pollingQuizQuestion,
    'id'=>$id
]);
?>
<script>
    function graphMultipleResponse(answersWithPercArray,num_user){
        var id='<?php echo $id ?>';
        var finalData=[];
        for(var i in answersWithPercArray){
            var user_perc=(answersWithPercArray[i]/num_user)*100;
            user_perc=parseInt(user_perc);
            finalData.push({answer :"[ "+i+" ]",num_user:answersWithPercArray[i],user_perc:user_perc});
        }
        console.log("multiple choice")
        console.log(finalData)
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
    var insertLinebreaks = function (d) {
        /*var el = d3.select(this);
        var words = d.split(' ');
        el.text('');

        for (var i = 0; i < words.length; i++) {
            alert("sdfdsfsdfsd");
            var tspan = el.append('tspan').text(words[i]);
            if (i > 0)
                tspan.attr('x', 0).attr('dy', '15');
        }*/
        /*var el = d3.select(this);
        var tspan = el.append('tspan')
            .style("width",20)
            .text("sdfsdfsdsd");
        console.log("starttt");*/
        for(var i in d){

            console.log(d[i]);
        }
        //console.log(d);
    };


</script>
<?php
$finalAnswersByUsersArray=[];
$questionOptions=[];
$fixedChoices=[];
$finalAnswerArray=[];
//echo json_encode(array_count_values($PollingQuizResultModel->answerByUsers));
//print_r($PollingQuizResultModel->answerByUsersMR);
//print_r($PollingQuizResultModel->correctAnswerMR);
if(!empty($PollingQuizResultModel->answerByUsersMR)){
    foreach($PollingQuizResultModel->answerByUsersMR as $arrayVal){
        //echo json_encode($arrayVal)."/n";
        if(count($finalAnswerArray)==0){
            array_push($finalAnswerArray,["count"=>1,"arrayVal"=>$arrayVal]);
        }else{
            $is_array_found=false;
            $found_key=-1;
            foreach($finalAnswerArray as $key=>$inArrayVal){
                //$diff_array=array_diff($arrayVal,$inArrayVal['arrayVal']);
                // check if two array has same values or not
                if(array_diff_custom($inArrayVal['arrayVal'],$arrayVal)){
                    $is_array_found=true;
                    $found_key=$key;
                    break;
                }
            }
            if($is_array_found){
                //echo json_encode($finalAnswerArray[$found_key]);
                $new_count_val=(int)$finalAnswerArray[$found_key]['count']+1;
                $finalAnswerArray[$found_key]['count']=$new_count_val;
            }else{
                array_push($finalAnswerArray,["count"=>1,"arrayVal"=>$arrayVal]);
            }
        }

    }
    $finalShowAnswerArray=[];
    foreach($finalAnswerArray as $array_val){
         $count=$array_val['count'];
         $answersArray=$array_val['arrayVal'];
        $answerString=returnAnswerString($answersArray,$pollingQuizQuestion);
        $finalShowAnswerArray[$answerString]=$count;
       // echo $answerString."<br>";
    }
    ?>
    <script>
        var graphArray = <?php echo json_encode($finalShowAnswerArray) ?>;
        var num_user='<?php echo count($PollingQuizResultModel->answerByUsersMR) ?>';
        graphMultipleResponse(graphArray,num_user);
    </script>
    <?php
}

?>
<style>
    .dimple-tooltip{
        background: white;
    }
</style>
