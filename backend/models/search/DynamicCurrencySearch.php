<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DynamicCurrency;

/**
 * DynamicCurrencySearch represents the model behind the search form of `backend\models\DynamicCurrency`.
 */
class DynamicCurrencySearch extends DynamicCurrency
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'currency_id'], 'integer'],
            [['conversion_rate_to_SAR'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = DynamicCurrency::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'currency_id' => $this->currency_id,
            'conversion_rate_to_SAR' => $this->conversion_rate_to_SAR,
        ]);

        return $dataProvider;
    }
}
