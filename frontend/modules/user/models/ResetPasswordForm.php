<?php
namespace frontend\modules\user\models;

use common\models\User;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;

    /**
     * @var \common\models\User
     */
    private $user;

    /**
     * Creates a form model given a token.
     *
     * @param  string                          $token
     * @param  array                           $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
{
    if (empty($token) || !is_string($token)) {
        Yii::$app->session->setFlash('error', 'Password reset token cannot be blank.');
        Yii::$app->response->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl)->send();
        exit; // Ensure the script terminates after the redirect
    }
    $this->user = User::findByPasswordResetToken($token);
    if (!$this->user) {
        Yii::$app->session->setFlash('error', 'The reset password link has expired. Please click on forgot password again.');
        Yii::$app->response->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl)->send();
        exit; // Ensure the script terminates after the redirect
    }
    parent::__construct($config);
}

    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->user;
        $user->password = $this->password;
        $user->removePasswordResetToken();

        return $user->save();
    }

    public function attributeLabels()
    {
        return [
            'password'=>Yii::t('frontend', 'New Password')
        ];
    }
}
