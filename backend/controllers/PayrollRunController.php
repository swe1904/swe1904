<?php

namespace backend\controllers;

use backend\models\Employee;
use Yii;
use backend\models\PayrollDetails;
use backend\models\PayrollRun;
use backend\models\Payslip;
use backend\models\search\PayrollRunSearch;
use DateTime;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Tcpdf;
use yii\base\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\Response as WebResponse;

class PayrollRunController extends Controller
{

    
 public function actionIndex()
 {
     $searchModel = new PayrollRunSearch();
     $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

     return $this->render('index', [
         'searchModel' => $searchModel,
         'dataProvider' => $dataProvider,
     ]);
 }

    public function actionCreate()
    {
        $lastPayrollRun = (new \yii\db\Query())
            ->select(['payroll_year', 'payroll_month'])
            ->from('tbl_payroll_run')
            ->orderBy(['id' => SORT_DESC])
            ->limit(1)
            ->one();
            $payrollYear = $lastPayrollRun['payroll_year'] ?? date('Y');
            $payrollMonth = $lastPayrollRun['payroll_month'] ?? date('n');
        return $this->render('create', [
            'lastPayrollRun' => $lastPayrollRun,
            'payrollYear' => $payrollYear,
            'payrollMonth' => $payrollMonth
        ]);
    }

    public function actionPayrollProcessing()
    {
        $employees = (new \yii\db\Query())
            ->select(['id', 'first_name', 'last_name', 'position', 'monthly_salary_basic', 'monthly_salary_housing', 'monthly_salary_transportation', 'sales_commission', 'bonus', 'damages'])
            ->from('employee')  // Ensure this matches your employee table name
            ->where(['status' => 1])  // Fetch active employees
            ->all();
        
        return $this->render('payroll-processing', [
            'employees' => $employees,
        ]);
    }

    public function actionFetchEmployeeData()
    {
        $year = Yii::$app->request->post('year');
        $month = Yii::$app->request->post('month');
    
     
    
        // Fetch only active employees
        $employees = (new \yii\db\Query())
            ->select(['id','user_id', 'first_name', 'last_name', 'position', 'monthly_salary_basic', 'monthly_salary_housing', 'monthly_salary_transportation'])
            ->from('employee')
            ->where(['status' => 1])  // Active employees
            ->all();
    
        if ($employees) {
            return $this->renderPartial('payroll-data', [
                'employees' => $employees,
                'payrollYear' => $year,
                'payrollMonth' => $month
            ]);
        }
    
        return json_encode(['error' => 'No active employees found.']);
    }
    
    public function actionUpdatePayroll()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $employeeId = Yii::$app->request->post('employee_id');
        $field = Yii::$app->request->post('field');
        $value = Yii::$app->request->post('value');

        if (!$employeeId || !$field) {
            return ['success' => false, 'message' => 'Invalid data provided.'];
        }

        // Validate the field
        $allowedFields = [
            'sales_commission', 
            'bonus', 
            'damages', 
            'absence', 
            'employee_loan'
        ];

        if (!in_array($field, $allowedFields)) {
            return ['success' => false, 'message' => 'Invalid field.'];
        }

        // Update the payroll data
        $result = Yii::$app->db->createCommand()
            ->update('employee', [$field => $value], ['id' => $employeeId])
            ->execute();

