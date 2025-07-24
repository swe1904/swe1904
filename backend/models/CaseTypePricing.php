<?php

namespace backend\models;

use Yii;
use backend\models\Client;
use backend\models\ClientEntity;
use backend\models\Currency;
use backend\models\Organisation;
use backend\models\CaseType;


/**
 * This is the model class for table "tbl_case_type_pricing".
 *
 * @property int $id
 * @property int $client_id
 * @property int $client_entity_id
 * @property int $currency_id
 * @property int $case_type_id
 * @property int $organisation_id
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property CaseType $caseType
 * @property Client $client
 * @property ClientEntity $clientEntity
 * @property Currency $currency
 * @property Organisation $organisation
 */
class CaseTypePricing extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_case_type_pricing';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'client_entity_id', 'currency_id', 'case_type_id', 'organisation_id'], 'required'],
            [['client_id', 'client_entity_id', 'currency_id', 'case_type_id', 'organisation_id'], 'integer'],
            [['client_id', 'client_entity_id', 'case_type_id', 'organisation_id'], 'unique', 'targetAttribute' => ['client_id', 'client_entity_id', 'case_type_id', 'organisation_id']],
            [['created_at', 'updated_at'], 'safe'],
            [['case_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => CaseType::class, 'targetAttribute' => ['case_type_id' => 'id']],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::class, 'targetAttribute' => ['client_id' => 'id']],
            [['client_entity_id'], 'exist', 'skipOnError' => true, 'targetClass' => ClientEntity::class, 'targetAttribute' => ['client_entity_id' => 'id']],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::class, 'targetAttribute' => ['currency_id' => 'id']],
            [['organisation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organisation::class, 'targetAttribute' => ['organisation_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => 'Client',
            'client_entity_id' => 'Client Entity',
            'currency_id' => 'Currency',
            'case_type_id' => 'Case Type',
            'organisation_id' => 'Organisation',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[CaseType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCaseType()
    {
        return $this->hasOne(CaseType::class, ['id' => 'case_type_id']);
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

    /**
     * Gets query for [[ClientEntity]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClientEntity()
    {
        return $this->hasOne(ClientEntity::class, ['id' => 'client_entity_id']);
    }

    /**
     * Gets query for [[Currency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::class, ['id' => 'currency_id']);
    }

    /**
     * Gets query for [[Organisation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganisation()
    {
        return $this->hasOne(Organisation::class, ['id' => 'organisation_id']);
    }
}
