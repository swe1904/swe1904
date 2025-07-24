<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DocumentRequest;

class DocumentRequestSearch extends DocumentRequest
{
    public function rules()
    {
        return [
            [['id', 'employee_id'], 'integer'],
            [['document_type', 'language_of_document'], 'safe'],
        ];
    }

    public function search($params, $perPage = 10)
    {
        $query = DocumentRequest::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $perPage,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'employee_id' => $this->employee_id,
        ]);

        $query->andFilterWhere(['like', 'document_type', $this->document_type])
              ->andFilterWhere(['like', 'language_of_document', $this->language_of_document]);

        return $dataProvider;
    }
}
