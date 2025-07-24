<?php
namespace backend\models;


use Yii;
use yii\db\ActiveRecord;

class PayrollDetails extends ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_payroll_details';
    }

    public function rules()
    {
        return [
            [['payroll_run_id', 'employee_id', 'basic_salary', 'gross_salary', 'net_salary'], 'required'],
            [['payroll_run_id', 'employee_id'], 'integer'],
            [['basic_salary', 'housing_allowance', 'transportation_allowance', 'other_allowance', 'gross_salary', 'overtime', 'sales_commission', 'bonus', 'social_insurance', 'income_tax', 'absence', 'damages', 'employee_loan', 'net_salary'], 'number'],
        ];
    }

    public function getEmployee()
    {
        return $this->hasOne(Employee::class, ['id' => 'employee_id']);
    }
}
