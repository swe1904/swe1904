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
    <div class='middle_data' id='<?php echo $id ?>'></div>
</div>

<script>
    function graphNumber(answersWithPercArray,correctAnswer){
        var id='<?php echo $id ?>';
        var finalData=[];
        for(var i in answersWithPercArray){
            finalData.push({Number :i,Users:answersWithPercArray[i]});
        }

        var svg = dimple.newSvg("#"+id, 590, 400);
        var myChart = new dimple.chart(svg, finalData);
       // myChart.setBounds(20, 20, 460, 360)
        myChart.addMeasureAxis("p", "Users");
        myChart.addSeries("Number", dimple.plot.pie);
        myChart.addLegend(500, 20, 90, 300, "left");
        myChart.setMargins("10%", "10%", "10%", "10%");
        myChart.draw();
        //alert(correctAnswer);
        console.log(answersWithPercArray);
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
        graphNumber(graphArray,correctAnswer);
    </script>
    <?php
    //echo $vals["true"];
}
?>
