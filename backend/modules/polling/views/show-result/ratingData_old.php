<?php
echo $this->render('html_content/ratingData', [
    'PollingQuizResultModel' => $PollingQuizResultModel,
    'pollingQuizQuestion'=>$pollingQuizQuestion,
]);
?>
<div class="col-md-12">
    <div class='middle_data' id='<?php echo $id ?>'></div>
</div>
<script>
    function graphRating(answersWithPercArray,noOfUsersArray){
        var id='<?php echo $id ?>';
        console.log(answersWithPercArray);
        var svg = dimple.newSvg("#"+id, 690, 500);
        var axisData={'User percentage':null,"Ratings":null};
        var finalData=[];
        for(var i in answersWithPercArray){
            //alert(answersWithPercArray[i]+"      "+i);
            finalData.push({'User percentage':answersWithPercArray[i],"Ratings":i,"No of users":noOfUsersArray[i+'_user']});
        }
        //console.log(finalData);
        var myChart = new dimple.chart(svg, finalData);
       // myChart.setBounds(60, 30, 510, 305)
        var x = myChart.addCategoryAxis("x", "Ratings");
        /*x.tickFormat = ',.1f';addCategoryAxis
         x.addOrderRule("Order");*/
        x.addOrderRule("Ratings");
        var y = myChart.addMeasureAxis("y", "User percentage");
        y.overrideMax = 100;
        myChart.setMargins("10%", "10%", "10%", "10%");
        myChart.addSeries(['No of users'], dimple.plot.bar);
        myChart.draw();
        console.log(answersWithPercArray[3]);
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
