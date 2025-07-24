<?php

namespace backend\modules\polling\models\search\base;

use app\components\GlobalConstant;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\polling\models\PollingQuiz;

/**
 * PollingQuizSearch represents the model behind the search form about `backend\modules\polling\models\PollingQuiz`.
 */
class PollingQuizSearch extends PollingQuiz
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'type', 'quiz_reminder_is', 'is_deleted', 'master', 'disable_edit'], 'integer'],
            [['name', 'description', 'uuid', 'created_at'], 'safe'],
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
        $query = PollingQuiz::find();
        //$query = PollingQuiz::find()->joinWith('user');
        if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN ){
            $query->where(['user_id'=>Yii::$app->user->id]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'type' => $this->type,
            'quiz_reminder_is' => $this->quiz_reminder_is,
            'is_deleted' => $this->is_deleted,
            'master' => $this->master,
            'disable_edit' => $this->disable_edit,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'uuid', $this->uuid]);

        return $dataProvider;
    }
}
