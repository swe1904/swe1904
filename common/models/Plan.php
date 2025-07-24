<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%plan}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $receipt_count
 * @property string $amount
 */
class Plan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%plan}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['receipt_count'], 'integer'],
            [['amount'], 'number'],
            [['name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'receipt_count' => 'Receipt Count',
            'amount' => 'Amount',
        ];
    }
}
