<?php
namespace backend\models\search;

use backend\models\PayrollRun;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class PayrollRunSearch extends PayrollRun
{
    public function rules()
    {
        return [
            [['id', 'payroll_month', 'payroll_year', 'total_employees'], 'integer'],
            [['total_amount_paid', 'total_social_insurance', 'total_income_tax'], 'number'],
            [['created_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = PayrollRun::find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        if (!$this->load($params) || !$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'payroll_month' => $this->payroll_month,
            'payroll_year' => $this->payroll_year,
            'total_employees' => $this->total_employees,
            'total_amount_paid' => $this->total_amount_paid,
            'total_social_insurance' => $this->total_social_insurance,
            'total_income_tax' => $this->total_income_tax,
        ]);

        $query->andFilterWhere(['like', 'created_at', $this->created_at]);

        return $dataProvider;
    }
}
