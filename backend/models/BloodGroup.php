<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tbl_blood_group".
 *
 * @property int $id
 * @property string $name
 */
class BloodGroup extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_blood_group';
    }
    public function beforeSave($insert)
    {
        // Convert the 'name' field to uppercase before saving
        if (parent::beforeSave($insert)) {
            $this->name = strtoupper($this->name);
            return true;
        }
        return false;
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 5],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Blood Group',
        ];
    }

    public static function getBloodGroupList()
    {
        $bloodGroups = self::find()->select('name')->indexBy('id')->column();
        return $bloodGroups;
    }
}
