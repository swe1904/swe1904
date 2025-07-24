<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class AuthAssignment extends ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_rbac_auth_assignment'; // or 'tbl_rbac_auth_assignment' if that's the actual table
    }

    public function rules()
    {
        return [
            [['item_name', 'user_id'], 'required'],
            [['created_at'], 'integer'],
            [['item_name', 'user_id'], 'string', 'max' => 64],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
