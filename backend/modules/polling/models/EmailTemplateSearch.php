<?php

namespace backend\modules\polling\models;

use app\components\GlobalConstant;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
/**
 * EmailTemplateSearch represents the model behind the search form about `backend\models\EmailTemplate`.
 */
class EmailTemplateSearch extends EmailTemplate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status_id'], 'integer'],
            [['name', 'to_email', 'to_name', 'subject', 'body', 'attachment'], 'safe'],
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
        $query = EmailTemplate::find();
        if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN ){
            $query->where(['user_id'=>Yii::$app->user->id]);
        }

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
            'event_id' => $this->status_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'to_email', $this->to_email])
            ->andFilterWhere(['like', 'to_name', $this->to_name])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'body', $this->body])
            ->andFilterWhere(['like', 'attachment', $this->attachment]);

        return $dataProvider;
    }
}
