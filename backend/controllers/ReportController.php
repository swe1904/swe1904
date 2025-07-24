<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 11/1/2019
 * Time: 11:37 AM
 */

namespace backend\controllers;


use app\components\GlobalConstant;
use backend\models\Applicant;
use backend\models\Organisation;
use backend\models\search\CaseHistorySearch;
use backend\models\search\CasesSearch;
use backend\models\search\ReceiptSearch;
use common\models\Receipt;
use common\models\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use backend\models\Cases;
use backend\models\Client;
use backend\models\ClientEntity;

class ReportController extends CustomBaseController
{

    //fetching list of pangea/client case workers with active cases count and completed cases count
    public function actionIndex($clientCW = null) {
        $query = User::find()->leftJoin('tbl_rbac_auth_assignment as rbac', 'tbl_user.id=rbac.user_id')->select(['active_cases_count' => 'COUNT(CASE WHEN tbl_cases.over_all_status = 0 THEN tbl_cases.case_number END)', 'completed_cases_count' => 'COUNT(CASE WHEN tbl_cases.over_all_status = 1 THEN tbl_cases.case_number END)', 'tbl_user.id', 'tbl_user.username']);

        $organization = Organisation::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
        if ($organization) {
            $query->andWhere(['tbl_user.organisation_id' => $organization->id]);
        }

       
        
        //$organization = Organisation::find()->where(['user_id' => Yii::$app->user->identity->id])->one();
          // if ($organization !== null) {
              // $organizationId = $organization->id;
              // $query->andWhere(['tbl_user.organisation_id' => $organizationId]);
        //Rest of your code here
            
             
             
             else {
        // Handle the case when the organization is not found
        }




        $params = Yii::$app->request->queryParams;
        //checking if passed caseWorker ID is pangea case worker or client case worker, joining on appropriate column
        if (isset($params['clientCW']) && $params['clientCW'] === '1') {
            $query->leftJoin('tbl_cases', 'tbl_cases.raised_by_id = tbl_user.id');
            $query->andWhere(['rbac.item_name' => GlobalConstant::ROLE_CLIENT_HR]);
        } else {
            $query->leftJoin('tbl_cases', 'tbl_cases.assigned_to = tbl_user.id');
            $query->andWhere(['rbac.item_name' => GlobalConstant::ROLE_CASE_WORKER]);
        }
        // $params = Yii::$app->request->queryParams;

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
            $client = Client::findOne($params['CasesSearch']['client_id']);
            if (!empty($client)) {
                $query->andWhere(['tbl_cases.client_name' => $client->client_name]);
            }
        }   

        if (isset($params['CasesSearch']['client_entity'])) {
            $query->andWhere(['tbl_cases.client_entity' => $params['CasesSearch']['client_entity']]);
        }   


        if (isset($params['CasesSearch']['case_worker'])) {
            $query->andWhere(['tbl_cases.assigned_to' => $params['CasesSearch']['case_worker']]);
        }
        // if (isset($params['CasesSearch']['case_manager_id'])) {
        //     $query->andFilterWhere(['case_manager_id' => $params['CasesSearch']['case_manager_id']]);
        // }
        //grouping by user_id for the counts of active and completed cases
        $query->groupBy('user_id', 'over_all_status')->asArray(); 

        //finding clients for filtering
        if (isset(Yii::$app->user->identity->organisation_id)) {
            $clients = Client::find()->select(['id', 'client_name'])->where(['organisation_id' => Yii::$app->user->identity->organisation_id])->all();
        } else {
            $clients = Client::find()->select(['id', 'client_name'])->all();
        }

