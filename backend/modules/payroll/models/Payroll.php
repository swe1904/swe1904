<?php

namespace backend\modules\payroll\models;

use Yii;

/**
 * This is the model class for table "tbl_payroll".
 *
 * @property int $id
 * @property string|null $day
 * @property string|null $email
 * @property string|null $status
 * @property string|null $year
 * @property string|null $date
 */
class Payroll extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_payroll';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['day', 'email', 'status', 'year'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'day' => 'Day',
            'email' => 'Email',
            'status' => 'Status',
            'year' => 'Year',
            'date' => 'Date',
        ];
    }
}

