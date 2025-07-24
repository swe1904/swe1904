<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CaseSteps;

/**
 * CaseStepsSearch represents the model behind the search form about `\backend\models\CaseSteps`.
 */
class CaseStepsSearch extends CaseSteps
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'case_id', 'case_type_step_id', 'status' ,'order'], 'integer'],
            [['planned_completion_date', 'actual_completion_date'], 'safe'],
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
        $query = CaseSteps::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
            'defaultOrder' => ['order' => SORT_ASC],  
        ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'case_id' => $this->case_id,
            'case_type_step_id' => $this->case_type_step_id,
            'planned_completion_date' => $this->planned_completion_date,
            'actual_completion_date' => $this->actual_completion_date,
            'status' => $this->status,
            'order' => $this->order,
        ]);

        return $dataProvider;
    }
}
