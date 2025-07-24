<?php

namespace backend\models\search;

use app\components\GlobalConstant;
use backend\models\Client;
use backend\models\Organisation;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public $role;
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at', 'logged_at'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email','role','fullname'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     * @return ActiveDataProvider
     */
    public function search($params)
    {
    $query = User::find()->joinWith('userProfile');
    $dataProvider = new ActiveDataProvider([
        'query' => $query,
    ]);

    $this->load($params);

    if (!$this->validate()) {
        return $dataProvider;
    }

    $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'fullname', $this->fullname])
            ->andFilterWhere(['like', 'email', $this->email]);

    $auth = Yii::$app->authManager;

    // 🔹 Logged-in user ID
    $currentUserId = Yii::$app->user->id;
    $currentRoles = $auth->getRolesByUser($currentUserId);

    // 🔹 If Super Admin – show ONLY organisation-admin
    if (isset($currentRoles[GlobalConstant::ROLE_SUPERADMIN])) {
        $userIds = [];
        foreach (User::find()->all() as $user) {
            $roles = $auth->getRolesByUser($user->id);
            if (isset($roles[GlobalConstant::ROLE_ORGANISATION_ADMIN])) {
                $userIds[] = $user->id;
            }
        }
        $query->andWhere(['id' => $userIds]);
    }

    // 🔹 If HR or Organisation Admin – hide super-admin and organisation-admin
    elseif (
        isset($currentRoles[GlobalConstant::ROLE_HR_MANAGER]) ||
        isset($currentRoles[GlobalConstant::ROLE_DEPARTMENT_MANAGER]) ||
        isset($currentRoles[GlobalConstant::ROLE_ORGANISATION_ADMIN])
    ) {
        $userIds = [];
        foreach (User::find()->all() as $user) {
            $roles = $auth->getRolesByUser($user->id);
            // Include users who are NOT super-admin or organisation-admin
            if (
                !isset($roles[GlobalConstant::ROLE_SUPERADMIN]) &&
                !isset($roles[GlobalConstant::ROLE_ORGANISATION_ADMIN])
            ) {
                $userIds[] = $user->id;
            }
        }
        $query->andWhere(['id' => $userIds]);
    }

    return $dataProvider;
    }

    public function searchSuperadmin($params)
{
    $query = User::find()->joinWith('userProfile');

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'pagination' => [
            'pageSize' => 20,
        ],
        'sort' => [
            'defaultOrder' => ['id' => SORT_DESC],
        ],
    ]);

    $this->load($params);

    if (!$this->validate()) {
        return $dataProvider;
    }

    $query->andFilterWhere(['like', 'username', $this->username])
        ->andFilterWhere(['like', 'fullname', $this->fullname])
        ->andFilterWhere(['like', 'email', $this->email]);

    // ✅ No role or organisation restriction for Super Admin
    return $dataProvider;
}

    // public function search($params)
    // {
    //     $query = User::find()->joinWith('userProfile');
    //     $dataProvider = new ActiveDataProvider([
    //         'query' => $query,
    //     ]);

    //     $this->load($params);

    //     if (!$this->validate()) {
    //         return $dataProvider;
    //     }

    //     $query->andFilterWhere(['like', 'username', $this->username])
    //           ->andFilterWhere(['like', 'fullname', $this->fullname])
    //           ->andFilterWhere(['like', 'email', $this->email]);

    //     // 🔸 Role-based filtering
    //     $auth = Yii::$app->authManager;
    //     $currentUserId = Yii::$app->user->id;
    //     $currentRoles = $auth->getRolesByUser($currentUserId);

    //     $isHR = isset($currentRoles[GlobalConstant::ROLE_HR_MANAGER]);
    //     $isDeptManager = isset($currentRoles[GlobalConstant::ROLE_DEPARTMENT_MANAGER]);

    //     if (!($isHR || $isDeptManager)) {
    //         // All other roles can see only their own data
    //         $query->andWhere(['user.id' => $currentUserId]);
    //     }

    //     return $dataProvider;
    // }

}
