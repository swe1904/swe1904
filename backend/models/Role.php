<?php 
namespace backend\models;
use Yii;

class Role extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_roles';
    }

    public function rules()
    {
        return [
            [['role_name'], 'required'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['role_name'], 'string', 'max' => 100],
            [['role_name'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_name' => 'Role Name',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
