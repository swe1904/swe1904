<?php

namespace backend\controllers;

use Yii;
use common\models\Organisation;
use common\models\User;
use backend\models\FileUpload;
use app\models\TempFile;
use app\components\GlobalConstant;
use backend\models\Cases;
use backend\models\Client;
use backend\models\CaseSteps;
use backend\models\CaseStatus;
use backend\models\CaseTypeStep;
use backend\models\Applicant;
use backend\models\search\ApplicantSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use backend\models\CaseTypeApplicantField;
use backend\components\Helper;
use backend\modules\mii\components\MiiGlobalConstants;

/**
 * ApplicantController implements the CRUD actions for Applicant model.
 */
class ApplicantController extends Controller
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
     * Lists all Applicant models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ApplicantSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = new Cases();
        if ($model->load(Yii::$app->request->post())) {
            $caseTypeID = Yii::$app->request->post()["Cases"]["case_type_id"];
            Yii::$app->response->redirect(Yii::$app->urlManager->createAbsoluteUrl(['applicant/create', 'id' => $caseTypeID ]));   
        } 
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Applicant model.
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
     * Creates a new Applicant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Applicant();
        //        $model_case = new Cases();
//
//        $requiredFields = CaseTypeApplicantField::findAll(['case_type_id' => $_GET['id'],'is_required' => 1]);
//        foreach($requiredFields as $field)
//        {
//
//            $model->customRules = array_merge($model->customRules, [[[$field['applicant_field_key']],'required']]);
//        }
//
//        $client=User::find()->where('id=:id',[':id'=>yii::$app->user->id])->one();
//        if(!empty($client->client_id)){
//            $model->client_id=$client->client_id;
//        }
//
        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
//            $jsonData = Yii::$app->request->post()["Applicant"];
//            $client=User::find()->where('id=:id',[':id'=>yii::$app->user->id])->one();
//            if(empty(Yii::$app->request->post()["Applicant"]["client_id"]) && !empty($client->client_id)){
//                $jsonData['client_id'] = $client->client_id;
//            }
//
            $this->checkIfFileUpload($model->attributes,$model, $model->id, 'applicant');
