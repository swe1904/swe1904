<?php

namespace backend\modules\payroll\models;

use Yii;

/**
 * This is the model class for table "tbl_payroll_pay_period_setting".
 *
 * @property int $id
 * @property string $start_date
 * @property string $end_date
 */
class PayrollPayPeriodSetting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_payroll_pay_period_setting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['start_date', 'end_date'], 'required'],
            [['start_date', 'end_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
        ];
    }
}
