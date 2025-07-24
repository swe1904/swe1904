<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

class PayrollRun extends ActiveRecord
{
    // public $payroll_year;
    public static function tableName()
    {
        return 'tbl_payroll_run';
    }

    public function rules()
    {
        return [
            [['payroll_month', 'payroll_year', 'total_employees', 'total_amount_paid', 'total_social_insurance', 'total_income_tax'], 'required'],
            [['payroll_month', 'payroll_year', 'total_employees'], 'integer'],
            [['total_amount_paid', 'total_social_insurance', 'total_income_tax'], 'number'],
            [['status'], 'string'],
            [['created_at'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'payroll_month' => 'Payroll Month',
            'payroll_year' => 'Payroll Year',
            'total_employees' => 'No. of Employees',
            'total_amount_paid' => 'Total Amount Paid',
            'total_social_insurance' => 'Total Social Insurance Liability',
            'total_income_tax' => 'Total Income Tax Liability',
            'created_at' => 'Created At',
        ];
    }
    public function getPayrollDetails()
    {
        return $this->hasMany(PayrollDetails::class, ['payroll_run_id' => 'id']);
    }
    public function getEmployee()
{
    return $this->hasOne(Employee::class, ['id' => 'employee_id']);
}

}
