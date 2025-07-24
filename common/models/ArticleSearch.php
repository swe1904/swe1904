<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ArticleTmp;

/**
 * ArticleSearch represents the model behind the search form about `common\models\ArticleTmp`.
 */
class ArticleSearch extends ArticleTmp
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'article_request_id', 'author_id'], 'integer'],
            [['body'], 'safe'],
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
        $query = ArticleTmp::find();

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
            'author_id' => $this->author_id,
        ]);

        $query->andFilterWhere(['like', 'body', $this->body]);

        return $dataProvider;
    }
}
