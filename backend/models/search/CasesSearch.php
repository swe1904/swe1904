<?php

namespace backend\models\search;

use app\components\GlobalConstant;
use backend\models\Applicant;
use backend\models\Organisation;
use backend\models\Client;
use common\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Cases;
use yii\helpers\ArrayHelper;

/**
 * CasesSearch represents the model behind the search form about `backend\models\Cases`.
 */
class CasesSearch extends Cases
{

    public $case_manager_id;
    public $client_billing_entity;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['id', 'case_type_id', 'applicant_id','mobile_number','over_all_status', 'mID', 'assigned_to', 'case_manager_id','organisation_id','client_id','client_case_worker_id','client_case_manager_id'], 'integer'],
            [['case_number', 'client_billing_entity','target_completion_date', 'sending_country', 'receiving_country', 'applicant_last_name', 'applicant_first_name', 'date_of_birth', 'passport_number', 'office_address', 'client_name'], 'safe'],
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
        $query = Cases::find()->joinWith('applicant');
        if ($params && array_key_exists('unassigned', $params) && $params['unassigned'] == "true") {
            $query->andWhere('assigned_to IS NULL');
        } 
        
        if (isset($params['CasesSearch']['case_status'])) {
            $query->andFilterWhere(['case_status' => $params['CasesSearch']['case_status']]);
        }
        if (isset($params['case_status'])) {
            if ($params['case_status'] == 'null' || $params['case_status'] == null) {
                $query->andWhere(['case_status' => null]);
            } else {
                $query->andFilterWhere(['case_status' => $params['case_status']]);
            }
        }
        //filtering on from_date and to_date
        if (isset($params['CasesSearch']['from_date']) || isset($params['CasesSearch']['to_date'])) {
            //setting to_date to today if empty
            if (empty($params['CasesSearch']['to_date'])) {
                $params['CasesSearch']['to_date'] = date('Y-m-d H:i:s', strtotime(date('Y-m-d')));
            }

            //the to_date was not inclusive so adding 1 day
            $params['CasesSearch']['to_date'] = date('Y-m-d H:i:s', strtotime('+1 day', strtotime(date($params['CasesSearch']['to_date']))));

            //setting from_date to minimum value in case of empty
            if (empty($params['CasesSearch']['from_date'])) {
                $params['CasesSearch']['from_date'] = date('Y-m-d H:i:s', strtotime('1970-01-01'));
            }

            if ($params['CasesSearch']['from_date'] <= $params['CasesSearch']['to_date']) {
                $query->andWhere(['between', 'tbl_cases.created_at', $params['CasesSearch']['from_date'], $params['CasesSearch']['to_date']]);
            }
        }

        //filtering based on client id
        if (isset($params['CasesSearch']['client_id'])) { 
            $clientId = $params['CasesSearch']['client_id'];
           
            if (!empty($clientId)) {
                $query->andWhere(['tbl_cases.client_id' => $clientId]); 
            }
        }
        if (isset($params['CasesSearch']['client_name'])) { 
            $clientId = $params['CasesSearch']['client_name'];
          
            if (!empty($clientId)) {
                $query->andWhere(['tbl_cases.client_id' => $clientId]); 
            }
        }
        
      

        if (isset($params['CasesSearch']['client_entity'])) {
        
            $query->andWhere(['client_entity' => $params['CasesSearch']['client_entity']]);
        }   


        if (isset($params['CasesSearch']['case_worker'])) {
            $query->andWhere(['assigned_to' => $params['CasesSearch']['case_worker']]);
        }
        if (isset($params['CasesSearch']['case_manager_id'])) {
            $query->andFilterWhere(['case_manager_id' => $params['CasesSearch']['case_manager_id']]);
        }
         // Filter by applicant_id
    if (!empty($params['applicant_id'])) {
        $query->andWhere(['applicant_id' => $params['applicant_id']]);
    }
    if (isset($params['client_case_worker_id'])) {
        $query->andFilterWhere(['client_case_worker_id' => $params['client_case_worker_id']]);
    }
    if (isset($params['CasesSearch']['client_case_manager_id'])) {
        $query->andFilterWhere(['client_case_manager_id' => $params['CasesSearch']['client_case_manager_id']]);
    }
    if (isset($params['CasesSearch']['client_case_worker_id'])) {
        $query->andFilterWhere(['client_case_worker_id' => $params['CasesSearch']['client_case_worker_id']]);
    }
         // Filter by is_receipt
    if (isset($params['is_receipt'])) {
        $isReceipt = $params['is_receipt'];
        $receiptQuery = (new \yii\db\Query())->select('tbl_receipt.case_id')->from('tbl_receipt');
    
        if ($isReceipt == 'Invoiced') {
            $receiptQuery->andWhere(['tbl_receipt.is_receipt' => 0]);
            $caseIds = $receiptQuery->column();
            $query->andWhere(['tbl_cases.id' => $caseIds]);
        } elseif ($isReceipt == 'Receipt') {
            $receiptQuery->andWhere(['tbl_receipt.is_receipt' => 1]);
            $caseIds = $receiptQuery->column();
            $query->andWhere(['tbl_cases.id' => $caseIds]);
        } elseif ($isReceipt == 'NoInvoice') {
            $caseIds = $receiptQuery->column();
            $query->andWhere(['NOT IN', 'tbl_cases.id', $caseIds]);
        }
    }

    if (isset($params['is_receipt']) && $params['is_receipt'] == 'NoInvoice') {
        $query->andWhere(['NOT EXISTS', (new \yii\db\Query())
            ->from('tbl_receipt')
            ->where('tbl_receipt.case_id = tbl_cases.id')
        ]);
    }

        if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT||Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_HR ){
           // $query->where(['client_id'=>Yii::$app->user->id]);
            // $query->andWhere(['in', 'applicant.client_id',ArrayHelper::getColumn(User::find()->where(['id'=>Yii::$app->user->id])->all(),'applicant.client_id')]);
            $query->andWhere(['tbl_cases.client_id'=> Yii::$app->user->identity->client_id]);
        }
        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_WORKER){
            // $clients= User::find()->where(['organisation_id'=>Yii::$app->user->identity->organisation_id])->andWhere(['not',['applicant.client_id'=>NULL]])->all();
            $query->andWhere('assigned_to = :id', [':id' => Yii::$app->user->id]);
            // $query->andWhere(['in', 'tbl_cases.client_id',ArrayHelper::getColumn($clients,'client_id')]);
        }
        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_MANAGER){

             $clients= Client::find()
                ->leftJoin('tbl_client_organisation', 'tbl_client_organisation.client_id = client.id')
                ->andWhere(['tbl_client_organisation.organisation_id' => Yii::$app->user->identity->organisation_id])->all();
        // $clients = User::find()->where(['organisation_id' => Yii::$app->user->identity->organisation_id])->andWhere(['not', ['tbl_user.client_id' => NULL]])->all();
            // $organizationCondition = ['in', 'tbl_cases.client_id', ArrayHelper::getColumn($clients, 'client_id')];
            $organizationCondition = ['in', 'tbl_cases.client_id', ArrayHelper::getColumn($clients, 'id')];
        
            $caseManagerCondition = [
                'or',
                ['case_manager_id' => Yii::$app->user->id],
                ['and', ['case_manager_id' => null], $organizationCondition],
            ];
        
            $query->andWhere($caseManagerCondition);
        }
        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_CASE_MANAGER){
            // $clients= User::find()->where(['organisation_id'=>Yii::$app->user->identity->organisation_id])->andWhere(['not',['applicant.client_id'=>NULL]])->all();
            $query->andWhere(['client_case_manager_id' => Yii::$app->user->id]);
            // $query->andWhere(['in', 'tbl_cases.client_id',ArrayHelper::getColumn($clients,'client_id')]);
        }
        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_CASE_WORKER){
            // $clients= User::find()->where(['organisation_id'=>Yii::$app->user->identity->organisation_id])->andWhere(['not',['applicant.client_id'=>NULL]])->all();
            $query->andWhere(['client_case_worker_id' => Yii::$app->user->id]);
            // $query->andWhere(['in', 'tbl_cases.client_id',ArrayHelper::getColumn($clients,'client_id')]);
        }
        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_FINANCE){
            // $clients= User::find()->where(['organisation_id'=>Yii::$app->user->identity->organisation_id])->andWhere(['not',['client_id'=>NULL]])->all();
            $clients= Client::find()
            ->leftJoin('tbl_client_organisation', 'tbl_client_organisation.client_id = client.id')
            ->andWhere(['tbl_client_organisation.organisation_id' => Yii::$app->user->identity->organisation_id])->all();
            $query->andWhere(['is_sent_for_billing' => 1]);
            $query->andWhere(['in', 'tbl_cases.client_id',ArrayHelper::getColumn($clients,'id')]);
        }
        elseif (Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_MANAGER) {
            // $clients = User::find()->where(['organisation_id' => User::findOne(Yii::$app->user->id)->organisation_id])->andWhere(['not', ['client_id' => NULL]])->all();
            $clients= Client::find()
            ->leftJoin('tbl_client_organisation', 'tbl_client_organisation.client_id = client.id')
            ->andWhere(['tbl_client_organisation.organisation_id' => Yii::$app->user->identity->organisation_id])->all();
            $query->andWhere(['in', 'tbl_cases.client_id', ArrayHelper::getColumn($clients, 'id')]);
            $query->andWhere(['tbl_cases.organisation_id' => Yii::$app->user->identity->organisation_id]);
        }
        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN){
            // $clients= User::find()->where(['organisation_id'=>Organisation::find()->where(['user_id'=>Yii::$app->user->id])->one()->id])->andWhere(['not',['client_id'=>NULL]])->all();
            $clients= Client::find()
            ->leftJoin('tbl_client_organisation', 'tbl_client_organisation.client_id = client.id')
            ->andWhere(['tbl_client_organisation.organisation_id' => Yii::$app->user->identity->organisation_id])->all();
            $query->andWhere(['in', 'tbl_cases.client_id',ArrayHelper::getColumn($clients,'id')]);
            $query->andWhere(['tbl_cases.organisation_id' => Yii::$app->user->identity->organisation_id]);
        }
        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_ENTITY_MANAGER){         
            $clients= Client::find()
            ->leftJoin('tbl_client_organisation', 'tbl_client_organisation.client_id = client.id')
            ->andWhere(['tbl_client_organisation.organisation_id' => Yii::$app->user->identity->organisation_id])->all();
            $clientIds = ArrayHelper::getColumn($clients, 'id');

            // Fetch only cases where client_id and client_entity match the user's assigned values
            $query->andWhere(['in', 'tbl_cases.client_id', $clientIds]);
            $query->andWhere(['tbl_cases.organisation_id' => Yii::$app->user->identity->organisation_id]);
            $query->andWhere(['tbl_cases.client_entity' => Yii::$app->user->identity->client_entity]); // Add condition for client entity
        }
        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_GROUP_MANAGER){  
            $query->andWhere(['tbl_cases.client_id'=> Yii::$app->user->identity->client_id]);
           
        }
        
        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'case_type_id' => $this->case_type_id,
            'applicant_id' => $this->applicant_id,
            'organisation_id' => $this->organisation_id,
            'assigned_to' => $this->assigned_to,
            'case_manager_id' => $this->case_manager_id,
            'client_case_worker_id' => $this->client_case_worker_id,
            'client_case_manager_id' => $this->client_case_manager_id,
            'target_completion_date' => $this->target_completion_date,
            'tbl_cases.created_at' => $this->created_at,
            'date_of_birth' => $this->date_of_birth,
            'mobile_number' => $this->mobile_number,
            'case_status' => $this->case_status,
            'client_billing_entity' =>  $this->client_billing_entity,
        ]);
        $query->andFilterWhere(['like', 'case_number', $this->case_number])
            ->andFilterWhere(['like', 'sending_country', $this->sending_country])
            ->andFilterWhere(['like', 'receiving_country', $this->receiving_country])
            ->andFilterWhere(['like', 'applicant_last_name', $this->applicant_last_name])
            ->andFilterWhere(['like', 'applicant_first_name', $this->applicant_first_name])
            ->andFilterWhere(['like', 'passport_number', $this->passport_number])
            ->andFilterWhere(['like', 'office_address', $this->office_address])
          ->andFilterWhere(['like', 'client_billing_entity', $this->client_billing_entity])
             ->andFilterWhere(['like', 'case_status', $this->case_status]);
    
        $dataProvider->sort->defaultOrder = ['created_at' => SORT_DESC];
        return $dataProvider;
    }
 public function getFilteredData()
    {
        
        $query = Cases::find()->joinWith('applicant');
        if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT||Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_HR ){
           
            $query->andWhere(['tbl_cases.client_id'=> Yii::$app->user->identity->client_id]);
        }
        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_WORKER){
        
            $query->andWhere('assigned_to = :id', [':id' => Yii::$app->user->id]);
            
        }
        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_MANAGER){

             $clients= Client::find()
                ->leftJoin('tbl_client_organisation', 'tbl_client_organisation.client_id = client.id')
                ->andWhere(['tbl_client_organisation.organisation_id' => Yii::$app->user->identity->organisation_id])->all();
              
      
            $organizationCondition = ['in', 'tbl_cases.client_id', ArrayHelper::getColumn($clients, 'id')];
        
            $caseManagerCondition = [
                'or',
                ['case_manager_id' => Yii::$app->user->id],
                ['and', ['case_manager_id' => null], $organizationCondition],
            ];
        
            $query->andWhere($caseManagerCondition);
        }
        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_CASE_MANAGER){
         
            $query->andWhere(['client_case_manager_id' => Yii::$app->user->id]);
           
        }
        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_CASE_WORKER){
        
            $query->andWhere(['client_case_worker_id' => Yii::$app->user->id]);
          
        }
        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_FINANCE){
           
            $clients= Client::find()
            ->leftJoin('tbl_client_organisation', 'tbl_client_organisation.client_id = client.id')
            ->andWhere(['tbl_client_organisation.organisation_id' => Yii::$app->user->identity->organisation_id])->all();
            $query->andWhere(['is_sent_for_billing' => 1]);
            $query->andWhere(['in', 'tbl_cases.client_id',ArrayHelper::getColumn($clients,'id')]);
        }
        elseif (Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_MANAGER) {
            
            $clients= Client::find()
            ->leftJoin('tbl_client_organisation', 'tbl_client_organisation.client_id = client.id')
            ->andWhere(['tbl_client_organisation.organisation_id' => Yii::$app->user->identity->organisation_id])->all();
            $query->andWhere(['in', 'tbl_cases.client_id', ArrayHelper::getColumn($clients, 'id')]);
            $query->andWhere(['tbl_cases.organisation_id' => Yii::$app->user->identity->organisation_id]);
        }
        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN){
           
            $clients= Client::find()
            ->leftJoin('tbl_client_organisation', 'tbl_client_organisation.client_id = client.id')
            ->andWhere(['tbl_client_organisation.organisation_id' => Yii::$app->user->identity->organisation_id])->all();
            $query->andWhere(['in', 'tbl_cases.client_id',ArrayHelper::getColumn($clients,'id')]);
            $query->andWhere(['tbl_cases.organisation_id' => Yii::$app->user->identity->organisation_id]);
        }
        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_ENTITY_MANAGER){         
            $clients= Client::find()
            ->leftJoin('tbl_client_organisation', 'tbl_client_organisation.client_id = client.id')
            ->andWhere(['tbl_client_organisation.organisation_id' => Yii::$app->user->identity->organisation_id])->all();
            $clientIds = ArrayHelper::getColumn($clients, 'id');

            // Fetch only cases where client_id and client_entity match the user's assigned values
            $query->andWhere(['in', 'tbl_cases.client_id', $clientIds]);
            $query->andWhere(['tbl_cases.organisation_id' => Yii::$app->user->identity->organisation_id]);
            $query->andWhere(['tbl_cases.client_entity' => Yii::$app->user->identity->client_entity]); // Add condition for client entity
        }
        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_GROUP_MANAGER){  
            $query->andWhere(['tbl_cases.client_id'=> Yii::$app->user->identity->client_id]);
           
        }
    
        $filterData = $query->all();
     
      
        return $filterData;
    }
    

}
   



