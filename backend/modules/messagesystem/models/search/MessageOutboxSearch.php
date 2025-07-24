<?php

namespace backend\modules\messagesystem\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\messagesystem\models\MessageOutbox;

/**
 * MessageOutboxSearch represents the model behind the search form about `backend\modules\messagesystem\models\MessageOutbox`.
 */
class MessageOutboxSearch extends MessageOutbox
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sender_id', 'receiver_id'], 'integer'],
            [['private_id', 'message'], 'safe'],
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
        $query = MessageOutbox::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        $this->sender_id=133;

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'sender_id' => $this->sender_id,
        ]);

        /*$query->andFilterWhere(['like', 'private_id', $this->private_id])
            ->andFilterWhere(['like', 'message', $this->message]);*/

        return $dataProvider;
    }
}
