<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%receipt_item}}".
 *
 * @property integer $id
 * @property integer $receipt_id
 * @property string $description
 * @property string $price
 *
 * @property Receipt $receipt
 */
class ReceiptItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%receipt_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['receipt_id'], 'integer'],
            [['price'], 'number'],
            [['description'], 'string', 'max' => 512]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'receipt_id' => 'Receipt ID',
            'description' => 'Description',
            'price' => 'Price',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceipt()
    {
        return $this->hasOne(Receipt::className(), ['id' => 'receipt_id']);
    }
}
