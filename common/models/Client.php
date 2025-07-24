<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%client}}".
 *
 * @property integer $id
 * @property string $country_name
 * @property string $country
 * @property string $registration_increment_alpahabetic_part
 * @property string $registration_increment_number_part
 * @property string $email
 * @property string $mobile
 * @property string $middle_name
 * @property integer $user_id
 * @property string $address
 * @property string $gstin
 * @property string $pan
 * @property integer $is_taxable
 *
 * @property User $user
 * @property Receipt[] $receipts
 */
class Client extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
//        return '{{%client}}';
        return '{{client}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_name', 'mobile', 'email'], 'required'],
            [['registration_increment_number_part', 'mobile', 'user_id','is_taxable'], 'integer'],
            [['client_name', 'country', 'email', 'middle_name', 'address'], 'string', 'max' => 512],
            [['registration_increment_alpahabetic_part','gstin','pan'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_name' => 'Client Name/Company Name',
            'country' => 'Country',
            'registration_increment_alpahabetic_part' => 'Registration  Alpahabetic Part',
            'registration_increment_number_part' => 'Registration  Number Part',
            'email' => 'Email',
            'mobile' => 'Mobile',
            'middle_name' => 'Middle Name',
            'user_id' => 'User ',
            'address' => 'Address',
            'gstin' => 'GSTIN',
            'pan' => 'PAN',
            'is_taxable' => 'Is Taxable',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceipts()
    {
        return $this->hasMany(Receipt::className(), ['client_id' => 'id']);
    }
}
