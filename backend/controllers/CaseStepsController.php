<?php

namespace backend\controllers;

use app\components\GlobalConstant;
use backend\models\CaseHistory;
use yii\helpers\ArrayHelper;
use backend\models\Cases;
use backend\models\Client;
use backend\models\CaseType;
use backend\models\CaseTypeStep;
use yii\web\Response;
use backend\models\CaseSteps;
use backend\models\search\CaseStepsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use himiklab\sortablegrid\SortableGridAction;

use yii\filters\VerbFilter;
use common\models\User;
use DateTime;
use backend\components\Helper;
use Yii;


/**
 * CaseStepsController implements the CRUD actions for CaseSteps model.
 */
class CaseStepsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all CaseSteps models.
     * @return mixed
     */
    public function actionIndex()
    {
       
        $this->layout  = '@backend/modules/messagesystem/views/layouts/_final_layout'; //Nemanja
        $searchModel    = new CaseStepsSearch();
        $dataProvider   = $searchModel->search(Yii::$app->request->queryParams);
        $casestepsModel = new CaseSteps();
        $model          = new CaseTypeStep();
        $case_id        = $_GET['CaseStepsSearch']['case_id'];  
      
        if (isset($_GET['sendForBilling']) && isset($_GET['CaseStepsSearch']['case_id'])) {
            $model = Cases::findOne($_GET['CaseStepsSearch']['case_id']);
            if ($model->over_all_status == 1) {
                $model->updateAttributes(['is_sent_for_billing' => 1]);
                Yii::$app->session->setFlash('success', "Case sent for billing.");
                return $this->redirect(['cases/index']);
            } 
            // else {
            //     alert("All steps have to be completed before you can send this for billing");
            // }
            else {
                Yii::$app->session->setFlash('error', 'All steps have to be completed before you can send this for billing.');
                // return $this->redirect(['cases/index']); // Redirect to prevent reloading the form
            }
        }
        
        if ($model->load(Yii::$app->request->post())) 
        {
            $case_id = $_GET['CaseStepsSearch']['case_id'];      
            $name          = $_POST["CaseTypeStep"]["name"];
            $numberOfDays  = $_POST["CaseTypeStep"]["number_of_days"]; 
            $model->name   = $name;
            $model->number_of_days = $numberOfDays;
            $model->save();
            if($model->save())
            {
                $lastCaseStep = CaseSteps::find()
                ->where(['case_id' => $case_id]) 
                ->orderBy(['order' => SORT_DESC]) 
                ->one();
                $plannedCompletionDate = new DateTime($lastCaseStep->planned_completion_date);
                $plannedDate = $plannedCompletionDate->modify('+' . $numberOfDays . ' days');
                $casestepsModel->planned_completion_date =   $plannedDate->format("Y-m-d");               
                $casestepsModel->status = GlobalConstant::CASE_STEP_STATUS_PROCESSING;
                $casestepsModel->description = $casestepsModel->description;  
                $casestepsModel->case_id = $_GET['CaseStepsSearch']['case_id'];
                $casestepsModel->case_type_step_id = $model->id; 
                $casestepsModel->order =  $lastCaseStep->order + 1;
                $casestepsModel->save();
                Yii::$app->session->setFlash('success', 'Case Step created.');
                return $this->redirect(['index', 'CaseStepsSearch[case_id]' => $case_id]);
            }
            
        }
       
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model'        => $model,
           

        ]);
    }

     public function actions()
     {

        
        return [

           'sort' => [
                'class' => SortableGridAction::class,
                'modelName' => CaseSteps::class,        
            ],
        ];
     }
   


     public function actionAfterSort()
     {
         $request = Yii::$app->request;
         $caseStepSortedIds = json_decode($request->getRawBody(), true);
     
         if (!empty($caseStepSortedIds)) {
             $caseStepsArray = [];  
             $caseId = null;        
     
             // Loop through sorted IDs and fetch case steps
             foreach ($caseStepSortedIds as $caseStepId => $newOrder) {
                 $caseSteps = CaseSteps::find()
                     ->with('caseTypeStep')
                     ->where(['status' => 0, 'id' => $newOrder])
                     ->select(['id', 'order', 'case_id'])
                     ->orderBy('order ASC')
                     ->one();

                 if ($caseSteps !== null) {
                     $caseStepsArray[] = $caseSteps;
                     $caseId = $caseSteps->case_id; // Store the case ID
                 }
             }
           
             // Sort the array based on the `order` value
             usort($caseStepsArray, function ($a, $b) {
                 return $a->order - $b->order;
             });
   
             // Fetch all rows from CaseSteps starting from the smallest order ID
             if (!empty($caseStepsArray)) {
                 $smallestOrder = $caseStepsArray[0]->order;
     
                 $allCaseSteps = CaseSteps::find()
                     ->with('caseTypeStep')
                     ->where(['status' => 0, 'case_id' => $caseId])
                     ->andWhere(['>=', 'order', $smallestOrder])
                     ->orderBy('order ASC')
                     ->all();
                 $plannedDays = 0;
                 // Update planned_completion_date for each step
                 foreach ($allCaseSteps as $casesStep) {
                     $number_of_days = $casesStep->caseTypeStep->number_of_days;
                     $plannedDays += $number_of_days;
     
                     $planned_completion_date = date("Y-m-d", strtotime("+$plannedDays days"));

                     $casesStep->planned_completion_date = $planned_completion_date;
    
                     if (!$casesStep->save()) {
                         // Return an error message if save fails
                         return $this->asJson([
                             'status' => 'error',
                             'message' => 'Failed to update case step: ' . json_encode($casesStep->getErrors())
                         ]);
                     }
                 }
     
                 // Successful response after all steps are updated
                 return $this->asJson([
                     'status' => 'success',
                     'message' => 'Reordered successfully!',
                 ]);
             }
         }
         // In case the payload is empty or invalid
         return $this->asJson([
             'status' => 'error',
             'message' => 'Invalid data received',
         ]);
     }
     
     
    
    /**
     * Displays a single CaseSteps model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CaseSteps model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CaseSteps();
        // for admin
        if ($model->load(Yii::$app->request->post())) {
            $model->planned_completion_date = date("Y-m-d", strtotime("+" . $model->caseTypeStep->number_of_days . " days"));
            $model->status = GlobalConstant::CASE_STEP_STATUS_PROCESSING;
            $model->description = $model->description;  //Nemanja
            $model->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CaseSteps model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        
        $oldModelStatus = $model->status;
        $caseModel = Cases::findOne($model->case_id);
           
        $oldModelPlannedDate = new DateTime($model->planned_completion_date);
        if ($model->load(Yii::$app->request->post()) ) { 
           
            if (isset(Yii::$app->request->post()["send_email"])) {
                $caseTypeStepID = $model->case_type_step_id;
                $caseStep = CaseTypeStep::findOne($caseTypeStepID);
                // $caseRaiser = User::findOne($caseModel->raised_by_id);
                $fromEmail = $caseModel->organisation->user->email;
                if (strpos($fromEmail, GlobalConstant::NORTHMAN_EMAIL_DOMAIN) !== false)
                 {
                    $clientId = $caseModel->client_id;
                    $clientUser = Client::getClientUser($clientId);
                    $toEmail = $clientUser->email;
                    $subject = 'Case Update from Northmansterling';
                    $htmlBody = 'Dear ' . $clientUser->username . ', <br/><br/> Regarding your case ' . $caseModel->case_number . ', <br/><br/> We have completed the following step: ' . $caseStep->name . ' <br/><br/>Thanks';
                    Helper::sendEmailViaSes($fromEmail, $toEmail, null, $subject, $htmlBody, null, null, null);
                 }
                 else{
                    Yii::$app->session->setFlash('error', 'The '.$fromEmail .' is invalid(does not contain @northmansterling.app)');
                 }
            }
            if ($oldModelStatus == 0 && $_POST['CaseSteps']['status'] == 1) 
            {
                $model->actual_completion_date = date("Y-m-d");
                // completed
                $modelDate = new DateTime($model->planned_completion_date);
                $currentDate = new DateTime(date("Y-m-d"));
                $interval = $currentDate->diff($modelDate);
                $daysDifference = $currentDate > $modelDate ? $interval->days : -$interval->days;            
                if (strtotime($model->actual_completion_date) <= strtotime($model->planned_completion_date)) {
                    $model->status = GlobalConstant::CASE_STEP_STATUS_ON_TIME;
                } else {
                    $model->status = GlobalConstant::CASE_STEP_STATUS_DELAYED;
                }
                foreach (CaseSteps::findAll(['case_id' => $model->case_id]) as $step) {
                    if ($step->id > $model->id) {
                        $date = new DateTime($step->planned_completion_date);
                        $date->modify(($daysDifference > 0 ? '+' : '-') . abs($interval->days) . ' days');
                        $step->planned_completion_date = $date->format('Y-m-d');
                        $step->save();
                    }
                }
                if ($model->save()) {
                    $case_type_stepID = $model->case_type_step_id;
                    $casetypeStep = CaseTypeStep::findOne(['id' => $case_type_stepID]);
                    if (@$casetypeStep) {
                        $title = $casetypeStep->name;
                    } else {
                        $title = "";
                    }
                    $cm = Cases::findOne(['id' => $model->case_id]);
                
                    $cm->last_status_update = $title;
                    $cm->update();                 
                    $caseHistoryModel = new CaseHistory();
                    $caseHistoryModel->case_id = $model->case_id;
                    $caseHistoryModel->is_complete = 0;
                    // $caseHistoryModel->case_time=  date("Y-m-d h:i:sa",time());
                    $CaseSteps = CaseSteps::findAll(['case_id' => $model->case_id]);
                   
                    $checkStepsCompletion = 1;
                    foreach ($CaseSteps as $step) {
                        if ($step->status == '0') {
                            $checkStepsCompletion = 0;
                        }
                    }

                    $CaseStepscount = count($CaseSteps) - 1;
                   
                    // die();
                    // if is last step set status of last step
                    if ($CaseSteps[$CaseStepscount]->case_type_step_id == $model->case_type_step_id) {      
                        $caseHistoryModel->case_status = $model->status == GlobalConstant::CASE_STEP_STATUS_ON_TIME ? GlobalConstant::CASE_STEP_STATUS_ON_TIME_LABEL : GlobalConstant::CASE_STEP_STATUS_DELAYED_LABEL;
                        // $caseHistoryModel->is_complete = 1;
                        $caseModel->over_all_status = $checkStepsCompletion;  
                        $caseModel->save();
                    } else { //set status of case
                        $caseHistoryModel->case_status = GlobalConstant::CASE_STEP_STATUS_PROCESSING_LABEL;
                        $caseModel->over_all_status = $checkStepsCompletion;
                        $caseModel->save();
                    }

                    $caseHistoryModel->msg = "step " . $model->caseTypeStep->name . ' has been marked completed';
                    // if already completed set case_step_status on time
                    $caseHistoryModel->case_step_status = ($caseModel->status == 1) ? GlobalConstant::CASE_STEP_STATUS_ON_TIME : $model->status;
                    if (!$caseHistoryModel->save()) {
                        echo '<pre>';
                        print_r($caseHistoryModel->getErrors());
                        echo '<pre>';
                        die('die here');
                    }
                   
                }
                else{
                    Yii::$app->session->setFlash('error', 'Failed to save the model.');
                    return $this->render('update', ['model' => $model]);
                }
             }
             elseif(isset($_POST['CaseSteps']['planned_completion_date']) && $oldModelPlannedDate !== new DateTime($_POST['CaseSteps']['planned_completion_date']))
              {
                $newPlannedDate = new DateTime(Yii::$app->request->post()['CaseSteps']['planned_completion_date']);
              
                $interval = $newPlannedDate->diff($oldModelPlannedDate);
               
                $daysDifference = $newPlannedDate > $oldModelPlannedDate ? $interval->days : -$interval->days;    
                         
                $allSteps = CaseSteps::findAll(['case_id' => $model->case_id]);
                
                    if (strtotime($model->actual_completion_date) < strtotime($model->planned_completion_date)) {
                       
                        foreach ($allSteps as $step) {
                            if ($step->id > $model->id) {
                                $date = new DateTime($step->planned_completion_date);
                               
                                if ($daysDifference > 0) {
                                    // Incrementing interval days
                                    $date->modify('+' . $interval->days . ' days');
                                } else {
                                    // Decrementing interval days
                                    $date->modify('-' . $interval->days . ' days');
                                }
                                $step->planned_completion_date = $date->format('Y-m-d');
                                $step->save();    
                                $model->save();                      
                               
                            }
                            else{
                                $date = Yii::$app->request->post()['CaseSteps']['planned_completion_date'];
                              
                                $model->planned_completion_date = $date;
                                $model->save();

                            }
                        }
                    }
                }
                else
                $model->save();      
                
            return $this->redirect(['index', 'CaseStepsSearch[case_id]' => $_GET['CaseStepsSearch']['case_id']]);
        }
         else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    


    /**
     * Deletes an existing CaseSteps model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CaseSteps model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CaseSteps the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CaseSteps::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
