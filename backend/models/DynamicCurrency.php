<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tbl_dynamic_currency".
 *
 * @property int $id
 * @property int $currency_id
 * @property float $conversion_rate_to_SAR
 *
 * @property Currency $currency
 */
class DynamicCurrency extends \yii\db\ActiveRecord
{

    use \mootensai\relation\RelationTrait;

    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
   
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'currency_new';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code'], 'required'],
            ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Currency Code',
            
        ];
    }

    /**
     * Gets query for [[Currency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::class, ['id' => 'id']);
    }
}
