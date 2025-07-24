<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%receipt_service}}".
 *
 * @property integer $id
 * @property integer $service_id
 * @property integer $receipt_id
 *
 * @property Receipt $receipt
 * @property Service $service
 */
class ReceiptService extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%receipt_service}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_id', 'receipt_id'], 'required'],
            [['service_id', 'receipt_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'service_id' => 'Service ID',
            'receipt_id' => 'Receipt ID',
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
    public function getService()
    {
        return $this->hasOne(Service::className(), ['id' => 'service_id']);
    }
}
