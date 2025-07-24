<?php
namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\BloodGroup;

/**
 * BloodGroupSearch represents the model behind the search form of `backend\models\BloodGroup`.
 */
class BloodGroupSearch extends BloodGroup
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = BloodGroup::find();

        // Add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // No need to apply filters if validation fails
            return $dataProvider;
        }

        // Apply filters
        $query->andFilterWhere(['id' => $this->id])
              ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
