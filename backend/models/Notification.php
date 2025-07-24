<?php

namespace backend\models;


use Yii;
use yii\db\ActiveRecord;

class Notification extends ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_notifications'; // Table name
    }
    
    public function rules()
    {
        return [
            [['from_user_id', 'title', 'message'], 'required'],
            [['record_id'], 'integer'],
            [['message'], 'string'],
            [['created_at','to_user_id','is_read','hr_user_id'], 'safe'],
            [['title', 'table_name', 'link'], 'string', 'max' => 255],
            // [['is_read'], 'boolean'],
        ];
    }

    public function getFromUser()
{
    return $this->hasOne(Employee::class, ['user_id' => 'from_user_id']);
}

public function getToUser()
{
    return $this->hasOne(Employee::class, ['user_id' => 'to_user_id']);
}
}
