<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tbl_case_type_service_price".
 *
 * @property int $id
 * @property int $case_type_pricing_id
 * @property string $service_name
 * @property int $price
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CaseTypePricing $caseTypePricing
 */
class CaseTypeServicePrice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_case_type_service_price';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['case_type_pricing_id', 'service_name', 'price'], 'required'],
            [['case_type_pricing_id', 'price'], 'number', 'min' => 0],
            [['created_at', 'updated_at'], 'safe'],
            [['service_name'], 'string', 'max' => 255],
            [['case_type_pricing_id'], 'exist', 'skipOnError' => true, 'targetClass' => CaseTypePricing::class, 'targetAttribute' => ['case_type_pricing_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'case_type_pricing_id' => 'Case Type Pricing ID',
            'service_name' => 'Service Name',
            'price' => 'Price',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[CaseTypePricing]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCaseTypePricing()
    {
        return $this->hasOne(CaseTypePricing::class, ['id' => 'case_type_pricing_id']);
    }
}