        //finding caseworkers for filtering
        if (isset(Yii::$app->user->identity->organisation_id)) {
            $caseWorkers = User::find()->select(['tbl_user.id', 'tbl_user.username'])->leftJoin('tbl_rbac_auth_assignment as rbac', 'tbl_user.id=rbac.user_id')->where(['tbl_user.organisation_id' => Yii::$app->user->identity->organisation_id, 'rbac.item_name' => 'Case Worker'])->all();
        } else {
            $caseWorkers = User::find()->select(['tbl_user.id', 'tbl_user.username'])->leftJoin('tbl_rbac_auth_assignment as rbac', 'tbl_user.id=rbac.user_id')->where(['rbac.item_name' => 'Case Worker'])->all();
        }

        $clientIDs = ArrayHelper::getColumn($clients, 'id');
        //finding client entities for filtering
        $clientEntities = ClientEntity::find()->select(['id', 'name', 'client_id'])->where(['in', 'client_id', $clientIDs])->all();
        $clients = ArrayHelper::map($clients, 'id', 'client_name');
        $caseWorkers = ArrayHelper::map($caseWorkers, 'id', 'username');
        // $clientEntities = ArrayHelper::map($clientEntities, 'id', 'name', 'client_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index-new', [
            'dataProvider' => $dataProvider,
            'clients' => $clients,
            'clientEntities' => $clientEntities,
            'caseworkers' => $caseWorkers
        ]);
    }

    //view report of single caseworker
    //$id represents case worker's user ID, if $clientCW is 1 that means given caseWorker ID is client case worker
    public function actionView($id, $clientCW = null) {
        $params = Yii::$app->request->queryParams;
        $caseWorker = User::findOne($id);
        $searchModelCases = new CasesSearch();
        if(isset($params['clientCW']))
            $clientCW = 1;
        //querying active cases on time related to given user id
        $dataProviderActiveCasesOnTime = $searchModelCases->search(Yii::$app->request->queryParams);
        $dataProviderActiveCasesOnTime->query = $this->getQueryWithCaseWorkerFilter($dataProviderActiveCasesOnTime->query, $id, $clientCW); //check if case is assigned to/raised by the user we are viewing the report for
        $dataProviderActiveCasesOnTime->query->leftJoin('tbl_case_steps as cs', 'tbl_cases.id = cs.case_id'); //joining tbl_cases with tbl_case_steps
        $dataProviderActiveCasesOnTime->query->andWhere('tbl_cases.id IN (
            SELECT c.id
            FROM tbl_cases c
            INNER JOIN tbl_case_steps cs ON c.id = cs.case_id
            WHERE cs.actual_completion_date IS NULL
            AND NOT EXISTS (
                  SELECT case_id
                  FROM tbl_case_steps
                  WHERE case_id = c.id
                  AND actual_completion_date IS NULL
                  AND planned_completion_date < CURDATE()
            )
            GROUP BY c.id
            )'
        ); //fetching only those cases where the minimum planned completion date of a step is greater than or equal to current date
        $dataProviderActiveCasesOnTime->query->andFilterWhere(['over_all_status' => 0])->distinct(); // overall the case should not be complete
        $dataProviderActiveCasesOnTime->pagination->pageSize = 10;
        $dataProviderActiveCasesOnTime->sort->defaultOrder = ['target_completion_date' => SORT_DESC];

        //querying active cases delayed assigned to/raised by given user id
        $dataProviderActiveCasesDelayed = $searchModelCases->search(Yii::$app->request->queryParams);
        $dataProviderActiveCasesDelayed->query = $this->getQueryWithCaseWorkerFilter($dataProviderActiveCasesDelayed->query, $id, $clientCW);
        $dataProviderActiveCasesDelayed->query->leftJoin('tbl_case_steps as cs', 'tbl_cases.id = cs.case_id');
        $dataProviderActiveCasesDelayed->query->andWhere(['<', 'cs.planned_completion_date', date('Y-m-d')])->distinct(); //checking if any step has a planned date lesser than today
        $dataProviderActiveCasesDelayed->query->andWhere(['cs.actual_completion_date' => null])->distinct(); //step should not be completed
        $dataProviderActiveCasesDelayed->query->andWhere(['tbl_cases.over_all_status' => 0]); //overall the case should not be complete
        $dataProviderActiveCasesDelayed->pagination->pageSize = 10;
        $dataProviderActiveCasesDelayed->sort->defaultOrder = ['target_completion_date' => SORT_DESC];

        //querying completed cases on time assigned to/raised by given user id
        $dataProviderCompletedCasesOnTime = $searchModelCases->search(Yii::$app->request->queryParams);
        $dataProviderCompletedCasesOnTime->query = $this->getQueryWithCaseWorkerFilter($dataProviderCompletedCasesOnTime->query, $id, $clientCW);
        $dataProviderCompletedCasesOnTime->query->leftJoin('tbl_case_steps as cs', 'tbl_cases.id = cs.case_id');
        $dataProviderCompletedCasesOnTime->query->andWhere('NOT EXISTS ( SELECT * FROM tbl_case_steps s2 WHERE s2.case_id = tbl_cases.id AND (s2.actual_completion_date > s2.planned_completion_date OR s2.actual_completion_date IS NULL ))');
        // $dataProviderCompletedCasesOnTime->query->andWhere('cs.id in (SELECT max(id) FROM tbl_case_steps group BY case_id)'); //selecting last step to check if step was completed on time
        // $dataProviderCompletedCasesOnTime->query->andWhere('cs.actual_completion_date <= cs.planned_completion_date')->distinct(); 
        $dataProviderCompletedCasesOnTime->query->andFilterWhere(['over_all_status' => 1]);
        $dataProviderCompletedCasesOnTime->sort->defaultOrder = ['target_completion_date' => SORT_DESC];

        // var_dump($dataProviderCompletedCasesOnTime->query->createCommand()->getRawSql()); die();

        //querying completed cases delayed assigned to/raised by given user id
        $dataProviderCompletedCasesDelayed = $searchModelCases->search(Yii::$app->request->queryParams);
        $dataProviderCompletedCasesDelayed->query = $this->getQueryWithCaseWorkerFilter($dataProviderCompletedCasesDelayed->query, $id, $clientCW);
        $dataProviderCompletedCasesDelayed->query->leftJoin('tbl_case_steps as cs', 'tbl_cases.id = cs.case_id');
        $dataProviderCompletedCasesDelayed->query->andWhere('EXISTS ( SELECT * FROM tbl_case_steps s2 WHERE s2.case_id = tbl_cases.id AND s2.actual_completion_date > s2.planned_completion_date AND s2.actual_completion_date IS NOT NULL )');
        // $dataProviderCompletedCasesDelayed->query->andWhere('cs.id in (select max(id) FROM tbl_case_steps group BY case_id)'); //same as above
        // $dataProviderCompletedCasesDelayed->query->andWhere('cs.actual_completion_date >= cs.planned_completion_date');
        $dataProviderCompletedCasesDelayed->query->andFilterWhere(['over_all_status' => 1]);
        $dataProviderCompletedCasesDelayed->sort->defaultOrder = ['target_completion_date' => SORT_DESC];

        //counting cases assigned to/raised by given caseWorker that have no steps, hence can't be determined whether they are on time or delayed
        $countCasesWithoutSteps = Cases::find()->leftJoin('tbl_case_steps as cs', 'tbl_cases.id = cs.case_id')->andWhere(['cs.id' => null]);
        $countCasesWithoutSteps = $this->getQueryWithCaseWorkerFilter($countCasesWithoutSteps, $id, $clientCW)->count();

        return $this->render('view', [
            'caseWorker' => $caseWorker,
            'dataProviderActiveCasesOnTime' => $dataProviderActiveCasesOnTime,
            'dataProviderActiveCasesDelayed' => $dataProviderActiveCasesDelayed,
            'dataProviderCompletedCasesOnTime' => $dataProviderCompletedCasesOnTime,
            'dataProviderCompletedCasesDelayed' => $dataProviderCompletedCasesDelayed,
            'countCasesWithoutSteps' => $countCasesWithoutSteps,
        ]);

    }

    //this function applies the appropriate filter given the query, caseWorkerID and whether or not the case worker is client case worker
    public function getQueryWithCaseWorkerFilter($query, $id, $clientCW = null) {
        if (isset($clientCW)) {
            return $query->andWhere(['tbl_cases.raised_by_id' => $id]); //filtering on raised_by_id in case of client case worker
        }

        return $query->andWhere(['tbl_cases.assigned_to' => $id]); //filtering on assigned_to in case of pangea case worker
    }

    public function actionOldIndex()
    {
        /* Cases*/
        $searchModelCases = new CaseHistorySearch();
        $dataProviderCasesCompletedOnTime = $searchModelCases->search(Yii::$app->request->queryParams);
        // to select clients applicant cases only
        if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT||Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_HR){
            $dataProviderCasesCompletedOnTime->query->where('tbl_cases.applicant_id IN (
        SELECT id FROM applicant where client_id='.Yii::$app->user->identity->client_id.'
        )');
        }
        // to select org applicant cases only
