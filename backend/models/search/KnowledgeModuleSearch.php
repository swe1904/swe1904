<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\KnowledgeModule;

/**
 * KnowledgeModuleSearch represents the model behind the search form of `backend\models\KnowledgeModule`.
 */
class KnowledgeModuleSearch extends KnowledgeModule
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'case_type_id'], 'integer'],
            [['query', 'notes'], 'safe'],
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
        $query = KnowledgeModule::find();

        // add conditions that should always apply here
        $this->load($params);
        // filtering queries for only specific case_type_id
        $query->andFilterWhere([
            'case_type_id' => $params['case_type_id'],
        ])->joinWith('caseType');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions

        $query->andFilterWhere(['like', 'query', $this->query])
            ->andFilterWhere(['like', 'notes', $this->notes]);

        return $dataProvider;
    }
}
