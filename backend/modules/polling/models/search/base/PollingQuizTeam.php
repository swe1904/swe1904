<?php

namespace backend\modules\polling\models\search\base;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\polling\models\base\PollingQuizTeam as PollingQuizTeamModel;

/**
 * PollingQuizTeam represents the model behind the search form about `backend\modules\polling\models\base\PollingQuizTeam`.
 */
class PollingQuizTeam extends PollingQuizTeamModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'polling_quiz_id'], 'integer'],
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
        $query = PollingQuizTeamModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'polling_quiz_id' => $this->polling_quiz_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