        if ($result) {
            return ['success' => true, 'message' => 'Updated successfully.'];
        } else {
            return ['success' => false, 'message' => 'Update failed.'];
        }
    }

        // public function actionInsertPayroll()
        // {
        //     Yii::$app->response->format = WebResponse::FORMAT_JSON;
        //     $payrollData = Yii::$app->request->post('payroll_data');

        //     if (!$payrollData) {
        //         return ['success' => false, 'message' => 'No data received.'];
        //     }

        //     $transaction = Yii::$app->db->beginTransaction();
        //     try {
        //         // Insert into tbl_payroll_run
        //         $payrollRunId = Yii::$app->db->createCommand()
        //             ->insert('tbl_payroll_run', [
        //                 'payroll_month' => Yii::$app->request->post('payroll_month'),
        //                 'payroll_year' => Yii::$app->request->post('payroll_year'),
        //                 'status' => 'Pending',
        //                 'created_at' => date('Y-m-d H:i:s')
        //             ])
        //             ->execute();

        //         $payrollRunId = Yii::$app->db->getLastInsertID();
        //         $totalEmployees = 0;
        //         $totalAmountPaid = 0;

        //         foreach ($payrollData as $employee) {
        //             $netSalary = $employee['basic_salary'] + $employee['housing_allowance']
        //                 - $employee['social_insurance'] - $employee['income_tax'];

        //             // Insert payroll details
        //             Yii::$app->db->createCommand()
        //                 ->insert('tbl_payroll_details', [
        //                     'payroll_run_id' => $payrollRunId,
        //                     'employee_id' => $employee['employee_id'],
        //                     'basic_salary' => $employee['basic_salary'],
        //                     'housing_allowance' => $employee['housing_allowance'],
        //                     'transportation_allowance' => $employee['transportation_allowance'],
        //                     'gross_salary' => $employee['gross_salary'],
        //                     'sales_commission' => $employee['sales_commission'],
        //                     'bonus' => $employee['bonus'],
        //                     'damages' => $employee['damages'],
        //                     'social_insurance' => $employee['social_insurance'],
        //                     'income_tax' => $employee['income_tax'],
        //                     'absence' => $employee['absence'],
        //                     'employee_loan' => $employee['employee_loan'],
        //                     'net_salary' => $netSalary
        //                 ])
        //                 ->execute();

        //             // Insert payslip
        //             Yii::$app->db->createCommand()
        //                 ->insert('tbl_payslip', [
        //                     'employee_id' => $employee['employee_id'],
        //                     'payroll_run_id' => $payrollRunId,
        //                     'basic_salary' => $employee['basic_salary'],
        //                     'housing_allowance' => $employee['housing_allowance'],
        //                     'transportation_allowance' => $employee['transportation_allowance'],
        //                     'net_salary' => $netSalary,
        //                     'created_at' => date('Y-m-d H:i:s')
        //                 ])
        //                 ->execute();

        //             $totalEmployees++;
        //             $totalAmountPaid += $netSalary;
        //         }

        //         Yii::$app->db->createCommand()
        //             ->update('tbl_payroll_run', [
        //                 'total_employees' => $totalEmployees,
        //                 'total_amount_paid' => $totalAmountPaid,
        //                 'status' => 'Finalized'
        //             ], ['id' => $payrollRunId])
        //             ->execute();

        //         $transaction->commit();
        //         return ['success' => true, 'message' => 'Payroll data saved successfully.'];
        //     } catch (\Exception $e) {
        //         $transaction->rollBack();
        //         return ['success' => false, 'message' => $e->getMessage()];
        //     }
        // }




