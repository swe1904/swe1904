<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Organisation;
use common\models\User;
use backend\models\Client;
use backend\models\Applicant;
use app\components\GlobalConstant;
//use backend\models\Client;

/**
 * ClientSearch represents the model behind the search form about `backend\models\Client`.
 */
class ClientSearch extends Client
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'organisation_id', 'user_id'], 'integer'],
            [['email', 'client_name', 'country', 'phone', 'address', 'text_1570532600638', 'text_1578126561394'], 'safe'],
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
        
// $organisation=Organisation::find()->where('user_id=:user_id',[':user_id'=>yii::$app->user->id])->one();
// $organisation_id='';
// if(!empty($organisation)){
// $organisation_id=$organisation->id;
// } elseif (Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_MANAGER && !empty(Organisation::findOne(User::findOne(Yii::$app->user->id)->organisation_id))) {
//     $organisation_id = User::findOne(Yii::$app->user->id)->organisation_id;
// }else{Yii::$app->getResponse()->redirect(array('organisation/create'));}
// $query = Client::find()->where('organisation_id=:organisation_id',[':organisation_id'=>$organisation_id]);
    // $query = Client::find()->where('organisation_id=:organisation_id',[':organisation_id'=>Yii::$app->user->identity->organisation_id]);
    $query = Client::find();
    $query->leftJoin('tbl_client_organisation', 'tbl_client_organisation.client_id = client.id');
    $query->andWhere(['tbl_client_organisation.organisation_id' => Yii::$app->user->identity->organisation_id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'organisation_id' => $this->organisation_id,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'client_name', $this->client_name])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'text_1570532600638', $this->text_1570532600638])
            ->andFilterWhere(['like', 'text_1578126561394', $this->text_1578126561394]);

        return $dataProvider;
    }
}
