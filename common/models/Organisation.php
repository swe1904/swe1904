<?php

namespace common\models;

use trntv\filekit\behaviors\UploadBehavior;
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
 * @property string $created_at
 * @property string $updated_at
 * @property string $tin_number
 * @property string $thumb_logo
 * @property string $avatar_path
 * @property string $avatar_base_url
 * @property string $receipt_note
 * @property string $trn
 * @property string $company_id
 * @property string $country_code
 *
 * @property Currency $currency
 * @property User $user
 */
class Organisation extends \yii\db\ActiveRecord
{

    public $picture;
    public $vat_rate_display;


    public function behaviors()
    {
        return [
            'picture' => [
                'class' => UploadBehavior::className(),
                'attribute' => 'picture',
                'pathAttribute' => 'avatar_path',
                'baseUrlAttribute' => 'avatar_base_url',
                'typeAttribute' => false,
                'sizeAttribute' => false,
                'nameAttribute' => false,
                'orderAttribute' => false
            ]
        ];
    }

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
            [['user_id', 'mobile', 'currency_id', 'logo_to_be_printed','receipt_increment_number_part','vat_type'], 'integer'],
            [['name', 'address', 'mobile', 'email', 'currency_id', 'receipt_increment_alpahabetic_part', 'receipt_increment_number_part', 'date_format', 'logo_to_be_printed','vat_type','vat_rate'], 'required'],
            [['service_tax_percentage','vat_rate'], 'number'],
            [['avatar_path','avatar_base_url', 'trn', 'company_id', 'country_code'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'tagline', 'address', 'landline', 'email', 'website', 'logo', 'service_tax_number', 'tin_number', 'thumb_logo', 'receipt_note'], 'string', 'max' => 512],
            [['receipt_increment_alpahabetic_part'], 'string', 'max' => 51],
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
            'currency_id' => 'Select Currency',
            'receipt_increment_alpahabetic_part' => 'Receipt Alpahabetic Part',
            'receipt_increment_number_part' => 'Receipt Number Part',
            'date_format' => 'Date Format',
            'logo_to_be_printed' => 'Logo To Be Printed on Bill',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'tin_number' => 'Tin Number',
            'thumb_logo' => 'Thumb Logo',
            'receipt_note' => 'Receipt Note',
            'avatar_path' => 'Image path',
            'avatar_base_url' => 'Image Base Url',
            'trn' => 'TRN',
            'company_id' => 'Company ID',
            'country_code' => 'Country Code',
            'vat_type' => 'VAT Type',
            'vat_rate' => 'VAT Rate',
            'vat_rate_display' => 'VAT Rate',
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
    public function getEmployees()
    {
        return $this->hasMany(\backend\models\Employee::class, ['organisation_id' => 'id']);
    }
}