//            $model_case = new Cases();
//
//            $applicant=Applicant::findOne($model->id);
//
//            $model_case->applicant_id=$applicant->id;
//            $model_case->case_applicant_info = json_encode($jsonData);
//            $model_case->raised_by_id = Yii::$app->request->post()["Cases"]["raised_by_id"];
//
//            if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_WORKER){
//                // $model_case->assigned_to = Yii::$app->request->post()["Cases"]["raised_by_id"];
//                $model_case->assigned_to = Yii::$app->user->id;
//            }
//
//            //setting Client Entity if selected
//            if (!empty(Yii::$app->request->post()["Cases"]["client_entity"])) {
//                $model_case->client_entity = Yii::$app->request->post()["Cases"]["client_entity"];
//            }
//
//            //setting Case Status to default
//            $defaultCaseStatus = CaseStatus::find()->where(['is_default' => 1])->one();
//            if (!empty($defaultCaseStatus)) {
//                $model_case->case_status = $defaultCaseStatus->id;
//            }
//
//            // changed by Nemanja
//
//            $clientInfor = Client::find()->where(['id' => $applicant->client_id])->one();
//
//            if (@$clientInfor)
//            {
//
//                $client_name = $clientInfor->client_name;
//
//                $organisation_id = $clientInfor->organisation_id;
//
//                $org = Organisation::find()->where(['id' => $organisation_id])->one();
//
//                // old flow where organization admin was used as prefix of case number
//                // $userId = $org->user_id;
//
//                // $user = User::find()->where(['id' => $userId])->one();
//
//                // $username = $user->username;
//
//                //new flow: where receipt_increment_alpahabetic_part of organization is used as prefix of case
//
//                $username = $org->receipt_increment_alpahabetic_part;
//            }
//
//            $model_case->client_name = $client_name;
//
//            $latestRow = Cases::getAll(@$organisation_id ? $organisation_id : null)->orderBy(['mID' => SORT_DESC])->one();
//
//            if (@$latestRow)
//            {
//
//                $ID = $latestRow->mID + 1;
//
//            }
//            else
//            {
//
//                $ID = 1;
//
//            }
//
//            if (@$username)
//            {
//
//                $uName = $username;
//
//            }
//            else
//            {
//
//                $uName = "None";
//
//            }
//
//            $model_case->mID = $ID;
//
//            $model_case->case_number = $uName . '-' . '10000' . $ID;
//
//            $model_case->case_type_id = $_GET['id'];
//
//            // end changing by Nemanja
//
//            // $model_case->sending_country=$applicant->sending_country;
//
//            // $model_case->applicant_first_name=$applicant->first_name;
//
//            // $model_case->applicant_last_name=$applicant->last_name;
//
//            // $model_case->date_of_birth=$applicant->date_of_birth;
//
//            // $model_case->passport_number=$applicant->passport_number;
//
//            // $model_case->mobile_number=$applicant->mobile_number;
//
//            // $model_case->office_address=$applicant->office_address;
//
//            // if ($model_case->load(Yii::$app->request->post()) ) {
//
//           if($model_case->save())
//           {
//                $this->checkIfFileUpload($model->attributes,$model, $model_case->id, 'cases');
//                $steps=CaseTypeStep::find()->where(['case_type_id'=>$model_case->case_type_id])->orderby('order asc')->all();
//
//                $plannedDays=0;
//
//                    //steps are created at the time of case creation:
//
//                foreach ($steps as $step)
//                {
//
//                    $caseStep = new CaseSteps();
//
//                    // if($plannedDays==0){ // set status processing for first step
//
//                    //   $caseStep->status=GlobalConstant::CASE_STEP_STATUS_PROCESSING;
//
//                    // }
//
//                    $caseStep->status=GlobalConstant::CASE_STEP_STATUS_PROCESSING;// set status processing for all
//
//                    $caseStep->case_id=$model_case->id;
//
//                    $caseStep->case_type_step_id=$step->id;
//
//                    $plannedDays+=$step->number_of_days;// added be after status
//
//                        $caseStep->planned_completion_date=date("Y-m-d",strtotime("+".$plannedDays." days"));
//
//                    $caseStep->save();
//
//                }
//
//                $plannedCompletionDate = (new CaseSteps)::find()->where(['case_id' => $model_case->id])->orderby(['planned_completion_date' => SORT_DESC])->one();
//
//                if (!empty($plannedCompletionDate)) {
//                    $model_case->updateAttributes(['target_completion_date' => $plannedCompletionDate->planned_completion_date]);
//                }
//                    // }

