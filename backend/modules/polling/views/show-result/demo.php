<?php
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/show-result-wizard/jquery.steps.js", ['position' => \yii\web\View::POS_BEGIN]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/show-result-wizard/dimpleD3.js", ['position' => \yii\web\View::POS_BEGIN]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/show-result-wizard/dimple.js", ['position' => \yii\web\View::POS_BEGIN]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/show-result-wizard/bootstrap.js", ['position' => \yii\web\View::POS_BEGIN]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/show-result-wizard/bootstrap.min.js", ['position' => \yii\web\View::POS_BEGIN]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/show-result-wizard/jquery.snippet.min.js", ['position' => \yii\web\View::POS_BEGIN]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/show-result-wizard/jquery.easyWizard.js", ['position' => \yii\web\View::POS_BEGIN]);

$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/show-result-wizard/jquery.bootstrap.wizard.js", ['position' => \yii\web\View::POS_BEGIN]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/show-result-wizard/jquery.bootstrap.wizard.min.js", ['position' => \yii\web\View::POS_BEGIN]);

$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/show-result-wizard/chart/Chart.bundle.js", ['position' => \yii\web\View::POS_BEGIN]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/show-result-wizard/chart/Chart.bundle.min.js", ['position' => \yii\web\View::POS_BEGIN]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/show-result-wizard/chart/Chart.js", ['position' => \yii\web\View::POS_BEGIN]);
$this->registerJsFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/show-result-wizard/chart/Chart.min.js", ['position' => \yii\web\View::POS_BEGIN]);

$this->registerCssFile(Yii::$app->urlManager->baseUrl . "/../modules/polling/app/css/jquery.steps.css", ['position' => \yii\web\View::POS_HEAD]);
?>
<div class="col-md-6">
    <canvas id="myChart" width="400" height="400"></canvas>
</div>


<script>
    var bridgeDataPoints = [];
    var bridge_age_grab = [];
    function DataPoint(id, age, milepost) {
        this.id = id;
        this.age = age;
        this.milepost = milepost;
        this.tooltip = "Bridge ID:" + id + ", Bridge Age: " + age +", Milepost: " + milepost;
    }
    DataPoint.prototype.toString = function () {
        return this.id;
    };
    for(var i=1;i<=6;i++){
        var dataPoint = new DataPoint(i*10, i*10, i*10);
        bridge_age_grab.push(i*10);
        bridgeDataPoints.push(dataPoint);
    }
    var data = {
        labels: bridgeDataPoints,
        datasets: [
            {
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1,
                data: [65, 59, 80, 81, 56, 55, 40],
            }
        ]
    };
    // Any of the following formats may be used
    var ctx = document.getElementById("myChart");
    var ctx = document.getElementById("myChart").getContext("2d");
    var ctx = $("#myChart");
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
            responsive : true,
            tooltips : {

                callbacks : { // HERE YOU CUSTOMIZE THE LABELS
                    title : function() {
                        return '***** My custom label title *****';
                    },
                    beforeLabel : function(tooltipItem, data) {
                        console.log(tooltipItem.index);
                        console.log(data.labels[tooltipItem.index]);
                        return 'Month ' + ': ' + data.labels[tooltipItem.index].tooltip;
                    },
                    label : function(tooltipItem, data) {
                        return data.datasets[tooltipItem.datasetIndex].label + ': ' + tooltipItem.yLabel;
                    },
                    afterLabel : function(tooltipItem, data) {

                        return '***** Test *****';
                    },
                }

            },
            /*tooltips: {
                enabled: false,
                //custom: customTooltips
            }*/
        }
    });

</script>