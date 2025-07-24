<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ArticlePaypalOrder;

/**
 * ArticlePaypalOrderSearch represents the model behind the search form about `common\models\ArticlePaypalOrder`.
 */
class ArticlePaypalOrderSearch extends ArticlePaypalOrder
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'article_request_id', 'article_id', 'article_request_author_id', 'article_author_id', 'author_id'], 'integer'],
            [['amount', 'paymentId', 'created_at', 'updated_at'], 'safe'],
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
        $query = ArticlePaypalOrder::find();

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
            'article_request_id' => $this->article_request_id,
            'article_id' => $this->article_id,
            'article_request_author_id' => $this->article_request_author_id,
            'article_author_id' => $this->article_author_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'author_id' => $this->author_id,
        ]);

        $query->andFilterWhere(['like', 'amount', $this->amount])
            ->andFilterWhere(['like', 'paymentId', $this->paymentId]);

        return $dataProvider;
    }
}
