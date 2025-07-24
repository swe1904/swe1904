<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ClientEntity;
use common\models\Organisation;
use Yii;
use app\components\GlobalConstant;
use backend\models\Client;
use yii\helpers\ArrayHelper;
use common\models\User;

/**
 * ClientEntitySearch represents the model behind the search form of `app\models\ClientEntity`.
 */
class ClientEntitySearch extends ClientEntity
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'client_id'], 'integer'],
            // [['name', 'address', 'cr_number', 'unified_national_number'], 'safe'],
            [['name', 'address'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        
        $query = ClientEntity::find()->joinWith('client');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT) {
            $query->andWhere(['client_id' => Yii::$app->user->identity->client_id]);
        }

        //COMMENTED BELOW CODE AS NOW THE CLIENT ENTITY WILL BE VISIBLE IN THE CLIENTS VIEW PAGE FOR ORGANISATION-ADMIN & ORGANISATION-MANAGER
        // *****COMMENTED CODE START*****
        // $organizationId = Organisation::find()->where(['user_id'=>Yii::$app->user->identity->id])->one()->id;
        // if ($organizationId) {
        //     $clientIDs = Client::find()->select(['id'])->where(['organisation_id' => $organizationId])->all();
        //     $query->andWhere(['in', 'client_id', ArrayHelper::getColumn($clientIDs, 'id')]);
        // }
        // //block added to filter the grid data as per CASE MANAGER role
        // if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_MANAGER){
        //     $organisation=Organisation::find()->where('user_id=:user_id',[':user_id'=>yii::$app->user->id])->one();
        // $organisation_id='';
        // if(!empty($organisation)){
        // $organisation_id=$organisation->id;
        // } elseif ((Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_MANAGER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_MANAGER) && !empty(Organisation::findOne(User::findOne(Yii::$app->user->id)->organisation_id))) {
        //     $organisation_id = User::findOne(Yii::$app->user->id)->organisation_id;
        // }else{Yii::$app->getResponse()->redirect(array('organisation/create'));}
        // $query->andWhere([
            
        //     'client.organisation_id' => $organisation_id,
        // ]);

        // }
        // *****COMMENTED CODE END*****

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // Apply filter for client_id if it exists in $params
        if (isset($params['client_id']) && !empty($params['client_id'])) {
            $query->andWhere(['client_id' => $params['client_id']]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'client_id' => $this->client_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address', $this->address]);
        return $dataProvider;
    }
}
