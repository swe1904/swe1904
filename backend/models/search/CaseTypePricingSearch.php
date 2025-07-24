<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CaseTypePricing;
use Yii;

/**
 * CaseTypePricingSearch represents the model behind the search form of `backend\models\CaseTypePricing`.
 */
class CaseTypePricingSearch extends CaseTypePricing
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'client_id', 'client_entity_id', 'currency_id', 'case_type_id', 'organisation_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
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
        $query = CaseTypePricing::find()->orderBy([
                'id'=>SORT_DESC
            ]);

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
        // Filter teh data as per logged in organisation-admin/manager 
        $query->andFilterWhere([ 
            'organisation_id' => Yii::$app->user->identity->organisation_id,
        ]);
        
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'client_id' => $this->client_id,
            'client_entity_id' => $this->client_entity_id,
            'currency_id' => $this->currency_id,
            'case_type_id' => $this->case_type_id,
            // 'organisation_id' => $this->organisation_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
