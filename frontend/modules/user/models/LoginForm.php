<?php
namespace frontend\modules\user\models;
use cheatsheet\Time;

use common\models\User;
use Yii;
use yii\base\Model;
use yii\web\ForbiddenHttpException;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $identity;
    public $password;
    public $rememberMe = true;

    private $user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['identity', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'identity'=>Yii::t('frontend', 'Username'),
            'password'=>Yii::t('frontend', 'Password'),
            'rememberMe'=>Yii::t('frontend', 'Remember me'),
        ];
    }


    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword()
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError('password', Yii::t('frontend', 'Incorrect username or password.'));
                $this->addError('identity', Yii::t('frontend', 'Incorrect username or password.'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
  public function login()
{
    if ($this->validate()) {
        // 60 * 60 * 24 * 30 = 2,592,000 seconds (approx. 1 month)
        $duration = $this->rememberMe ? 60 * 60 * 24 * 30 : 0;
        if (Yii::$app->user->login($this->getUser(), $duration)) {
            return true;
        }
    }
    return false;
}

//    public function login()
// {
//     if (!$this->validate()) {
//         return false;
//     }

//     $duration = $this->rememberMe ? 60 * 60 * 24 * 30 : 0;

//     if (Yii::$app->user->login($this->getUser(), $duration)) {
//         if (!Yii::$app->user->can('loginToBackend')) {
//             Yii::$app->user->logout();
//             throw new ForbiddenHttpException();
//         }
//         return true;
//     }

//     return false;
// }
    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->user === false) {
            $this->user = User::find()->where(['or', ['username'=>$this->identity], ['email'=>$this->identity]])->one();
        }

        return $this->user;
    }
}
