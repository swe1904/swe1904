<?php

namespace app\models;

use common\models\User as ModelsUser;
use Yii;
use yii\base\Model;
use user;

class PasswordResetRequestForm extends Model
{
    public $email;

    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'email'],
        ];
    }

    public function sendEmail()
    {
        $user = ModelsUser::findOne(['email' => $this->email, 'status' => ModelsUser::STATUS_ACTIVE]);

        if (!$user) {
            return false;
        }

        $user->generatePasswordResetToken();
        if (!$user->save()) {
            return false;
        }

        return Yii::$app->mailer->compose()
            ->setTo($this->email)
            ->setSubject('Password reset')
            ->send();
    }
}
