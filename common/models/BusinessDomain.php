<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%business_domain}}".
 *
 * @property integer $id
 * @property string $name
 *
 * @property UserProfile[] $userProfiles
 */
class BusinessDomain extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%business_domain}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfiles()
    {
        return $this->hasMany(UserProfile::className(), ['business_domain_id' => 'id']);
    }
}