public function actionInsertPayroll()
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    $payrollData = Yii::$app->request->post('payroll_data');
    $payrollMonth = Yii::$app->request->post('payroll_month');
    $payrollYear = Yii::$app->request->post('payroll_year');

    if (!$payrollData || !$payrollMonth || !$payrollYear) {
        return ['success' => false, 'message' => 'Missing required data.'];
    }

    $transaction = Yii::$app->db->beginTransaction();
    try {
        Yii::$app->db->createCommand()->insert('tbl_payroll_run', [
            'payroll_month' => $payrollMonth,
            'payroll_year' => $payrollYear,
            'status' => 'Pending',
            'created_at' => date('Y-m-d H:i:s')
        ])->execute();

        $payrollRunId = Yii::$app->db->getLastInsertID();
        $totalEmployees = 0;
        $totalAmountPaid = 0;
        $payslipFiles = []; // to collect generated file names

        foreach ($payrollData as $employee) {
            $netSalary = $employee['basic_salary'] + $employee['housing_allowance'] + $employee['transportation_allowance']
                + $employee['sales_commission'] + $employee['bonus']
                - $employee['social_insurance'] - $employee['income_tax']
                - $employee['absence'] - $employee['damages'] - $employee['employee_loan'];

            Yii::$app->db->createCommand()->insert('tbl_payroll_details', [
                'payroll_run_id' => $payrollRunId,
                'employee_id' => $employee['employee_id'],
                'basic_salary' => $employee['basic_salary'],
                'housing_allowance' => $employee['housing_allowance'],
                'transportation_allowance' => $employee['transportation_allowance'],
                'gross_salary' => $employee['gross_salary'],
                'sales_commission' => $employee['sales_commission'],
                'bonus' => $employee['bonus'],
                'damages' => $employee['damages'],
                'social_insurance' => $employee['social_insurance'],
                'income_tax' => $employee['income_tax'],
                'absence' => $employee['absence'],
                'employee_loan' => $employee['employee_loan'],
                'net_salary' => $netSalary
            ])->execute();

            Yii::$app->db->createCommand()->insert('tbl_payslip', [
                'employee_id' => $employee['employee_id'],
                'payroll_run_id' => $payrollRunId,
                'basic_salary' => $employee['basic_salary'],
                'housing_allowance' => $employee['housing_allowance'],
                'transportation_allowance' => $employee['transportation_allowance'],
                'net_salary' => $netSalary,
                'created_at' => date('Y-m-d H:i:s'),
                'payslip_date' => date('Y-m-d')
            ])->execute();

            // ✅ Generate Payslip PDF using view template
            $pdf = new \Mpdf\Mpdf([
                'format' => 'A4',
                'orientation' => 'P',
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_left' => 10,
                'margin_right' => 10,
            ]);

            $monthName = DateTime::createFromFormat('!m', $payrollMonth)->format('F');

            $html = $this->renderPartial('@backend/views/payslip/payslip-template', [
                'employee' => $employee,
                'monthName' => $monthName,
                'year' => $payrollYear, // ✅ explicitly passing year
                'netSalary' => $netSalary
            ]);
            $pdf->WriteHTML($html);

            // ✅ Save PDF
            $pdfPath = Yii::getAlias('@webroot') . '/payslips/';
            if (!file_exists($pdfPath)) {
                mkdir($pdfPath, 0777, true);
            }

            $pdfFileName = "Payslip_{$employee['employee_id']}_{$payrollMonth}_{$payrollYear}.pdf";
            $pdf->Output($pdfPath . $pdfFileName, \Mpdf\Output\Destination::FILE);
            // Collect file names for download
            $payslipFiles[] = [
            'employee_id' => $employee['employee_id'],
            'file_name' => $pdfFileName
            ];

            $totalEmployees++;
            $totalAmountPaid += $netSalary;
        }

        Yii::$app->db->createCommand()->update('tbl_payroll_run', [
            'total_employees' => $totalEmployees,
            'total_amount_paid' => $totalAmountPaid,
            'status' => 'Finalized'
        ], ['id' => $payrollRunId])->execute();

        $transaction->commit();return [
    'success' => true,
    'message' => 'Payroll processed and payslips generated.',
    'files' => $payslipFiles
];
 
    } catch (\Exception $e) {
        $transaction->rollBack();
        return ['success' => false, 'message' => 'Error while saving payroll: ' . $e->getMessage()];
    }
}


