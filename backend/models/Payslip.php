<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tbl_payslip".
 *
 * @property int $id
 * @property int $employee_id
 * @property string $payslip_date
 * @property float $basic_salary
 * @property float $housing_allowance
 * @property float $transportation_allowance
 * @property float $total_salary
 * @property float $deductions
 * @property float $net_salary
 * @property string|null $status
 * @property string|null $pay_period
 * @property string|null $allowances
 * @property int|null $payroll_run_id
 * @property string|null $tax_deductions
 * @property string $created_at
 */
class Payslip extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_payslip';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['employee_id', 'payslip_date', 'basic_salary', 'total_salary', 'net_salary'], 'required'],
            [['employee_id', 'payroll_run_id'], 'integer'],
            [['payslip_date', 'created_at'], 'safe'],
            [['basic_salary', 'housing_allowance', 'transportation_allowance', 'total_salary', 'deductions', 'net_salary'], 'number'],
            [['status'], 'in', 'range' => ['Pending', 'Paid']],
            [['pay_period', 'allowances', 'tax_deductions'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'employee_id' => 'Employee ID',
            'payslip_date' => 'Payslip Date',
            'basic_salary' => 'Basic Salary',
            'housing_allowance' => 'Housing Allowance',
            'transportation_allowance' => 'Transportation Allowance',
            'total_salary' => 'Total Salary',
            'deductions' => 'Deductions',
            'net_salary' => 'Net Salary',
            'status' => 'Status',
            'pay_period' => 'Pay Period',
            'allowances' => 'Allowances',
            'payroll_run_id' => 'Payroll Run ID',
            'tax_deductions' => 'Tax Deductions',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Optional: relation with Employee if exists
     */
    public function getEmployee()
    {
        return $this->hasOne(Payslip::class, ['employee_id' => 'employee_id']);
    }

    /**
     * Optional: relation with PayrollRun if exists
     */
    public function getPayrollRun()
    {
        return $this->hasOne(PayrollRun::class, ['id' => 'payroll_run_id']);
    }
}
