<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tbl_client_organisation".
 *
 * @property int $id
 * @property int $client_id
 * @property int $organisation_id
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Client $client
 * @property Organisation $organisation
 */
class ClientOrganisation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_client_organisation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'organisation_id'], 'required'],
            [['client_id', 'organisation_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::class, 'targetAttribute' => ['client_id' => 'id']],
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
            'client_id' => 'Client ID',
            'organisation_id' => 'Organisation ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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