//                        // return $this->redirect(['index', 'CasesSearch[applicant_id]'=>$_GET['CasesSearch']['applicant_id']]);
            }
            else {
                $errors = implode(', ', $model->getErrorSummary(true));

                // Set message
                //Yii::$app->session->setFlash('error', 'Error while saving: ' . $errors);
                Yii::error("Error while creating case: " . $errors , __METHOD__);

                return $this->render('create', [
                    'model' => $model,
//                    'model_case' => $model_case
                ]);
            }
            if($model->parent_id)
            {
                Yii::$app->session->setFlash('success', 'Dependent created successfully.');
                return $this->redirect(['view', 'id' => $model->parent_id]);
            }
            else
            {
                Yii::$app->session->setFlash('success', 'Applicant created successfully.');
            
                return $this->redirect(['applicant/index']);
            }

            // else {

            //     return $this->render('create', [

            //         'model_case' => $model_case,

            //     ]);

            // }
        }
        else 
        {
            return $this->render('create', [
                'model' => $model,
//                'model_case' => $model_case
            ]);
        }
            
    }

    private function checkIfFileUpload($attributes,$model, $id, $module){
        $client=\backend\modules\mii\jsonData\Applicant::returnData();
        foreach ($attributes as $columnName=>$value){
            foreach ($client as $data){
                if($data['type']=='file'){
                    if($columnName==str_replace("-","_",$data['name'])){
                        // save file uploads
                        $tempFileModels=TempFile::find()->where('session_id=:session_id',[':session_id'=>$value])->all();
                        if(!empty($tempFileModels)){
                            foreach ($tempFileModels as $tempFileModel){
                                // create file uploads
                                // upload to S3
                                
                                // success -> delete from temp and also delete file from server
                                // folder structure -> org -> module -> entities
                                // generic -> userProfiles -> ...
                                // 
                                // error -> Yii setFlash - file upload failed, please try again

                                $fileUploadModel = new FileUpload();
                                $fileUploadModel->file_id = $value;
                                $fileUploadModel->name = $tempFileModel->name;
                                $fileUploadModel->extension = $tempFileModel->extension;
                                $fileUploadModel->file_name = $tempFileModel->file_name;
                                $fileUploadModel->created_at = $tempFileModel->created_at;
                                $fileUploadModel->updated_at = $tempFileModel->updated_at;
                                $fileUploadModel->uploaded_by = $tempFileModel->uploaded_by;

                                if (getenv('IS_UPLOAD_TO_S3') == 1) {
                                    //Upload to S3 Start
                                    $organisation = Organisation::findOne(User::findOne(Yii::$app->user->id)->organisation_id);

                                    // $bucket = getenv('AWS_S3_BUCKET');
                                    $bucket = 'pangea-live-bucket';
                                    $filePath = \Yii::getAlias('@uploadPath'.'/'.MiiGlobalConstants::UPLOAD_IMAGES.'/'.$tempFileModel->file_name);
                                    //Readable folder structure
                                    $S3Key = $organisation->name . '-' . $organisation->id . '/' . $module . '/' . $id . '/' . basename($filePath);
                                    $errorMessage = 'Failed to upload files. Please try again.';
                                    //Uploading to S3 and getting URL
                                    $url = Helper::uploadToS3($bucket, $S3Key, $filePath, $errorMessage);
                                    if ($url) {
                                        $fileUploadModel->attachment = $url;
                                        $fileUploadModel->is_upload_to_s3 = 1;
                                        $fileUploadModel->s3_file_key = $S3Key;
                                        if($fileUploadModel->save()){
                                            //Deleting temp file from DB and Server
                                            $tempFileModel->delete();
                                            unlink($filePath); 
                                        }
                                    } else {
                                        //if no url, error message will be displayed and redirect back to index
                                        $this->redirect(['applicant/index']);
                                    }
                                } else {
                                    $fileUploadModel->attachment = $tempFileModel->attachment;
                                    if ($fileUploadModel->save()) {
                                        $tempFileModel->delete();
                                    } 
                                }
                            }
                        }
                    }
                }

            }
        }

    }

    /**
     * Updates an existing Applicant model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id = null)
    {
        if (isset($_GET['applicantID'])) {
            $model = Applicant::findOne($_GET['applicantID']);
            
            $requiredFields = CaseTypeApplicantField::findAll(['case_type_id' => $_GET['id'],'is_required' => 1]);
            foreach($requiredFields as $field)
            {
                
                $model->customRules = array_merge($model->customRules, [[[$field['applicant_field_key']],'required']]);
            }

            $caseTypeID = $_GET['id'];
            if (isset($_GET['caseID'])) {
                $model_case = Cases::findOne($_GET['caseID']);
            } 
            else {
                $model_case = new Cases();
            }
            if ($model->load(Yii::$app->request->post()) && ($model->save())) {
                $jsonData = Yii::$app->request->post()["Applicant"];
                $client=User::find()->where('id=:id',[':id'=>yii::$app->user->id])->one();
                if(empty(Yii::$app->request->post()["Applicant"]["client_id"]) && !empty($client->client_id)){
                    $jsonData['client_id'] = $client->client_id;
                }

                if (isset($_GET['caseID'])) {
                    $model_case = Cases::findOne($_GET['caseID']);
                } else {
                    $model_case = new Cases();
                }
                
                $model_case->applicant_id = $_GET['applicantID'];
                $model_case->case_applicant_info = json_encode($jsonData);
                $model_case->raised_by_id = Yii::$app->request->post()["Cases"]["raised_by_id"];

                //setting Client Entity if selected
                if (!empty(Yii::$app->request->post()["Cases"]["client_entity"])) {
                    $model_case->client_entity = Yii::$app->request->post()["Cases"]["client_entity"];
                } else { //else setting it null if de-selected during update
                    $model_case->client_entity = null;
                }

            // changed by Nemanja 

            $clientInfor = Client::find()->where(['id' => $model->client_id])->one();

            if (@$clientInfor) 
            {

                $client_name = $clientInfor->client_name;

                $organisation_id = $clientInfor->organisation_id;

                $org = Organisation::find()->where(['id' => $organisation_id])->one();

                // old flow where organization admin was used as prefix of case number
                // $userId = $org->user_id;

                // $user = User::find()->where(['id' => $userId])->one();

                // $username = $user->username; 
                
                //new flow: where receipt_increment_alpahabetic_part of organization is used as prefix of case

                $username = $org->receipt_increment_alpahabetic_part;    

            }

            $model_case->client_name = $client_name;

            $latestRow = Cases::getAll(@$organisation_id ? $organisation_id : null)->orderBy(['mID' => SORT_DESC])->one();

            if (isset($_GET['caseID'])) {
                $ID = $model_case->mID;
            } else {
                if (@$latestRow) 
                {

                    $ID = $latestRow->mID + 1;

                }
                else
                {

                    $ID = 1;

                }
            }

            if (@$username) 
            {

                $uName = $username;

            }
            else
            {

                $uName = "None";

            }

            $model_case->mID = $ID;

            $model_case->case_number = $uName . '-' . '10000' . $ID;
            
            $model_case->case_type_id = $caseTypeID;

            // end changing by Nemanja       

            if($model_case->save())
            {
                $this->checkIfFileUpload($model->attributes,$model, $model_case->id, 'cases');
                $steps=CaseTypeStep::find()->where(['case_type_id'=>$model_case->case_type_id])->orderby('order asc')->all();

                $plannedDays=0;

                    //steps are created at the time of case creation:

                foreach ($steps as $step) 
                {

                    $caseStep = new CaseSteps();

                    // if($plannedDays==0){ // set status processing for first step

                    //   $caseStep->status=GlobalConstant::CASE_STEP_STATUS_PROCESSING;

                    // }

                    $caseStep->status=GlobalConstant::CASE_STEP_STATUS_PROCESSING;// set status processing for all

                    $caseStep->case_id=$model_case->id;

                    $caseStep->case_type_step_id=$step->id;

                    $plannedDays+=$step->number_of_days;// added be after status

                        $caseStep->planned_completion_date=date("Y-m-d",strtotime("+".$plannedDays." days"));

                    $caseStep->save();

                }

                $plannedCompletionDate = (new CaseSteps)::find()->where(['case_id' => $model_case->id])->orderby(['planned_completion_date' => SORT_DESC])->one();

                if (!empty($plannedCompletionDate)) {
                    $model_case->updateAttributes(['target_completion_date' => $plannedCompletionDate->planned_completion_date]);
                }
                // }

                        // return $this->redirect(['index', 'CasesSearch[applicant_id]'=>$_GET['CasesSearch']['applicant_id']]);
                } 
                
                // $applicantID = $_GET['applicantID'];
                // return $this->redirect(["cases/index?CasesSearch%5Bapplicant_id%5D=$applicantID"]);              
                return $this->redirect(['cases/index']);
            }

            return $this->render("update", [
                'model' => $model,
                'model_case'=>$model_case
            ]);
        } else {
            $model = $this->findModel($id);
            $clientBeforeSave = $model->client_id;

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $clientAfterSave = $model->client_id;

                if ($clientBeforeSave != $clientAfterSave)//update client of dependents if client changed for parent
                {
                    Applicant::updateAll(['client_id' => $model->client_id], ['parent_id' => $model->id]);  
                }
                // upload images
                $this->checkIfFileUpload($model->attributes,$model, $model->id, 'applicants');

                // delete images
                foreach (Applicant::returnAttachmentAttr() as $attr){

                    if(!empty($model->$attr)){
                        $ids=explode(",",$model->$attr);
                        foreach ($ids as $id){
                            $attachmentModel=FileUpload::find()->where('id=:id',[':id'=>$id])->one();
                            if(!empty($attachmentModel)){
                                $attachmentModel->delete();
                            }
                        }
                    }
                }
                if($model->parent_id)
                {
                    Yii::$app->session->setFlash('success', 'Dependent updated successfully.');
                    return $this->redirect(['view', 'id' => $model->parent_id]);
                }
                else
                {
                    Yii::$app->session->setFlash('success', 'Applicant updated successfully.');
                
                    return $this->redirect(['applicant/index']);
                }
            } else {
                $model_case = new Cases();
                return $this->render('update', [
                    'model' => $model,
//                    'model_case'=>$model_case
                ]);
            }
        }
    }

    /**
     * Deletes an existing Applicant model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if($model->parent_id)
            $message = 'Dependent deleted successfully.';
        else
            $message = 'Applicant deleted successfully.';

        $client = Client::findOne($model->client_id);
        $organisation = Organisation::findOne($client->organisation_id);

        // $bucket = getenv('AWS_S3_BUCKET');
        $bucket = 'pangea-live-bucket';
        $prefix = $organisation->name . '-' . $organisation->id . '/' . 'applicants' . '/' . $model->id . '/';

        if (!Helper::deleteFolderFromS3($bucket, $prefix)) {    
            Yii::$app->session->setFlash('error', 'Files could not be deleted properly, please try again.');
            //return $this->redirect(['index']);
            return $this->redirect(Yii::$app->request->referrer);
        }

        $model->delete();
        
        Yii::$app->session->setFlash('success', $message);
        //return $this->redirect(['index']);
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the Applicant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Applicant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Applicant::findOne($id)) !== null) {
            $client=\backend\modules\mii\jsonData\Applicant::returnData();
            foreach ($model->attributes as $columnName=>$value) {
                foreach ($client as $data) {
                    if ($data['type'] == 'select' && !empty($data['multiple'])) {
                        if ($columnName == str_replace("-", "_", $data['name'])) {
                            $oldSelectData=[];
                            $selectData=$columnName."s";
                            $multiSelect='multi_select_'.$columnName;
                            if(!empty($model->$selectData)){
                                foreach ($model->$selectData as $selectData){
                                    array_push($oldSelectData,$selectData->name);
                                }
                            }
                            $model->$multiSelect=$model->$columnName;
                            $model->$columnName=$oldSelectData;
                        }
                    }
                }
            }
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionGetClientHr()
    {
        if(isset($_GET))
        {
            $data = ArrayHelper::map(User::find()->where(['client_id'=>$_GET['id']])->all(),'id','username');
            return json_encode($data);
            
        }   
    }

    public function actionDeleteFile() {
        if (!empty(Yii::$app->request->post()["fileID"])) {
            $fileID = Yii::$app->request->post()["fileID"];
            $file = FileUpload::findOne($fileID); 

            //checking if file is uploaded on s3
            if ($file->is_upload_to_s3 == 1) {
                // $bucket = getenv('AWS_S3_BUCKET');
                $bucket = 'pangea-live-bucket';
                $key = $file->s3_file_key;
                if (!Helper::deleteFromS3($bucket, $key)) {
                    return json_encode([
                        'code' => 0,
                        'message' => 'File could not be deleted, please try again.'
                    ]);
                } else {
                    $file->delete();
                    return json_encode([
                        'code' => 1,
                        'message' => 'File deleted',
                        'fileID' => $fileID,
                    ]);
                } 
            } else {
                //if file is directly uploaded on server
                $filePath = \Yii::getAlias('@uploadPath'.'/'.MiiGlobalConstants::UPLOAD_IMAGES.'/'.$file->file_name);
                unlink($filePath);
                return json_encode([
                    'code' => 1,
                    'message' => 'File Deleted'
                ]);
            }
        } 
    }
}