//        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_WORKER ){
//            $clients= User::find()->where(['organisation_id'=>Yii::$app->user->identity->organisation_id])->andWhere(['not',['client_id'=>NULL]])->all();
//            $clientIds = implode(',',ArrayHelper::getColumn($clients,'client_id'));
//            $dataProviderCasesCompletedOnTime->query->where('tbl_cases.applicant_id IN (
//        SELECT id FROM applicant where client_id IN ('.$clientIds.')
//        )');
//        }
//        // to select org applicant cases only
//        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN ){
//            $clients= User::find()->where(['organisation_id'=>Organisation::find()->where(['user_id'=>Yii::$app->user->id])->one()->id])->andWhere(['not',['client_id'=>NULL]])->all();
//            $clientIds = implode(',',ArrayHelper::getColumn($clients,'client_id'));
//            $dataProviderCasesCompletedOnTime->query->where('tbl_cases.applicant_id IN (
//        SELECT id FROM applicant where client_id IN ('.$clientIds.')
//        )');
//        }
        $dataProviderCasesCompletedOnTime->query->andFilterWhere(['is_complete'=>1])->distinct();
        $dataProviderCasesCompletedOnTime->pagination->pageSize = 10;

//Delayed
        $dataProviderCasesCompletedDelayed = $searchModelCases->search(Yii::$app->request->queryParams);
        $dataProviderCasesCompletedDelayed->query->andWhere('tbl_case_history.id IN (
        SELECT MAX(id)
    FROM tbl_case_history where is_complete=0
    GROUP BY case_id)');
        // to select clients applicant cases only
        if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT||Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_HR){
            $dataProviderCasesCompletedDelayed->query->where('tbl_cases.applicant_id IN (
        SELECT id FROM applicant where client_id='.Yii::$app->user->identity->client_id.'
        )');
        }
