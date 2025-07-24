<?php
namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\EmergencyContactRelationship;
use yii\data\Pagination; 

class EmergencyContactRelationshipSearch extends EmergencyContactRelationship
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['relationship_name'], 'safe'],
        ];
    }

    public function search($params, $perPage = 10)
    {
        $query = EmergencyContactRelationship::find();

        // Load search parameters
        $this->load($params);

        if ($this->relationship_name) {
            $query->andFilterWhere(['like', 'relationship_name', $this->relationship_name]);
        }

        $pagination = new Pagination([
            'defaultPageSize' => $perPage, // Items per page
            'totalCount' => $query->count(),
        ]);

        $models = $query->offset($pagination->offset)
                        ->limit($pagination->limit)
                        ->all();

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => $pagination,
            'models' => $models,
        ]);
    }
}
