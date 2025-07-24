<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Employee;
use backend\models\LeaveRequest;
use common\models\Organisation;

/**
 * EmployeeSearch represents the model behind the search form about `app\models\Employee`.
 */
class EmployeeSearch extends Employee
{
    public $organisation_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'date_of_birth','place_of_birth','blood_group', 'date_of_joining','current_residential_address','permanent_address','preferred_first_name','preferred_full_name','preferred_full_name','emergency_contact_relationship','emergency_mobile_number','emergency_contact_email','department_manager','notice_period', 'probation_period_completion_date','employment_type','contract_type','annual_leave','iban_number','swift_code','bank_country','currency_id','salary_frequency_id','country_manager_id','branch_name','user_id', 'position'], 'safe'],
            [['id',  'salary'], 'integer'],
             [['preferred_full_name', 'position', 'organisation_id', 'country_of_legal_residence'], 'safe'],
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
    // public function search($params)
    // {
    //     $query = Employee::find(); // Fetch employees directly
    
    //     // Fetch organization ID from parameters
    //     // if (!empty($params['EmployeeSearch']['organisation_id'])) {
    //     //     $query->andWhere(['organisation_id' => $params['EmployeeSearch']['organisation_id']]);
    //     // }
    
    //     // Load search parameters
    //     $this->load($params);
    
    //     if (!$this->validate()) {
    //         return new ActiveDataProvider([
    //             'query' => $query->where('0=1'), // No results if validation fails
    //         ]);
    //     }
    
    //     // Apply filters
    //     $query->andFilterWhere(['employee_id' => $this->employee_id])
    //           ->andFilterWhere(['like', 'first_name', $this->first_name])
    //           ->andFilterWhere(['like', 'preferred_full_name', $this->preferred_full_name])
    //           ->andFilterWhere(['like', 'position', $this->position])
    //           ->andFilterWhere(['like', 'country', $this->country]);
    
    //     // Debugging - Remove in production
    //     // echo $query->createCommand()->getRawSql(); exit;
    
    //     return new ActiveDataProvider([
    //         'query' => $query,
    //         'pagination' => [
    //             'pageSize' => $params['pageSize'] ?? 10, // Dynamic page size
    //         ],
    //     ]);
    // }
    
    
public function search($params)
{
    $query = Employee::find()
        ->joinWith(['organisation', 'countryOfLegalResidence', 'positionName']); // Ensure this relation exists

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'pagination' => ['pageSize' => $params['pageSize'] ?? 10],
    ]);

    $this->load($params);

    if (!$this->validate()) {
        $query->where('0=1');
        return $dataProvider;
    }

    $query->andFilterWhere(['like', 'preferred_full_name', $this->preferred_full_name]);

    // ✅ Correct filter by table and column
    $query->andFilterWhere(['like', 'tbl_positions.name', $this->position]);

    $query->andFilterWhere(['employee.organisation_id' => $this->organisation_id]);
    $query->andFilterWhere(['employee.country_of_legal_residence' => $this->country_of_legal_residence]);

    return $dataProvider;
}


    

}
