<?php

namespace backend\models\search;

use app\components\GlobalConstant;
use backend\models\Client;
use backend\models\Organisation;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Receipt;
use common\models\User;

/**
 * ReceiptSearch represents the model behind the search form about `common\models\Receipt`.
 */
class ReceiptSearch extends Receipt
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['receipt_increment_number_part', 'service_id', 'payment_mode', 'drawn_on','set_mobile'], 'integer'],
            [['date', 'receipt_increment_alphabetic_part', 'receipt_number' ,'client_id' ,'set_client_name','set_client_registration_number','set_email','cheque_number', 'date_received'], 'safe'],
            [['amount'], 'number'],
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
        if(!empty(Yii::$app->user->identity->client_id) && Yii::$app->user->identity->getRole()== GlobalConstant::ROLE_CLIENT){
            $query = Receipt::find()->where(['client_id'=>Yii::$app->user->identity->client_id]);
        }
        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN){
            $organisationModel = Organisation::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
            $query = Receipt::find()->where(['organisation_id'=>$organisationModel->id]);
        }
        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_MANAGER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_FINANCE){
            $organisationID = User::findOne(Yii::$app->user->id)->organisation_id;
            $query = Receipt::find()->where(['organisation_id'=>$organisationID]);
        }
       
        elseif(Yii::$app->user->can(GlobalConstant::ROLE_CASE_MANAGER)){
            $query = Receipt::find()->joinWith('case');
            $query->where(['tbl_cases.case_manager_id'=>Yii::$app->user->id]);
        }
        elseif(Yii::$app->user->can(GlobalConstant::ROLE_CASE_WORKER)){
            $query = Receipt::find()->joinWith('case');
            $query->where(['tbl_cases.assigned_to'=>Yii::$app->user->id]);
        }
        elseif(Yii::$app->user->can(GlobalConstant::ROLE_CLIENT_ENTITY_MANAGER)  ){
            $query = Receipt::find()->joinWith('case');
            
            // $query->where(['tbl_cases.assigned_to'=>Yii::$app->user->id]);
            $query->andWhere(['tbl_cases.client_entity' => Yii::$app->user->identity->client_entity]);
        }
        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_CASE_MANAGER){
            $query = Receipt::find()->joinWith('case');
            $query->where(['tbl_cases.client_case_manager_id'=>Yii::$app->user->id]);
        }
        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_CASE_WORKER){
            $query = Receipt::find()->joinWith('case');
            $query->where(['tbl_cases.client_case_worker_id'=>Yii::$app->user->id]);
        }
        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_GROUP_MANAGER){
        
            $query = Receipt::find()->where(['client_id'=>Yii::$app->user->identity->client_id]);
        }
        else {
            $query = Receipt::find()->where(['user_id'=>Yii::$app->user->id]);// for caseworker
        }

        //filtering on from_date and to_date
        if (isset($params['ReceiptSearch']['from_date']) || isset($params['ReceiptSearch']['to_date'])) {
            //setting to_date to today if empty
            if (empty($params['ReceiptSearch']['to_date'])) {
                $params['ReceiptSearch']['to_date'] = date('Y-m-d H:i:s', strtotime(date('Y-m-d')));
            }

            //the to_date was not inclusive so adding 1 day
            $params['ReceiptSearch']['to_date'] = date('Y-m-d H:i:s', strtotime('+1 day', strtotime(date($params['ReceiptSearch']['to_date']))));

            //setting from_date to minimum value in case of empty
            if (empty($params['ReceiptSearch']['from_date'])) {
                $params['ReceiptSearch']['from_date'] = date('Y-m-d H:i:s', strtotime('1970-01-01'));
            }

            if ($params['ReceiptSearch']['from_date'] <= $params['ReceiptSearch']['to_date']) {
                $query->andWhere(['between', 'created_at', $params['ReceiptSearch']['from_date'], $params['ReceiptSearch']['to_date']]);
            }
        }

        //filtering based on client id
        if (isset($params['ReceiptSearch']['client_id'])) {
            $client = Client::findOne($params['ReceiptSearch']['client_id']);
            if (!empty($client)) {
                $query->andWhere(['set_client_name' => $client->client_name]);
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['date'=>SORT_DESC]]
        ]);

        if(isset($params['Receipt']['quotes'])){
            $query->andWhere(['is_receipt'=>-1]);
        }
        elseif(isset($params['Receipt']['invoices'])){
            $query->andWhere(['is_receipt'=>0]);
        }
        else
            $query->andWhere(['is_receipt'=>1]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'receipt_increment_number_part' => $this->receipt_increment_number_part,
            'amount' => $this->amount,
            'service_id' => $this->service_id,
            'payment_mode' => $this->payment_mode,
            'drawn_on' => $this->drawn_on,
        ]);


        $query->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'date_received', $this->date_received])
            ->andFilterWhere(['like', 'cheque_number', $this->cheque_number])
            ->andFilterWhere(['like', 'receipt_number', $this->receipt_number])
            ->andFilterWhere(['like', 'set_client_registration_number', $this->set_client_registration_number])
            ->andFilterWhere(['like', 'set_email', $this->set_email])
            ->andFilterWhere(['like', 'set_client_name', $this->set_client_name])
            ->andFilterWhere(['like', 'set_mobile', $this->set_mobile])
            ->andFilterWhere(['like', 'receipt_increment_alphabetic_part', $this->receipt_increment_alphabetic_part]);

        return $dataProvider;
    }
}
