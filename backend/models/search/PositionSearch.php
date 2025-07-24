<?php
namespace backend\models\search;

use backend\models\Position;

// models/PositionSearch.php



use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


class PositionSearch extends Position
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Position::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
