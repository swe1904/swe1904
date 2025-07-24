<?php

namespace backend\models\search;

use app\components\GlobalConstant;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Organisation;
use common\models\User;
use backend\models\Client;
use backend\models\Applicant;
//use backend\models\Applicant;

/**
 * ApplicantSearch represents the model behind the search form about `backend\models\Applicant`.
 */
class ApplicantSearch extends Applicant
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'client_id', 'parent_id'], 'integer'],
            [['email', 'first_name', 'last_name', 'nationality', 'sending_country', 'date_of_birth', 'passport_number', 'mobile_number', 'office_address', 'file_1609222030883', 'date_1674644208007', 'textarea_1716885445830', 'select_1716885518762', 'date_1716885690490', 'date_1716885716345', 'select_1716885772442', 'file_1716885886753', 'file_1716885947331', 'file_1716886041312', 'file_1716886071776', 'select_1717755396737'], 'safe'],
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
        
if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_SUPERADMIN){
$query = Applicant::find();
}
//else if(Yii::$app->user->can('organisation-admin')){
//$clients=Client::find()->where('user_id=:user_id',[':user_id'=>yii::$app->user->id])->all();
//if(empty($clients)){
//return null;
//}
//$client_ids=[];
//foreach ($clients as $client){
//array_push($client_ids,$client->id);
//}
//$query = Applicant::find()->where(['in', 'client_id', $client_ids]);
//if(!empty($params['client_id'])) {
//$this->client_id = $params['client_id'];
//}
//}
else if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_WORKER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_MANAGER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_MANAGER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN){
$clients=Client::find()->leftJoin('tbl_client_organisation', 'tbl_client_organisation.client_id = client.id')->andWhere(['tbl_client_organisation.organisation_id' => Yii::$app->user->identity->organisation_id])->all();
if(empty($clients)){
//return null;
}
$client_ids=[];
foreach ($clients as $client){
array_push($client_ids,$client->id);
}
$query = Applicant::find()->where(['in', 'client_id', $client_ids]);
if(!empty($params['client_id'])) {
$this->client_id = $params['client_id'];
}
}else if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT){
$client=User::find()->where('id=:id',[':id'=>yii::$app->user->id])->one();
if(empty($client->client_id)){
//return null;
}
$query = Applicant::find()->where('client_id=:client_id',[':client_id'=>$client->client_id]);
}



        // Define the sort rules
        $sort = [
            'attributes' => [
                'client_id' => [
                    'asc' => ['client_id' => SORT_ASC],
                    'desc' => ['client_id' => SORT_DESC],
                    'default' => SORT_ASC,
                ],
                'first_name' => [
                    'asc' => ['client_id' => SORT_ASC, 'first_name' => SORT_ASC],
                    'desc' => ['client_id' => SORT_ASC, 'first_name' => SORT_DESC],
                    'default' => SORT_ASC,
                ],
                // Add other sortable attributes as needed
            ],
            'defaultOrder' => [
                'client_id' => SORT_ASC, // Ensure client_id is the default sort
            ],
        ];
        $query->joinWith('client')->orderBy(['client.client_name' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => $sort,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // Apply filter for parent_id if it exists in $params
        if (isset($params['parent_id']) && !empty($params['parent_id'])) {
            $query->andWhere(['parent_id' => $params['parent_id']]);
        }
        else
        $query->andWhere(['parent_id' => null]);

        $query->andFilterWhere([
            'id' => $this->id,
            'client_id' => $this->client_id,
            'parent_id' => $this->parent_id,
            'date_of_birth' => $this->date_of_birth,
            'date_1674644208007' => $this->date_1674644208007,
            'date_1716885690490' => $this->date_1716885690490,
            'date_1716885716345' => $this->date_1716885716345,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'nationality', $this->nationality])
            ->andFilterWhere(['like', 'sending_country', $this->sending_country])
            ->andFilterWhere(['like', 'passport_number', $this->passport_number])
            ->andFilterWhere(['like', 'mobile_number', $this->mobile_number])
            ->andFilterWhere(['like', 'office_address', $this->office_address])
            ->andFilterWhere(['like', 'file_1609222030883', $this->file_1609222030883])
            ->andFilterWhere(['like', 'textarea_1716885445830', $this->textarea_1716885445830])
            ->andFilterWhere(['like', 'select_1716885518762', $this->select_1716885518762])
            ->andFilterWhere(['like', 'select_1716885772442', $this->select_1716885772442])
            ->andFilterWhere(['like', 'file_1716885886753', $this->file_1716885886753])
            ->andFilterWhere(['like', 'file_1716885947331', $this->file_1716885947331])
            ->andFilterWhere(['like', 'file_1716886041312', $this->file_1716886041312])
            ->andFilterWhere(['like', 'file_1716886071776', $this->file_1716886071776])
            ->andFilterWhere(['like', 'select_1717755396737', $this->select_1717755396737]);

        return $dataProvider;
    }
}
