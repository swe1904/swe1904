<?php
namespace backend\models;

use app\components\GlobalConstant;
use common\models\User;
use common\models\UserProfile;
use yii\base\Model;
use Yii;
use yii\helpers\ArrayHelper;
use backend\controllers\GoogleAuthenticator;
use yii\db\ActiveRecord;

/**
 * Create user form
 */
class UserForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $status;
    public $roles;
    public $user_role;
    public $client_id;
    public $client_entity;
    public $fullname;
    public $emptbl_id;

    private $model;

    const CREATE_CLIENTS = 'create-clients';
    const UPDATE_CLIENTS = 'update-clients';
    const SCENARIO_CREATE_CLIENTS = self::CREATE_CLIENTS;
    const SCENARIO_UPDATE_CLIENTS = self::UPDATE_CLIENTS;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass'=>'\common\models\User', 'filter' => function ($query) {
                if (!$this->getModel()->isNewRecord) {
                    $query->andWhere(['not', ['id'=>$this->getModel()->id]]);
                }
            }],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            // ['email', 'unique', 'targetClass'=> '\common\models\User', 'filter' => function ($query) {
            //     if (!$this->getModel()->isNewRecord) {
            //         $query->andWhere(['not', ['id'=>$this->getModel()->id]]);
            //     }
            // }],

            ['password', 'required', 'on'=>'create'],
            ['password', 'string', 'min' => 6],

            [['status'], 'boolean'],
