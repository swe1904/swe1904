<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%organisation}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $tagline
 * @property string $address
 * @property string $landline
 * @property integer $mobile
 * @property string $email
 * @property string $website
 * @property string $logo
 * @property string $service_tax_number
 * @property string $service_tax_percentage
 * @property integer $currency_id
 * @property string $receipt_increment_alpahabetic_part
 * @property string $receipt_increment_number_part
 * @property string $date_format
 * @property integer $logo_to_be_printed
 *
 * @property Currency $currency
 * @property User $user
 */
class Organisation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%organisation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'mobile', 'currency_id', 'logo_to_be_printed'], 'integer'],
            [['name', 'address', 'mobile', 'email', 'currency_id', 'receipt_increment_alpahabetic_part', 'receipt_increment_number_part', 'date_format', 'logo_to_be_printed'], 'required'],
            [['service_tax_percentage'], 'number'],
            [['name', 'tagline', 'address', 'landline', 'email', 'website', 'logo', 'service_tax_number'], 'string', 'max' => 512],
            [['receipt_increment_alpahabetic_part', 'receipt_increment_number_part'], 'string', 'max' => 51],
            [['date_format'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name' => 'Name',
            'tagline' => 'Tagline',
            'address' => 'Address',
            'landline' => 'Landline',
            'mobile' => 'Mobile',
            'email' => 'Email',
            'website' => 'Website',
            'logo' => 'Logo',
            'service_tax_number' => 'GSTIN',
            'service_tax_percentage' => 'GST %',
            'currency_id' => 'Currency ID',
            'receipt_increment_alpahabetic_part' => 'Receipt Increment Alpahabetic Part',
            'receipt_increment_number_part' => 'Receipt Increment Number Part',
            'date_format' => 'Date Format',
            'logo_to_be_printed' => 'Logo To Be Printed',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
