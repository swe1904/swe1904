<?php

namespace backend\modules\polling\models\search\base;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\polling\models\PollingQuizQuestion;

/**
 * PollingQuizQuestionSearch represents the model behind the search form about `backend\modules\polling\models\PollingQuizQuestion`.
 */
class PollingQuizQuestionSearch extends PollingQuizQuestion
{
    /**
     * @inheritdoc
     */
    public $questionType;
    public function rules()
    {
        return [
            [['id', 'polling_quiz_id', 'type', 'order', 'action', 'action_compare', 'action_compare_radio', 'action_compare_text', 'visible', 'visible_quiz_question_id', 'visible_compare'], 'integer'],
            [['title', 'question', 'action_value', 'visible_value','questionType'], 'safe'],
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
        $query = PollingQuizQuestion::find()->where('polling_quiz_id=:polling_quiz_id',[':polling_quiz_id'=>$params['id']]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->sort->attributes['questionType'] = [
            'asc' => ['pollingQuizQuestionType.name' => SORT_ASC],
            'desc' => ['pollingQuizQuestionType.name' => SORT_DESC],
        ];
        $query->joinWith('pollingQuizQuestionType');

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'polling_quiz_id' => $this->polling_quiz_id,
            'type' => $this->type,
            'order' => $this->order,
            'action' => $this->action,
            'action_compare' => $this->action_compare,
            'action_compare_radio' => $this->action_compare_radio,
            'action_compare_text' => $this->action_compare_text,
            'visible' => $this->visible,
            'visible_quiz_question_id' => $this->visible_quiz_question_id,
            'visible_compare' => $this->visible_compare,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'question', $this->question])
            ->andFilterWhere(['like', 'action_value', $this->action_value])
            ->andFilterWhere(['like', 'visible_value', $this->visible_value])
            ->andFilterWhere(['like', 'tbl_polling_quiz_question_type.name', $this->questionType]);

        return $dataProvider;
    }
}
