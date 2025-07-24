<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\PaypalRecurringPaymentsProfile;

/**
 * PaypalRecurringPaymentsProfileSearch represents the model behind the search form about `frontend\models\PaypalRecurringPaymentsProfile`.
 */
class PaypalRecurringPaymentsProfileSearch extends PaypalRecurringPaymentsProfile
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'receipt_id'], 'integer'],
            [['profileId', 'profileStatus', 'ack', 'payerId', 'token', 'timestamp'], 'safe'],
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
        $query = PaypalRecurringPaymentsProfile::find();

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
            'receipt_id' => $this->receipt_id,
            'timestamp' => $this->timestamp,
        ]);

        $query->andFilterWhere(['like', 'profileId', $this->profileId])
            ->andFilterWhere(['like', 'profileStatus', $this->profileStatus])
            ->andFilterWhere(['like', 'ack', $this->ack])
            ->andFilterWhere(['like', 'payerId', $this->payerId])
            ->andFilterWhere(['like', 'token', $this->token]);

        return $dataProvider;
    }
}
