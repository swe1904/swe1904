<?php


namespace backend\models\search;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Nationality;
class NationalitySearch extends Nationality
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
        $query = Nationality::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id])
              ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
