<?php
namespace frontend\modules\user\models;

use Yii;
use common\models\User;
use yii\base\Model;
use backend\components\Helper;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'There is no user with such email.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if ($user) {
            $user->generatePasswordResetToken();
            if ($user->save()) {
                   $resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/user/sign-in/reset-password', 'token' => $user->password_reset_token]);
                    $email = $user->email;
                    $subject = 'Reset Password Link.';
                    $message = '
                    <p>Hello'.$user->username.',</p>
                    <p>Follow the link below to reset your password::</p>
                    <h3 style="font-size: 20px;"><a href="'.$resetLink.'" target="_blank"></h3>';
                    Helper::sendEmailViaSes('authverify@northmansterling.com', $email, null, $subject, $message, null, null, null);
            }
        }

        return false;
    }

    public function attributeLabels()
    {
        return [
            'email'=>Yii::t('frontend', 'Email')
        ];
    }
}