//        // to select org applicant cases only
//        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_WORKER ){
//            $clients= User::find()->where(['organisation_id'=>Yii::$app->user->identity->organisation_id])->andWhere(['not',['client_id'=>NULL]])->all();
//            $clientIds = implode(',',ArrayHelper::getColumn($clients,'client_id'));
//            $dataProviderCasesCompletedDelayed->query->where('tbl_cases.applicant_id IN (
//        SELECT id FROM applicant where client_id IN ('.$clientIds.')
//        )');
//        }
//        // to select org applicant cases only
//        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN ){
//            $clients= User::find()->where(['organisation_id'=>Organisation::find()->where(['user_id'=>Yii::$app->user->id])->one()->id])->andWhere(['not',['client_id'=>NULL]])->all();
//            $clientIds = implode(',',ArrayHelper::getColumn($clients,'client_id'));
//            $dataProviderCasesCompletedDelayed->query->where('tbl_cases.applicant_id IN (
//        SELECT id FROM applicant where client_id IN ('.$clientIds.')
//        )');
//        }
        $dataProviderCasesCompletedDelayed->query->andFilterWhere(['case_step_status'=>GlobalConstant::CASE_STEP_STATUS_DELAYED]);
        $dataProviderCasesCompletedDelayed->query->andFilterWhere(['over_all_status'=>0]);
        $dataProviderCasesCompletedDelayed->query->andFilterWhere(['is_complete'=>0])->distinct();
