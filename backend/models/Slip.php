<?php

namespace backend\models;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "slip".
 *
 * @property int $id
 * @property int|null $organisation_id
 * @property int $employee_id
 * @property string $payslip_month
 * @property string $payslip_year
 * @property float|null $leaves_left
 * @property string $start_date
 * @property string $end_date
 * @property float $leaves_taken
 * @property int|null $payment_mode 1=Cash, 2=Cheque, 3=Draft
 * @property int|null $By_Month 0=Not Visible,1=visible
 * @property int|null $By_Date 0=Not Visible,1=visible
 * @property string|null $description
 * @property string|null $cheque_number
 * @property int $current_salary
 * @property float $deduction
 * @property float $final_salary
 * @property float|null $bonus
 * @property string|null $pangea_employee_reference
 * @property string|null $leaves_accrued
 *
 * @property Employee $employee
 * @property Organisation $organisation
 */
class Slip extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'slipItems',
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'slip';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['organisation_id', 'employee_id', 'payment_mode', 'By_Month', 'By_Date', 'current_salary'], 'integer'],
            [['employee_id', 'payslip_month', 'payslip_year', 'start_date', 'end_date', 'leaves_taken', 'current_salary', 'final_salary'], 'required'],
            [['payslip_month', 'leaves_accrued'], 'string'],
            [['payslip_year'], 'safe'],
            [['leaves_left', 'leaves_taken', 'deduction', 'final_salary', 'bonus'], 'number'],
            [['start_date', 'end_date', 'pangea_employee_reference'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 255],
            [['cheque_number'], 'string', 'max' => 512],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::class, 'targetAttribute' => ['employee_id' => 'id']],
            [['organisation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organisation::class, 'targetAttribute' => ['organisation_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'organisation_id' => 'Organisation ID',
            'employee_id' => 'Employee ID',
            'payslip_month' => 'Payslip Month',
            'payslip_year' => 'Payslip Year',
            'leaves_left' => 'Leaves Left',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'leaves_taken' => 'Leaves Taken',
            'payment_mode' => 'Payment Mode',
            'By_Month' => 'By Month',
            'By_Date' => 'By Date',
            'description' => 'Description',
            'cheque_number' => 'Cheque Number',
            'current_salary' => 'Current Salary',
            'deduction' => 'Deduction',
            'final_salary' => 'Final Salary',
            'bonus' => 'Bonus',
            'pangea_employee_reference' => 'Pangea Employee Reference',
            'leaves_accrued' => 'Leaves Accrued',
        ];
    }

    /**
     * Gets query for [[Employee]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::class, ['id' => 'employee_id']);
    }

    /**
     * Gets query for [[Organisation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganisation()
    {
        return $this->hasOne(Organisation::class, ['id' => 'organisation_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSlipItems()
    {
        return $this->hasMany(\backend\models\SlipItem::className(), ['slip_id' => 'id']);
    }
}

