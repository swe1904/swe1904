<?php

use app\components\GlobalConstant;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ClientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
<!-- <div class="col-md-12">
    <div class="panel panel-default border-panel card-view">
           Dashboard
    </div>
</div> -->
<style>
   .panel-heading{
      padding:10px !important;
   }
</style>
<div class="client-index">

<div class="col-md-12 " style="margin-left:20px!important">
    <div class="row">
        <!-- First Row: Two Charts -->
        <div class="col-md-6">
            <div class="panel panel-default card-view panel-refresh" style="border: 1px solid #ccc; padding: 5px;">
                <div class="panel-heading" style="background-color: #000; color:white;">
                    <h6 style="color:white!important;" >Cases by Case Status</h6>
                </div>
                <div class="panel-body" style="text-align: center; height:316px!important">
                    <!-- Bar chart for Case Status -->
                    <canvas id="caseStatusChart" style="max-width: 400px !important;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default card-view panel-refresh" style="border: 1px solid #ccc; padding: 5px;">
                <div class="panel-heading" style="background-color: #000; color: #fff;">
                    <h6 style="color:white !important;">Cases by Billing Status</h6>
                </div>
                <div class="panel-body" style="text-align: center;">
                    <!-- Doughnut chart for Billing Status -->
                    <canvas id="billingStatusChart" style="max-width: 400px; max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Second Row: Two Charts -->
        <div class="col-md-6">
            <div class="panel panel-default card-view panel-refresh" style="border: 1px solid #ccc; padding: 5px;">
                <div class="panel-heading" style="background-color: #000; color: #fff;">
                    <h6 style="color:white !important;" >Cases by Sending Country</h6>
                </div>
                <div class="panel-body" style="text-align: center;">
                    <!-- Pie chart for Sending Country -->
                    <canvas id="sendingCountryChart" style="max-width: 400px; max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
        <?php   if (Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_CASE_MANAGER):
                    ?> 
        <div class="col-md-6">
            <div class="panel panel-default card-view panel-refresh" style="border: 1px solid #ccc; padding: 5px;">
                <div class="panel-heading" style="background-color: #000; color: #fff;">
                    <h6 style="color:white !important;">Cases by Client Case Worker</h6>
                </div>
                <div class="panel-body" style="text-align: center;height:316px !important">
                    <!-- Horizontal Bar chart for Client Case Worker -->
                    <canvas id="caseWorkerChart" style="max-width: 400px; "></canvas>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Include Chart.js library to render the charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartLabels = <?= json_encode($chartLabels) ?>;
    const chartData = <?= json_encode($chartData) ?>;
    const chartstatus = <?= json_encode($statusMap) ?>;
    const chartWorkerLabels = <?= json_encode($chartWorkerLabels) ?>; 
    const chartWorkerData = <?= json_encode($chartWorkerData) ?>; 
    const caseWorkerMap = <?= json_encode($caseWorkerMap) ?>; 
    const billingStatus = <?= json_encode($billingStatus) ?>;
    const  receiptStatusLabels =  <?= json_encode($receiptStatusLabels) ?>;
   

    const ctx = document.getElementById('caseStatusChart').getContext('2d');
    const dynamicColors = (count) => Array.from({ length: count }, () =>
    `#${Math.floor(Math.random() * 16777215).toString(16).padStart(6, '0')}`
);
    const getColorFromName = (name) => {
        let hash = 0;
        for (let i = 0; i < name.length; i++) {
            hash = name.charCodeAt(i) + ((hash << 5) - hash);
        }
        const color = `#${((hash & 0x00ffffff).toString(16).toUpperCase()).padStart(6, '0')}`;
        return color;
    };
    
    // Cases by Case Status (Bar Chart)

    new Chart(ctx, {
    type: 'bar',
    data: {
        labels: chartLabels, // Labels for the x-axis (case status names)
        datasets: [{
            label: 'Number of Cases',
            data: chartData, // Data for the bars
            backgroundColor: dynamicColors(chartData.length),
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        onClick: function (evt, elements) {
            if (elements.length > 0) {
                // Get the clicked element
                const chartElement = elements[0];
                const statusIndex = chartElement.index; // Index of the clicked bar
                const statusName = chartLabels[statusIndex]; // Name of the clicked status
                const statusId = chartstatus[statusName]; // Get the corresponding ID from the mapping
                const baseUrl = "<?= Yii::$app->urlManager->createUrl('cases/index') ?>";
                if (statusName === 'No Status' || statusId === 'null') {
                    window.location.href = `${baseUrl}?case_status=null&filtered=true`;
                } else if (statusId !== undefined) {
                    window.location.href = `${baseUrl}?case_status=${encodeURIComponent(statusId)}&filtered=true`;
                }

                
            }
        }
    }
});


    // Cases by Billing Status (Doughnut Chart)
   
    new Chart(document.getElementById('billingStatusChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_values($receiptStatusLabels)) ?>,
        datasets: [{
            data: <?= json_encode(array_values($receiptStatusData)) ?>,
            backgroundColor: dynamicColors(<?= count($receiptStatusLabels) ?>),
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        onClick: function (evt, elements) {
            if (elements.length > 0) {
                const chartElement = elements[0];
                const statusIndex = chartElement.index;
                const statusName = <?= json_encode(array_values($receiptStatusLabels)) ?>[statusIndex];

                // Correct billing status mapping
                const billingStatus = <?= json_encode($billingStatus) ?>;
                const statusMapping = {
                    "Receipt": "Receipt",
                    "Invoiced": "Invoiced",
                    "Payment Received": "Receipt",
                    "Pending Payment": "Invoiced"
                };

                let isReceipt = statusMapping[statusName] || billingStatus[statusName] || null;

              

                if (isReceipt) {
                    const url = "<?= Yii::$app->urlManager->createUrl('cases/index') ?>?is_receipt=" + encodeURIComponent(isReceipt) + '&filtered=true';
                    window.location.href = url;
                } else {
                    console.warn(`No valid mapping found for status: ${statusName}`);
                }
            }
        }
    }
});
    
    // Cases by Sending Country (Pie Chart)
    const chartCountryLabels = <?= json_encode($sendingCountryLabels) ?>;
    const chartCountryData = <?= json_encode($sendingCountryData) ?>;
    const sendingCountryMap = <?= json_encode($sendingCountryMap) ?>; 
    const backgroundColors = chartCountryLabels.map(label => getColorFromName(label));
    new Chart(document.getElementById('sendingCountryChart').getContext('2d'), {
        type: 'pie', // Assuming the chart type is pie
        data: {
            labels: chartCountryLabels,
            datasets: [{
                label: 'Number of Cases',
                data: chartCountryData,
                backgroundColor: backgroundColors,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            onClick: function (evt, elements) {
                if (elements.length > 0) {
                    // Get the clicked element
                    const chartElement = elements[0];
                    const countryIndex = chartElement.index; // Index of the clicked slice
                    const countryName = chartCountryLabels[countryIndex]; // Name of the clicked country
                    const applicantId = sendingCountryMap[countryName]; // Get the corresponding applicant ID

                    if (applicantId !== undefined) {
                        // Redirect to the specified URL with the applicant ID as a query parameter
                        const url = "<?= Yii::$app->urlManager->createUrl('cases/index') ?>?applicant_id=" + encodeURIComponent(applicantId) + '&filtered=true';
                        window.location.href = url;
                    } else {
                        console.error(`No applicant ID found for country: ${countryName}`);
                    }
                }
            }
        }
    });

     // Cases by Client Case Worker (Horizontal Bar Chart)
     new Chart(document.getElementById('caseWorkerChart').getContext('2d'), {
        type: 'bar',
    data: {
        labels: chartWorkerLabels, // Labels for the x-axis (worker names)
        datasets: [{
            label: 'Number of Cases',
            data: chartWorkerData, // Data for the bars
            backgroundColor: dynamicColors(chartData.length),
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        onClick: function (evt, elements) {
            if (elements.length > 0) {
                // Get the clicked element
                const chartElement = elements[0];
                    const workerIndex = chartElement.index; // Index of the clicked bar
                    const workerName = chartWorkerLabels[workerIndex]; // Name of the clicked worker
                    const workerId = caseWorkerMap[workerName]; // Get the corresponding ID from the mapping
                if (workerId !== undefined) {
                    // Redirect to the specified URL with the worker ID as a query parameter
                    const url = "<?= Yii::$app->urlManager->createUrl('cases/index') ?>?client_case_worker_id=" + encodeURIComponent(workerId) + '&filtered=true';

                    window.location.href = url;
                } else {
                    console.error(`No ID found for worker: ${workerName}`);
                }
            }
        }
    }
});
 
 
 


  

</script>





