<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Slip;
use common\models\Organisation;

/**
 * SlipSearch represents the model behind the search form about `backend\models\Slip`.
 */
class SlipSearch extends Slip
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_id', 'organisation_id', 'leaves_left', 'leaves_taken', 'current_salary'], 'integer'],
            [['payslip_month', 'payslip_year', 'start_date', 'end_date', 'description'], 'safe'],
            [['deduction', 'final_salary'], 'number'],
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
        $query = Slip::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $organizationId = Organisation::find()->where(['user_id'=>Yii::$app->user->identity->id])->one()->id;
        $query->andFilterWhere([
            'employee_id' => $this->employee_id,
            'organisation_id' =>  $organizationId,
            'payslip_year' => $this->payslip_year,
            'leaves_left' => $this->leaves_left,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'leaves_taken' => $this->leaves_taken,
            'current_salary' => $this->current_salary,
            'deduction' => $this->deduction,
            'final_salary' => $this->final_salary,
        ]);

        $query->andFilterWhere(['like', 'payslip_month', $this->payslip_month])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
