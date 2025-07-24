<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

class ShiftSchedule extends ActiveRecord
{
    public static function tableName()
    {
        return 'shift_schedule';
    }

    public function rules()
    {
        return [
            [['employee_id', 'shift_start', 'shift_end'], 'required'],
            [['minimum_hours', 'grace_period'], 'integer'],
            [['shift_start', 'shift_end'], 'safe'],
            [['enforced_days'], 'string', 'max' => 255],
        ];
    }
}

