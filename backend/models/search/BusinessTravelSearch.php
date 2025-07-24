<?php
namespace backend\models\search;
use backend\models\BusinessTravel;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class BusinessTravelSearch extends BusinessTravel
{
    public function rules()
    {
        return [
            [['id', 'employee_id'], 'integer'],
            [['country', 'from_date', 'to_date', 'reason', 'created_at'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = BusinessTravel::find()->with('employee');
        $dataProvider = new ActiveDataProvider(['query' => $query]);

        $this->load($params);
        if (!$this->validate()) return $dataProvider;

        $query->andFilterWhere(['id' => $this->id, 'employee_id' => $this->employee_id]);
        $query->andFilterWhere(['like', 'country', $this->country])
              ->andFilterWhere(['like', 'reason', $this->reason]);

        return $dataProvider;
    }
}
