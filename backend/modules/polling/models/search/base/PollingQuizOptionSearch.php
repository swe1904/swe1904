<?php

namespace backend\modules\polling\models\search\base;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\polling\models\PollingQuizOption;

/**
 * PollingQuizOptionSearch represents the model behind the search form about `backend\modules\polling\models\PollingQuizOption`.
 */
class PollingQuizOptionSearch extends PollingQuizOption
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'polling_quiz_question_id', 'order'], 'integer'],
            [['value', 'explanation'], 'safe'],
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
        $query = PollingQuizOption::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'polling_quiz_question_id' => $this->polling_quiz_question_id,
            'order' => $this->order,
        ]);

        $query->andFilterWhere(['like', 'value', $this->value])
            ->andFilterWhere(['like', 'explanation', $this->explanation]);

        return $dataProvider;
    }
}
