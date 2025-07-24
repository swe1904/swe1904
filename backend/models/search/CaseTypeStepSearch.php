<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CaseTypeStep;

/**
 * CaseTypeStepSearch represents the model behind the search form about `backend\models\CaseTypeStep`.
 */
class CaseTypeStepSearch extends CaseTypeStep
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'case_type_id', 'number_of_days', 'order'], 'integer'],
            [['name'], 'safe'],
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
        $query = CaseTypeStep::find()->orderBy([
        //    'id' => SORT_ASC,
            'order'=>SORT_ASC
        ]);

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
            'case_type_id' => $this->case_type_id,
            'number_of_days' => $this->number_of_days,
            'order' => $this->order,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
