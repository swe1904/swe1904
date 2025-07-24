<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%currency}}".
 *
 * @property integer $id
 * @property string $iso
 * @property string $name
 *
 * @property Organisation[] $organisations
 */
class Currency extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%currency}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['iso'], 'string', 'max' => 3],
            [['name'], 'string', 'max' => 200],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'iso' => 'Iso',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganisations()
    {
        return $this->hasMany(Organisation::className(), ['currency_id' => 'id']);
    }
}
