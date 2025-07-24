<?php

namespace frontend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Client;

/**
 * ClientSearch represents the model behind the search form about `frontend\models\Client`.
 */
class ClientSearch extends Client
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['first_name_fixed', 'last_name_fixed', 'phone_fixed', 'address_fixed', 'text_1528808645886', 'select_1528809495736', 'date_1528809715690', 'date_1528810280939'], 'safe'],
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
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Client::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'date_1528809715690' => $this->date_1528809715690,
            'date_1528810280939' => $this->date_1528810280939,
        ]);

        $query->andFilterWhere(['like', 'first_name_fixed', $this->first_name_fixed])
            ->andFilterWhere(['like', 'last_name_fixed', $this->last_name_fixed])
            ->andFilterWhere(['like', 'phone_fixed', $this->phone_fixed])
            ->andFilterWhere(['like', 'address_fixed', $this->address_fixed])
            ->andFilterWhere(['like', 'text_1528808645886', $this->text_1528808645886])
            ->andFilterWhere(['like', 'select_1528809495736', $this->select_1528809495736]);

        return $dataProvider;
    }
}
