<?php

namespace backend\modules\polling\models\search\base;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\polling\models\PollingQuizQuestionAnswer;

/**
 * PollingQuizQuestionAnswerSearch represents the model behind the search form about `backend\modules\polling\models\PollingQuizQuestionAnswer`.
 */
class PollingQuizQuestionAnswerSearch extends PollingQuizQuestionAnswer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'participant_id', 'polling_quiz_question_id'], 'integer'],
            [['answer'], 'safe'],
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
        $query = PollingQuizQuestionAnswer::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'participant_id' => $this->participant_id,
            'polling_quiz_question_id' => $this->polling_quiz_question_id,
        ]);

        $query->andFilterWhere(['like', 'answer', $this->answer]);

        return $dataProvider;
    }
}
