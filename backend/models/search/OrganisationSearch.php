<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Organisation;

/**
 * OrganisationSearch represents the model behind the search form about `common\models\Organisation`.
 */
class OrganisationSearch extends Organisation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'mobile', 'currency_id', 'logo_to_be_printed'], 'integer'],
            [['name', 'tagline', 'address', 'landline', 'email', 'website', 'logo', 'service_tax_number', 'receipt_increment_alpahabetic_part', 'receipt_increment_number_part', 'date_format'], 'safe'],
            [['service_tax_percentage'], 'number'],
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
        $query = Organisation::find()->where('user_id=:user_id',[':user_id'=>yii::$app->user->id]);

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
            'user_id' => $this->user_id,
            'mobile' => $this->mobile,
            'service_tax_percentage' => $this->service_tax_percentage,
            'currency_id' => $this->currency_id,
            'logo_to_be_printed' => $this->logo_to_be_printed,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'tagline', $this->tagline])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'landline', $this->landline])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'website', $this->website])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'service_tax_number', $this->service_tax_number])
            ->andFilterWhere(['like', 'receipt_increment_alpahabetic_part', $this->receipt_increment_alpahabetic_part])
            ->andFilterWhere(['like', 'receipt_increment_number_part', $this->receipt_increment_number_part])
            ->andFilterWhere(['like', 'date_format', $this->date_format]);

        return $dataProvider;
    }
}
