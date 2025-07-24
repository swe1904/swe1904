<?php
namespace app\models;

use yii\base\Model;

class EnableTwoFactorForm extends Model
{
    public $code;

    public function rules()
    {
        return [
            [['code'], 'required'],
            [['code'], 'integer'],
        ];
    }
}
