<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tbl_client_entity".
 *
 * @property int $id
 * @property int $client_id
 * @property string $name
 * @property string $email
 * @property string $country
 * @property string $phone
 * @property string|null $additional_attachments
 * @property string $company_vat
 * @property string|null $address
 *
 * @property Client $client
 */
class ClientEntity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_client_entity';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['client_id', 'name', 'cr_number', 'unified_national_number'], 'required'],
            // [['client_id', 'name', 'cr_number'], 'required'],
            [['client_id', 'name','country','phone','email','company_vat'], 'required'],
            [['client_id'], 'integer'],
            ['email','email'],
            //[['name', 'address', 'cr_number', 'unified_national_number'], 'string', 'max' => 255],
             [['name', 'address', 'additional_attachments'], 'string', 'max' => 255],
            [['name', 'address'], 'string', 'max' => 255],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::class, 'targetAttribute' => ['client_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'client_id' => Yii::t('backend', 'Client ID'),
            //'name' => Yii::t('backend', 'Name'),
            'name' => Yii::t('backend', 'Entity Name *'),
            'address' => Yii::t('backend', 'Address'),
            'country' => Yii::t('backend', 'Country'),
            'phone' => Yii::t('backend', 'Phone'),
            'email' => Yii::t('backend', 'Email'),
//            'company_vat' => Yii::t('backend', 'Company vat'),Yii::t('backend', 'Company vat'),
           'company_vat' => 'Company vat',
            'additional_attachments' => Yii::t('backend', 'Attachments'),
            //'cr_number' => Yii::t('backend', 'CR Number'),
            // 'cr_number' => Yii::t('backend', 'License Number / Commercial Registration Number *'),
            
        ];
    }

    /**
     * Gets query for [[Client]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::class, ['id' => 'client_id']);
    }
}
