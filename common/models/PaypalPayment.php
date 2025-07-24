<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%paypal_payment}}".
 *
 * @property integer $id
 * @property string $ack
 * @property integer $user_id
 * @property integer $receipt_id
 * @property string $transactionId
 * @property string $token
 * @property string $transactionType
 * @property string $paymentType
 * @property string $paymentDate
 * @property string $currencyID
 * @property string $value
 * @property string $timestamp
 * @property string $payment_status
 *
 * @property Receipt $receipt
 * @property User $user
 */
class PaypalPayment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%paypal_payment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'receipt_id'], 'integer'],
            [['value'], 'number'],
            [['ack', 'transactionType', 'paymentType', 'paymentDate', 'currencyID', 'timestamp', 'payment_status'], 'string', 'max' => 50],
            [['transactionId', 'token'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ack' => 'Ack',
            'user_id' => 'User ID',
            'receipt_id' => 'Receipt ID',
            'transactionId' => 'Transaction ID',
            'token' => 'Token',
            'transactionType' => 'Transaction Type',
            'paymentType' => 'Payment Type',
            'paymentDate' => 'Payment Date',
            'currencyID' => 'Currency ID',
            'value' => 'Value',
            'timestamp' => 'Timestamp',
            'payment_status' => 'Payment Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceipt()
    {
        return $this->hasOne(Receipt::className(), ['id' => 'receipt_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
