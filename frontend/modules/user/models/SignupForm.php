<?php
namespace frontend\modules\user\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $organisation_size;
    public $password_confirm;
    public $firstname;
    public $lastname;
    public $business_domain_id;
    public $business_domain_other;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['business_domain_id', 'required'],
            ['lastname', 'required'],
            ['firstname', 'required'],
            ['organisation_size', 'required'],
            ['business_domain_other', 'required','when' => function() {
                return $this->business_domain_id == 6;
            }, 'enableClientValidation' => false],
           /* ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique',
                'targetClass'=>'\common\models\User',
                'message' => Yii::t('frontend', 'This username has already been taken.')
            ],*/
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique',
                'targetClass'=> '\common\models\User',
                'message' => Yii::t('frontend', 'This email address has already been taken.')
            ],

            ['password', 'required'],
            ['password_confirm', 'required'],
            ['password', 'string', 'min' => 6],
            ['password_confirm', 'compare', 'compareAttribute' => 'password', 'skipOnEmpty' => false],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>Yii::t('frontend', 'Username'),
            'email'=>Yii::t('frontend', 'E-mail'),
            'password'=>Yii::t('frontend', 'Password'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->email;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->save();
            $user->afterSignup();
            return $user;
        }

        return null;
    }
}