//            [['roles'], 'each',
//                'rule' => ['in', 'range' => ArrayHelper::getColumn(
//                    Yii::$app->authManager->getRoles(),
//                    'name'
//                )]
//            ],
            [['roles'], 'required'],
            [['two_factor_auth_google_token','enable_two_factor_auth','check_auth_login','two_factor_auth_qr_token','auth_type','emptbl_id','fullname'], 'safe'],
            ['client_id','integer'],
            [['password'], 'required', 'on' => self::SCENARIO_CREATE_CLIENTS],
          //  [['client_id'], 'required', 'on' => self::SCENARIO_UPDATE_CLIENTS],
            ['client_id', 'required',
                'when'       => function ($model) {
                    // if not creating Case-hr but is Clients
                    return (($model->scenario == self::SCENARIO_CREATE_CLIENTS) &&($model->roles == GlobalConstant::ROLE_CASE_WORKER) && ($model->roles == GlobalConstant::ROLE_ORGANISATION_MANAGER) && ($model->roles == GlobalConstant::ROLE_CLIENT_ENTITY_MANAGER) && ($model->roles == GlobalConstant::ROLE_CLIENT_GROUP_MANAGER));
                },
                'whenClient' => "function(attribute, value) {
                      return false;
                  }"
            ],
            ['client_entity', 'required',
                'when'       => function ($model) {
                    // if not creating Case-hr but is Clients
                    return (($model->scenario == self::SCENARIO_CREATE_CLIENTS) &&($model->roles == GlobalConstant::ROLE_CLIENT_ENTITY_MANAGER));
                },
                'whenClient' => "function(attribute, value) {
                      return false;
                  }"
            ],
            ['client_id', 'required',
                'when'       => function ($model) {
                    // if not creating Case-hr but is Clients
                    return (($model->scenario == self::SCENARIO_UPDATE_CLIENTS) &&($model->roles == GlobalConstant::ROLE_CASE_WORKER) && ($model->roles == GlobalConstant::ROLE_ORGANISATION_MANAGER) && ($model->roles == GlobalConstant::ROLE_CLIENT_ENTITY_MANAGER
                    ) );
                },
                'whenClient' => "function(attribute, value) {
                      return false;
                  }"
            ],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fullname' => Yii::t('backend', 'Full Name'),
            'username' => Yii::t('backend', 'Username'),
            'email' => Yii::t('backend', 'Email'),
            'password' => Yii::t('backend', 'Password'),
            'roles' => Yii::t('backend', 'Roles')

        ];
    }
    public function scenarios() {

        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_CREATE_CLIENTS] = ['client_id','client_entity','password','roles','username','email'];
        $scenarios[self::SCENARIO_UPDATE_CLIENTS] = ['client_id','client_entity','password','roles','username','email'];

        return $scenarios;

    }
    public function setModel($model)
    {
        $this->username = $model->username;
        $this->fullname = $model->fullname;
        $this->email = $model->email;
        $this->status = $model->status;
        $this->model = $model;
              $this->roles = ArrayHelper::getColumn(
            Yii::$app->authManager->getRolesByUser($model->getId()),
            'name'

        );
        $this->client_id=$model->client_id;
        $this->client_entity =$model->client_entity;
        return $this->model;
    }

    public function getModel()
    {
        if (!$this->model) {
            $this->model = new User();
        }
        return $this->model;
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    // public function save()
    // {   
      
    //        if ($this->validate()) {
    //         $model = $this->getModel();
    //         $isNewRecord = $model->getIsNewRecord();

    //         $model->username = $this->username;
    //         $model->email = $this->email;
    //         $model->status = $this->status;
    //         $model->client_id = $this->client_id;
    //         $model->client_entity = $this->client_entity;
    //         $model->fullname = $this->fullname;
    //         // $model->emptbl_id = $this->emptbl_id;
    //         $model->auth_type = 'email';
    //         $googleAuthenticator = new GoogleAuthenticator();
    //         $secret = $googleAuthenticator->createSecret();
    //         $model->two_factor_auth_google_token = $secret;
    //         $model->two_factor_auth_qr_token =Yii::$app->google2fa->generateSecretKey();
    //         if(!Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN)){
    //             // to create client/case-worker
    //         $organisationModel = \backend\models\Organisation::find()->where(['user_id' => Yii::$app->user->identity->id])->one();
    //            if (!empty($organisationModel) && $isNewRecord) {
    //                $model->organisation_id = $organisationModel->id;
    //                //$model->organisation_id=NULL;
    //            }
    //            // to create hr
    //            $organisation = \backend\models\Organisation::find()->where(['id' => Yii::$app->user->identity->organisation_id])->one();
    //            if (!empty($organisation) && $isNewRecord) {
    //                $model->organisation_id = $organisation->id;
    //            }
    //        }
    //         if ($this->password) {
    //             $model->setPassword($this->password);
    //         }
    //         if ($model->save() && $isNewRecord) {
    //             $model->afterSignup();
    //         }

    //         // adding/updating fullname in user profile
    //         if(isset($_POST['UserForm']['fullname']))
    //         {
    //             $firstName = null;
    //             $lastName = null;
    //             $userProfile = UserProfile::findOne($model->id);

    //             if($userProfile)
    //             {
    //                 if(!empty($_POST['UserForm']['fullname']))
    //                 {
    //                     $fullName = $_POST['UserForm']['fullname'];
                    
    //                     // list($firstName, $lastName) = explode(' ', $fullName, 2); //splitting first and last name after first space
    //                     $nameParts = explode(' ', $fullName, 2);

    //                     if (count($nameParts) < 2) {
    //                         $firstName = $nameParts[0];
    //                     } else {
    //                         list($firstName, $lastName) = $nameParts;
    //                     }                        
    //                 }
    //                 $userProfile->firstname = $firstName;
    //                 $userProfile->lastname = $lastName;
    //                 $userProfile->save();
    //             }               
    //         }

    //         $auth = Yii::$app->authManager;
    //         $auth->revokeAll($model->getId());

    //         if (!empty($this->roles)) {
    //             $role = $auth->getRole($this->roles);
    //             if ($role) {
    //                 $auth->assign($role, $model->getId());
    //             } else {
    //                 Yii::error("Role '{$this->roles}' does not exist in RBAC table.", __METHOD__);
    //             }
    //         }
            


    //         return !$model->hasErrors();
    //     }
    //     return null;
    // }
 public function save()
{
    if ($this->validate()) {
        $model = $this->getModel(); // New or existing user model
        $isNewRecord = $model->getIsNewRecord();

        // Assign basic attributes
        $model->username = $this->username;
        $model->email = $this->email;
        $model->status = $this->status;
        $model->client_id = $this->client_id;
        $model->client_entity = $this->client_entity;
        $model->fullname = $this->fullname;
        $model->auth_type = 'email';

        // 2FA setup only if new (optional logic; can be adjusted)
        if ($isNewRecord) {
            $googleAuthenticator = new GoogleAuthenticator();
            $model->two_factor_auth_google_token = $googleAuthenticator->createSecret();
            $model->two_factor_auth_qr_token = Yii::$app->google2fa->generateSecretKey();
        }

        // Set organisation_id only on create if not superadmin
        if (!Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN) && $isNewRecord) {
            $organisationModel = \backend\models\Organisation::find()
                ->where(['user_id' => Yii::$app->user->identity->id])
                ->one();
            $model->organisation_id = $organisationModel->id ?? Yii::$app->user->identity->organisation_id;
        }

        // Set hashed password if provided
        if ($this->password) {
            $model->setPassword($this->password);
        }

        // Save user model
        if ($model->save()) {
            if ($isNewRecord) {
                $model->afterSignup();
            }

            // ✅ Always update UserProfile firstname + lastname based on fullname
            if (!empty($this->fullname)) {
                $nameParts = explode(' ', trim($this->fullname), 2);
                $firstName = $nameParts[0] ?? '';
                $lastName = $nameParts[1] ?? '';

                $userProfile = UserProfile::findOne(['user_id' => $model->id]);
                if (!$userProfile) {
                    $userProfile = new UserProfile();
                    $userProfile->user_id = $model->id;
                }

                $userProfile->firstname = $firstName;
                $userProfile->lastname = $lastName;
                $userProfile->save(false); // Skip validation if fields are safe
            }

            // Handle role assignment
            $auth = Yii::$app->authManager;
            $auth->revokeAll($model->getId());
            if (!empty($this->roles)) {
                $role = $auth->getRole($this->roles);
                if ($role) {
                    $auth->assign($role, $model->getId());
                }
            }

            return true;
        } else {
            Yii::error("User save failed: " . json_encode($model->errors), __METHOD__);
        }
    }

    return false;
}



    public function hasRole($checkRole){
        if(!empty($this->model)){
            $roles = Yii::$app->authManager->getRolesByUser($this->model->id);
            foreach($roles as $role => $value){
                if($checkRole == $role)
                    return true;
            }
        }

        return false;
    }
}
