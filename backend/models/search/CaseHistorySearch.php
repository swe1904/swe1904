<?php

namespace backend\models\search;

use app\components\GlobalConstant;
use backend\models\Cases;
use backend\models\Organisation;
use common\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CaseHistory;
use yii\helpers\ArrayHelper;

/**
 * CaseHistorySearch represents the model behind the search form about `backend\models\CaseHistory`.
 */
class CaseHistorySearch extends CaseHistory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'case_id','is_complete','case_step_status'], 'integer'],
            [['created_at','case_status'], 'safe'],
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
        $query = CaseHistory::find()->orderBy('id desc')->distinct();
        $query->joinWith('case')->joinWith('case.caseSteps')->joinWith('case.applicant');
        if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_HR ){
            $query->andWhere(['in', 'tbl_cases.client_id',ArrayHelper::getColumn(User::find()->where(['id'=>Yii::$app->user->id])->all(),'client_id')]);
        }
        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_WORKER){
            $query->andWhere('tbl_case_history.case_id = tbl_cases.id');
            $query->andWhere('tbl_cases.assigned_to = :id', ['id' => Yii::$app->user->id]);
        }
        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN ){
            $clients= User::find()->where(['organisation_id'=>Organisation::find()->where(['user_id'=>Yii::$app->user->id])->one()->id])->andWhere(['not',['client_id'=>NULL]])->all();
            $query->andWhere(['in', 'tbl_cases.client_id',ArrayHelper::getColumn($clients,'client_id')]);
        }
        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_MANAGER ){
            $clients= User::find()->where(['organisation_id'=>Organisation::findOne(User::findOne(Yii::$app->user->id))])->andWhere(['not',['client_id'=>NULL]])->all();
            $query->andWhere(['in', 'tbl_cases.client_id',ArrayHelper::getColumn($clients,'client_id')]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'pageSize' => 10
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'tbl_case_history.id' => $this->id,
            'tbl_case_history.case_id' =>$this->case_id,
            'is_complete' =>$this->is_complete
        ]);

        $query->andFilterWhere(['like', 'tbl_case_history.created_at', $this->created_at]);


        return $dataProvider;
    }

}
