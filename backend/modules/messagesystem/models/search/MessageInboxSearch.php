<?php

namespace backend\modules\messagesystem\models\search;

use backend\modules\messagesystem\models\MessageReadStatus;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\messagesystem\models\MessageInbox;
use yii\data\ArrayDataProvider;
use yii\db\Query;

/**
 * MessageInboxSearch represents the model behind the search form about `backend\modules\messagesystem\models\MessageInbox`.
 */
class MessageInboxSearch extends MessageInbox
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sender_id', 'receiver_id'], 'integer'],
            [['thread_id', 'message'], 'safe'],
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
        $query = MessageInbox::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        $this->load($params);

        $this->receiver_id=Yii::$app->user->id;
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'thread_id' => $this->thread_id,
            'message'=> $this->message
        ]);

       /* $query->andFilterWhere(['like', 'thread_id', $this->thread_id])
            ->andFilterWhere(['like', 'message', $this->message]);*/

        return $dataProvider;
    }
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ArrayDataProvider
     */
    public function search2($params)
    {

        $this->load($params);
        
        $query = (new Query())
            ->select('m1.*')
            ->from('tbl_message_inbox m1')
            ->join('LEFT JOIN', 'tbl_message_inbox m2', 'm1.thread_id = m2.thread_id AND m1.id < m2.id')
            ->where('m2.id IS NULL and (m1.sender_id=:sender_id or m1.receiver_id=:receiver_id)')
            ->orderBy("m1.id desc")
            ->params([':sender_id'=>Yii::$app->user->id,':receiver_id'=>Yii::$app->user->id]);

        if(!empty($this->message)) {
            $query->andFilterWhere(['like', 'm1.message', $this->message]);
        }
        $data=$query->all();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        return $dataProvider;
    }
}