public function actionInsertPayroll_old()
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $request = Yii::$app->request;
    $data = $request->post();

    $transaction = Yii::$app->db->beginTransaction();

    try {
        $payrollRun = new PayrollRun();
        $payrollRun->payroll_month = $data['payroll_month'];
        $payrollRun->payroll_year = $data['payroll_year'];
        // $payrollRun->created_by = Yii::$app->user->id;
        $payrollRun->created_at = date('Y-m-d H:i:s');

        if (!$payrollRun->save()) {
            Yii::error('PayrollRun Error: ' . json_encode($payrollRun->getErrors()), __METHOD__);
            throw new \Exception('PayrollRun save failed');
        }

        foreach ($data['payrollDetails'] as $detail) {
            $payrollDetail = new PayrollDetails();
            $payrollDetail->payroll_run_id = $payrollRun->id;
            $payrollDetail->employee_id = $detail['employee_id'];
            $payrollDetail->basic_salary = $detail['basic_salary'] ?? 0;
            $payrollDetail->housing_allowance = $detail['housing_allowance'] ?? 0;
            $payrollDetail->transportation_allowance = $detail['transportation_allowance'] ?? 0;
            $payrollDetail->other_allowance = $detail['other_allowance'] ?? 0;
            $payrollDetail->sales_commission = $detail['sales_commission'] ?? 0;
            $payrollDetail->bonus = $detail['bonus'] ?? 0;
            $payrollDetail->overtime = $detail['overtime'] ?? 0;
            $payrollDetail->damages = $detail['damages'] ?? 0;
            $payrollDetail->social_insurance = $detail['social_insurance'] ?? 0;
            $payrollDetail->income_tax = $detail['income_tax'] ?? 0;
            $payrollDetail->absence_deduction = $detail['absence_deduction'] ?? 0;
            $payrollDetail->employee_loan = $detail['employee_loan'] ?? 0;

            $additions = $payrollDetail->sales_commission + $payrollDetail->bonus + $payrollDetail->overtime;
            $fixed = $payrollDetail->basic_salary + $payrollDetail->housing_allowance + $payrollDetail->transportation_allowance + $payrollDetail->other_allowance;
            $deductions = $payrollDetail->social_insurance + $payrollDetail->income_tax + $payrollDetail->absence_deduction + $payrollDetail->damages + $payrollDetail->employee_loan;

            $payrollDetail->net_salary = $fixed + $additions - $deductions;

            if (!$payrollDetail->save()) {
                Yii::error('PayrollDetails Error: ' . json_encode($payrollDetail->getErrors()), __METHOD__);
                throw new \Exception('PayrollDetails save failed');
            }

            // Payslip generation
            $employee = Employee::findOne($payrollDetail->employee_id);
            $content = $this->renderPartial('payslip', [
                'employee' => $employee,
                'payroll' => $payrollDetail,
            ]);

            $pdf = new \Mpdf\Mpdf();
            $pdf->WriteHTML($content);

            $payslipPath = Yii::getAlias('@webroot') . '/payslips/' . 'payslip_' . $employee->employee_id . '_' . $data['month'] . '_' . $data['year'] . '.pdf';

            // Ensure the payslips folder exists
            if (!file_exists(Yii::getAlias('@webroot') . '/payslips')) {
                mkdir(Yii::getAlias('@webroot') . '/payslips', 0777, true);
            }

            $pdf->Output($payslipPath, 'F'); // Save to file
        }

        $transaction->commit();
        return [
            'success' => true,
            'message' => 'Payroll and payslips saved successfully.'
        ];

    } catch (\Exception $e) {
        $transaction->rollBack();
        Yii::error('Payroll Save Exception: ' . $e->getMessage(), __METHOD__);
        return [
            'success' => false,
            'message' => 'Error while saving payroll data: ' . $e->getMessage()
        ];
    }
}


public function generatePayslipPDF($payslipId)
{
    $payslip = Payslip::findOne($payslipId);
    if (!$payslip) {
        throw new \Exception("Payslip not found.");
    }

    $employee = $payslip->employee;
    $payrollRun = $payslip->payroll_run;

    if (!$employee || !$payrollRun) {
        throw new \Exception("Employee or Payroll Run data missing.");
    }

    $content = "
        <h1>Payslip for {$payrollRun->payroll_month}/{$payrollRun->payroll_year}</h1>
        <p><strong>Employee:</strong> {$employee->first_name} {$employee->last_name}</p>
        <p><strong>Basic Salary:</strong> {$payslip->basic_salary}</p>
        <p><strong>Housing Allowance:</strong> {$payslip->housing_allowance}</p>
        <p><strong>Transportation Allowance:</strong> {$payslip->transportation_allowance}</p>
        <p><strong>Net Salary:</strong> {$payslip->net_salary}</p>
    ";

    $pdf = new mPDF();
    $pdf->WriteHTML($content);
    $pdf->Output("payslip_{$payslip->id}.pdf", 'I'); // 'I' for inline display
}



public function actionDownloadSummary($id)
{
    // Fetch payroll data based on ID
    $payroll = PayrollRun::findOne($id);
    if (!$payroll) {
        throw new \yii\web\NotFoundHttpException('Payroll not found.');
    }

    // Prepare data for the PDF
    $content = $this->renderPartial('payroll-summary-pdf', [
        'payroll' => $payroll,
    ]);

    // Generate PDF using mPDF
    $mpdf = new Mpdf();
    $mpdf->WriteHTML($content);
    $filename = 'Payroll_Summary_' . date('Y-m') . '.pdf';

    // Force download
    return $this->response->sendContentAsFile($mpdf->Output($filename, 'S'), $filename, [
        'mimeType' => 'application/pdf',
        'inline' => false,
    ]);
}
public function actionDownloadPayslips($payrollRunId, $employeeId = null)
{
    // If employeeId is provided, filter by employee_id
    $query = PayrollDetails::find()->where(['payroll_run_id' => $payrollRunId]);
    if ($employeeId) {
        $query->andWhere(['employee_id' => $employeeId]);
    }
    $payrollDetails = $query->all();

    if (!$payrollDetails) {
        Yii::$app->session->setFlash('error', 'No payroll details found.');
        return $this->redirect(['payroll/index']);
    }

    $pdf = new \Mpdf\Mpdf();
    $content = '<h1>Payslips for Payroll Run ID: ' . $payrollRunId . '</h1>';
    
    foreach ($payrollDetails as $detail) {
        $content .= "
            <h2>Employee: {$detail->employee->first_name} {$detail->employee->last_name}</h2>
            <p>Employee ID: {$detail->employee->id}</p>
            <p>Basic Salary: {$detail->basic_salary}</p>
            <p>Net Salary: {$detail->net_salary}</p>
            <hr>
        ";
    }

    $pdf->WriteHTML($content);
    $pdf->Output("payslips_run_{$payrollRunId}.pdf", 'D'); // 'D' forces download
}


