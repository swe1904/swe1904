<?php

namespace backend\controllers;

use app\components\GlobalConstant;
use backend\models\Cases;
use backend\models\Receipt;
use backend\models\search\CasesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;

class DashboardController extends BaseController
{
    public function actionIndex()
{
    $searchModel = new CasesSearch();
    $data = $searchModel->getFilteredData();

    $groupedCases = [];
    $statusMap = [];
    $receiptStatusCount = [];
    $billingStatus = [];
    $workerGroupedCases = [];
    $caseWorkerMap = [];
    $sendingCountryCases = [];
    $sendingCountryMap = [];

    foreach ($data as $case) {
        // Grouped Cases
        $caseStatusName = $case->caseStatus->name ?? 'No Status';
        $groupedCases[$caseStatusName] = ($groupedCases[$caseStatusName] ?? 0) + 1;
        if ($case->caseStatus === null) {
            $statusMap[$caseStatusName] = 'null';
        } else {
            $statusMap[$caseStatusName] = $case->caseStatus->id;
        }
       

        // Map status name to ID
        $caseStatusId = $case->caseStatus->id ?? null;
        if ($caseStatusId !== null) {
            $statusMap[$caseStatusName] = $caseStatusId;
        }

        // Client Case Workers
        if (!empty($case->client_case_worker_id) && $case->clientCaseWorker) {
            $workerName = $case->clientCaseWorker->username;
            $workerGroupedCases[$workerName] = ($workerGroupedCases[$workerName] ?? 0) + 1;
            $caseWorkerMap[$workerName] = $case->client_case_worker_id;
        }

        // Sending Country Cases
        if (!empty($case->applicant->sending_country)) {
            $countryName = $case->applicant->sending_country;
            $sendingCountryCases[$countryName] = ($sendingCountryCases[$countryName] ?? 0) + 1;
            $sendingCountryMap[$countryName] = $case->applicant->id;
        }

   
         $receipts = $case->receipts;  
          if (empty($receipts)) {
            $receiptStatus = 'No Invoice';
            $billingStatus['No Invoice'] = 'NoInvoice';
        } else {
            $hasReceipt = false;
            $hasInvoice = false;

            foreach ($receipts as $receipt) {
                if ($receipt->is_receipt == 1) {
                    $hasReceipt = true;
                } elseif ($receipt->is_receipt == 0) {
                    $hasInvoice = true;
                }
            }

            if ($hasReceipt) {
                $receiptStatus = 'Payment Received';
                $billingStatus['Payment Received'] = 'Receipt';
            } elseif ($hasInvoice) {
                $receiptStatus = 'Pending Payment';
                $billingStatus['Pending Payment'] = 'Invoiced';
            } else {
                continue; // Skip if all receipts are is_receipt == 0
            }
        }

        // Count case statuses
        $receiptStatusCount[$receiptStatus] = ($receiptStatusCount[$receiptStatus] ?? 0) + 1;
        
        // After the loop, you can print the total
        // echo "Total number of paid receipts: " . $totalPaidReceipts; die();
    }
    uksort($groupedCases, function($a, $b) {
        if ($a === 'No Status') {
            return 1;
        } elseif ($b === 'No Status') {
            return -1;
        }
        return 0;
    });
    // Prepare chart labels and data
    $chartLabels = array_keys($groupedCases);
    $chartData = array_values($groupedCases);
    $chartWorkerLabels = array_keys($workerGroupedCases);
    $chartWorkerData = array_values($workerGroupedCases);
    $sendingCountryLabels = array_keys($sendingCountryCases);
    $sendingCountryData = array_values($sendingCountryCases);
    $receiptStatusLabels = array_keys($receiptStatusCount);
    $receiptStatusData = array_values($receiptStatusCount);

    return $this->render('index', [
        'chartLabels' => $chartLabels,
        'chartData' => $chartData,
        'statusMap' => $statusMap,
        'chartWorkerLabels' => $chartWorkerLabels,
        'chartWorkerData' => $chartWorkerData,
        'caseWorkerMap' => $caseWorkerMap,
        'sendingCountryLabels' => $sendingCountryLabels,
        'sendingCountryData' => $sendingCountryData,
        'sendingCountryMap' => $sendingCountryMap,
        'receiptStatusLabels' => $receiptStatusLabels,
        'receiptStatusData' => $receiptStatusData,
        'billingStatus' => $billingStatus,
    ]);
}
}
    
