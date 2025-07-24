<?php
namespace backend\models\search;


use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Team;
use yii\data\Pagination; 

class TeamSearch extends Team
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name'], 'safe'],
        ];
    }

   
    public function search($params)
    {
        $query = Team::find();

        // Load search parameters
        $this->load($params);

        if ($this->name) {
            $query->andFilterWhere(['like', 'name', $this->name]);
        }

        // $pagination = new Pagination([
        //     'defaultPageSize' => $perPage, // Items per page
        //     'totalCount' => $query->count(),
        // ]);

        // $models = $query->offset($pagination->offset)
        //                 ->limit($pagination->limit)
        //                 ->all();

        return new ActiveDataProvider([
            'query' => $query,
            // 'pagination' => $pagination,
            // 'models' => $models,
        ]);
    }


}