public function actionDownloadPayslips_old($payrollRunId)
{
    $payrollRun = PayrollRun::findOne($payrollRunId);
    if (!$payrollRun) {
        throw new \yii\web\NotFoundHttpException('Payroll Run not found.');
    }

    // Fetch payroll details
    $payrollDetails = (new Query())
        ->select([
            'e.id AS employee_id',
            'e.first_name',
            'e.last_name',
            'e.position',
            'pd.basic_salary',
            'pd.housing_allowance',
            'pd.transportation_allowance',
            'pd.gross_salary',
            'pd.sales_commission',
            'pd.bonus',
            'pd.social_insurance',
            'pd.income_tax',
            'pd.net_salary',
        ])
        ->from('tbl_payroll_details pd')
        ->innerJoin('employee e', 'pd.employee_id = e.id')
        ->where(['pd.payroll_run_id' => $payrollRunId])
        ->all();

    // Generate payslips for each employee
    $mpdf = new Mpdf();
    foreach ($payrollDetails as $details) {
        $content = $this->renderPartial('payslip', [
            'employee' => $details,
            'payrollRun' => $payrollRun,
        ]);

        $mpdf->AddPage();
        $mpdf->WriteHTML($content);
    }

    $filename = 'Payslips_' . date('Y-m') . '.pdf';
    return $this->response->sendContentAsFile($mpdf->Output($filename, 'S'), $filename, [
        'mimeType' => 'application/pdf',
        'inline' => false,
    ]);
}
// public function actionReopenPayroll($id)
// {
//     $details = PayrollDetails::find()
//         ->where(['payroll_run_id' => $id])
//         ->all();

//     return $this->render('reopen', [
//         'details' => $details,
//     ]);
// }
public function actionReopenPayroll($payrollRunId)
{
    $payrollRun = PayrollRun::findOne($payrollRunId);

    if (!$payrollRun) {
        throw new NotFoundHttpException("Payroll Run not found");
    }

    $payrollMonth = $payrollRun->payroll_month;
    $payrollYear = $payrollRun->payroll_year;

    // Get all employee payroll details for this run
    $employees = PayrollDetails::find()
        ->where(['payroll_run_id' => $payrollRunId])
        ->all();

    return $this->render('reopen', [
        'employee' => $employees,
        'payrollMonth' => $payrollMonth,
        'payrollYear' => $payrollYear,
        'payrollRunId' => $payrollRunId,
    ]);
}





public function actionUpdateInline()
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    $detailId = Yii::$app->request->post('detail_id');
    $field = Yii::$app->request->post('field');
    $value = Yii::$app->request->post('value');

    $payrollDetail = PayrollDetails::findOne($detailId);

    if (!$payrollDetail) {
        return ['success' => false, 'message' => 'Record not found'];
    }

    $validFields = ['sales_commission', 'bonus', 'damages', 'absence', 'employee_loan'];

    if (!in_array($field, $validFields)) {
        return ['success' => false, 'message' => 'Invalid field'];
    }

    $payrollDetail->$field = $value;

    // Recalculate net salary
    $payrollDetail->net_salary = $payrollDetail->gross_salary
        + $payrollDetail->sales_commission
        + $payrollDetail->bonus
        - ($payrollDetail->damages + $payrollDetail->absence + $payrollDetail->employee_loan);

    if ($payrollDetail->save()) {
        return ['success' => true];
    } else {
        return ['success' => false, 'message' => 'Failed to save', 'errors' => $payrollDetail->getErrors()];
    }
}




}
