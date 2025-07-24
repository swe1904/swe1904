<?php
namespace backend\models\search;

use backend\models\Payslip;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PayslipSearch represents the model behind the search form of `app\models\Payslip`.
 */
class PayslipSearch extends Payslip
{
    public function rules()
    {
        return [
            [['id', 'employee_id'], 'integer'],
            [['pay_period'], 'safe'],
            [['basic_salary', 'allowances', 'deductions', 'net_salary'], 'number'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Payslip::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'pay_period' => $this->pay_period,
            'basic_salary' => $this->basic_salary,
            'allowances' => $this->allowances,
            'deductions' => $this->deductions,
            'net_salary' => $this->net_salary,
        ]);

        return $dataProvider;
    }
}
