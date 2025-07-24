<?php
// models/Position.php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

class Position extends ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_positions'; // Your table name
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
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'filter', 'filter' => function($value) {
                return ucwords(strtolower($value));  // Convert to proper case
            }],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Position Name',
        ];
    }

    
  
}
