<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%paypal_recurringpaymentsprofile}}".
 *
 * @property integer $id
 * @property string $profileId
 * @property integer $plan_id
 * @property integer $user_id
 * @property string $profileStatus
 * @property string $ack
 * @property string $payerId
 * @property string $token
 * @property string $transaction_id
 * @property string $timestamp
 * @property string $initial_amount
 * @property string $amount
 * @property string $billing_start_date
 * @property string $billing_end_date
 * @property integer $is_cancelled
 * @property string $client_secret
 *
 * @property Plan $plan
 * @property User $user
 */
class PaypalRecurringPaymentsProfile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%paypal_recurringpaymentsprofile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['plan_id', 'user_id', 'is_cancelled'], 'integer'],
            [['timestamp', 'billing_start_date', 'billing_end_date'], 'safe'],
            [['initial_amount', 'amount'], 'number'],
            [['profileId', 'payerId', 'token', 'transaction_id'], 'string', 'max' => 100],
            [['profileStatus', 'ack'], 'string', 'max' => 50],
            [['client_secret'], 'string', 'max' => 512]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'profileId' => 'Profile ID',
            'plan_id' => 'Plan ID',
            'user_id' => 'User ID',
            'profileStatus' => 'Profile Status',
            'ack' => 'Ack',
            'payerId' => 'Payer ID',
            'token' => 'Token',
            'transaction_id' => 'Transaction ID',
            'timestamp' => 'Timestamp',
            'initial_amount' => 'Initial Amount',
            'amount' => 'Amount',
            'billing_start_date' => 'Billing Start Date',
            'billing_end_date' => 'Billing End Date',
            'is_cancelled' => 'Is Cancelled',
            'client_secret' => 'Client Secret',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlan()
    {
        return $this->hasOne(Plan::className(), ['id' => 'plan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
