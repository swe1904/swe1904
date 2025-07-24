<?php

namespace backend\modules\payroll\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "tbl_zoho_auth_token".
 *
 * @property int $id
 * @property int $user_id
 * @property string $access_token
 * @property string $refresh_token
 * @property string $expires_on
 * @property string $scope
 *
 * @property User $user
 */
class ZohoAuthToken extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_zoho_auth_token';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'access_token', 'refresh_token', 'expires_on', 'scope'], 'required'],
            [['user_id'], 'integer'],
            [['expires_on'], 'safe'],
            [['access_token', 'refresh_token'], 'string', 'max' => 100],
            [['scope'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'access_token' => 'Access Token',
            'refresh_token' => 'Refresh Token',
            'expires_on' => 'Expires On',
            'scope' => 'Scope',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
