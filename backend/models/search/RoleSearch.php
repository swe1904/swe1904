<?php
namespace backend\models\search;
use backend\models\Role;
use yii\data\ActiveDataProvider;

class RoleSearch extends Role
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['role_name', 'description'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Role::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id])
              ->andFilterWhere(['like', 'role_name', $this->role_name])
              ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