//        echo '<pre>';
//        print_r($dataProviderCasesCompletedDelayed->query->createCommand()->getRawSql());
//        echo '<pre>';
//        die('die here');
        $dataProviderCasesCompletedDelayed->pagination->pageSize = 10;

//in progress
        $dataProviderCasesProgress = $searchModelCases->search(Yii::$app->request->queryParams);
        $dataProviderCasesProgress->query->andWhere('tbl_case_history.id IN (
        SELECT MAX(id)
    FROM tbl_case_history where is_complete=0
    GROUP BY case_id)');
        // to select clients applicant cases only
        if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT||Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_HR){
            $dataProviderCasesProgress->query->where('tbl_cases.applicant_id IN (
        SELECT id FROM applicant where client_id='.Yii::$app->user->identity->client_id.'
        )');
        }
        // to select org applicant cases only
//        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_WORKER ){
//            $clients= User::find()->where(['organisation_id'=>Yii::$app->user->identity->organisation_id])->andWhere(['not',['client_id'=>NULL]])->all();
//            $clientIds = implode(',',ArrayHelper::getColumn($clients,'client_id'));
//            $dataProviderCasesProgress->query->where('tbl_cases.applicant_id IN (
//        SELECT id FROM applicant where client_id IN ('.$clientIds.')
//        )');
//        }
//        // to select org applicant cases only
//        elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN ){
//            $clients= User::find()->where(['organisation_id'=>Organisation::find()->where(['user_id'=>Yii::$app->user->id])->one()->id])->andWhere(['not',['client_id'=>NULL]])->all();
//            $clientIds = implode(',',ArrayHelper::getColumn($clients,'client_id'));
//            $dataProviderCasesProgress->query->where('tbl_cases.applicant_id IN (
//        SELECT id FROM applicant where client_id IN ('.$clientIds.')
//        )');
//        }
        $dataProviderCasesProgress->query->andFilterWhere(['over_all_status'=>0]);
        $dataProviderCasesProgress->query->andFilterWhere(['is_complete'=>0])->distinct();
        $dataProviderCasesProgress->pagination->pageSize = 10;

        /*Report*/
        $searchModel = new ReceiptSearch();

        $dataProviderQuote = $searchModel->search(ArrayHelper::merge(Yii::$app->request->queryParams, ['Receipt' => ['quotes' => -1]]));
        $dataProviderQuote->pagination->pageSize = 10;

        $dataProviderInvoice = $searchModel->search(ArrayHelper::merge(Yii::$app->request->queryParams, ['Receipt' => ['invoices' => 0]]));
        $dataProviderInvoice->pagination->pageSize = 10;

        $searchModel->is_receipt=1;
        $dataProviderReceipt = $searchModel->search(Yii::$app->request->queryParams);
        $dataProviderReceipt->pagination->pageSize = 10;

        return $this->render('index', [
            'dataProviderCasesCompletedOnTime' => $dataProviderCasesCompletedOnTime,
            'dataProviderCasesCompletedDelayed' => $dataProviderCasesCompletedDelayed,
            'dataProviderCasesProgress' => $dataProviderCasesProgress,

            'dataProviderQuote' => $dataProviderQuote,
            'dataProviderInvoice' => $dataProviderInvoice,
            'dataProviderReceipt' => $dataProviderReceipt,

        ]);

    }

}