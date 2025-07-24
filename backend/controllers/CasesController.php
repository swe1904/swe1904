<?php

namespace backend\controllers;

use app\components\GlobalConstant;
use backend\models\Applicant;
use backend\models\CaseSteps;
use backend\models\CaseType;
use backend\models\CaseTypeStep;
use common\models\Organisation;
use common\models\User;
use Yii;
use backend\models\Cases;
use backend\models\Client;  //Nemanja
use backend\models\search\CasesSearch;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\FileUpload;
use app\models\TempFile;
use backend\components\Helper;
use backend\modules\mii\components\MiiGlobalConstants;
use backend\models\CaseStatus;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use backend\models\ClientEntity;
use ZipArchive;
use yii\data\ActiveDataProvider;

/**

 * CasesController implements the CRUD actions for Cases model.

 */

class CasesController extends CustomBaseController

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

//            [

//                'class' => TimestampBehavior::className(),

//                'createdAtAttribute' => 'created_at',

//                'updatedAtAttribute' => 'updated_at',

//                'value' => new Expression('NOW()'),

//            ],

        ];

    }



    /**

     * Lists all Cases models.

     * @return mixed

     */

     public function actionIndex()

     {
 
         //Nemanja created
 
         // if (Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN) || Yii::$app->user->can(GlobalConstant::ROLE_CASE_WORKER)) {
 
         //     Cases::renumber();
 
         // } else {
 
         //     $organisation_id = Organisation::findOne(['user_id' => Yii::$app->user->id])->id;
 
         //     Cases::renumber($organisation_id);
 
         // }
 
         // ended By Nemanja
              
             $organisation = Organisation::find()->where(['user_id' => Yii::$app->user->id])->one();            
             // Test whether organisation exists for organisation admin user
             if (Yii::$app->user->identity->hasRole(GlobalConstant::ROLE_ORGANISATION_ADMIN) && empty($organisation)) {
                 Yii::$app->session->setFlash('warning', "Please fill out organisation details before updating other sections.");
                 return $this->redirect(['organisation/create']);
             }
             $searchModel = new CasesSearch();
            $filter = $searchModel->getFilteredData();
            $filterClientCaseWorkers = [];
            $filterClientCaseManagers = [];
          
            $filterClientCaseWorkers = ArrayHelper::map($filter, 'clientCaseWorker.id', 'clientCaseWorker.username');
            $filterClientCaseManagers =  ArrayHelper::map($filter, 'clientCaseManager.id', 'clientCaseManager.username');
          
            $organisations = ArrayHelper::map($filter, 'organisation.id', 'organisation.name');
            $filterClient = ArrayHelper::map($filter, 'client.id', 'client.client_name');
             //finding case worker and case manager for filtering
            $filterCaseWorkers = [];
            $filterCaseManagers = [];
            foreach( $filterClient as $id => $name)
            {
                $orgsIds = ArrayHelper::getColumn(Organisation::find()->leftJoin('tbl_client_organisation', 'tbl_client_organisation.organisation_id = tbl_organisation.id')->andWhere(['tbl_client_organisation.client_id' => $id])->all(), 'id');
                $filterCaseWorkers = ArrayHelper::map(
                    User::find()
                        ->alias('u') 
                        ->join('LEFT JOIN', 'tbl_rbac_auth_assignment a', 'a.user_id = u.id') 
                        ->andWhere(['a.item_name' => GlobalConstant::ROLE_CASE_WORKER]) 
                        ->andWhere(['in', 'u.organisation_id', $orgsIds]) 
                        ->all(),
                    'id', 
                    function ($model) {
                        if ($model->userProfile) {
                            $firstName = $model->userProfile->firstname;
                            $lastName = $model->userProfile->lastname;
                
                            if (!empty($firstName) || !empty($lastName)) {
                                return trim($firstName . ' ' . $lastName); 
                            }
                        }
                        return $model->username; 
                    }
                );

                $filterCaseManagers = ArrayHelper::map(
                    User::find()
                        ->alias('u') 
                        ->join('LEFT JOIN', 'tbl_rbac_auth_assignment a', 'a.user_id = u.id') 
                        ->andWhere(['a.item_name' => GlobalConstant::ROLE_CASE_MANAGER]) 
                        ->andWhere(['in', 'u.organisation_id', $orgsIds]) 
                        ->all(),
                    'id',
                    function ($model) {
                        if ($model->userProfile) {
                            $firstName = $model->userProfile->firstname;
                            $lastName = $model->userProfile->lastname;
                
                            if (!empty($firstName) || !empty($lastName)) {
                                return trim($firstName . ' ' . $lastName); 
                            }
                        }
                        return $model->username;


                    });
        }
          
            $applicants = ArrayHelper::map($filter, 'applicant.id', function ($model) {
                return $model->applicant->first_name . ' ' . $model->applicant->last_name;
            });
        
           $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
 
         //re routing to creating case for existing applicant
        //  $model = new Cases();

        $role = Yii::$app->user->identity->getRole();  // Get the current user role

        // Filter cases based on the user's role
        $query = Cases::find();
    
        // If the user is Finance, show only cases with 'SENT FOR BILLING' status
        if ($role == GlobalConstant::ROLE_FINANCE) {
            $query->andWhere(['case_status' => 40]);
        }
    
        // Create the data provider with the updated query
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,  // Adjust page size as needed
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,  // Example sorting
                ],
            ],
        ]);
    
        // Continue with the existing logic for finding other necessary data (case workers, clients, etc.)
        $model = new Cases();
        if ($model->load(Yii::$app->request->post())) {
            $caseTypeID = Yii::$app->request->post()["Cases"]["case_type_id"];
            if (isset($_GET["CasesSearch"]['applicant_id'])) {
                $applicantID = $_GET["CasesSearch"]["applicant_id"];
                Yii::$app->response->redirect(Yii::$app->urlManager->createAbsoluteUrl(['applicant/update', 'applicantID' => $applicantID, 'id' => $caseTypeID]));
            }
        }
 
        
         //finding clients for filtering
         if (isset(Yii::$app->user->identity->organisation_id)) {
             // $clients = Client::find()->select(['id', 'client_name'])->where(['organisation_id' => Yii::$app->user->identity->organisation_id])->all();
             $clients = Client::find()
                         ->leftJoin('tbl_client_organisation', 'tbl_client_organisation.client_id = client.id')
                         ->andWhere(['tbl_client_organisation.organisation_id' => Yii::$app->user->identity->organisation_id])->all();
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
      
         return $this->render('index', [
             'searchModel' => $searchModel,
             'dataProvider' => $dataProvider,
             'model' => $model,
             'clients' => $clients,
             'clientEntities' => $clientEntities,
             'caseworkers' => $caseWorkers,
             'applicants'  => $applicants,
             'organisations' => $organisations,
             'filterClient' => $filterClient,
             'filterCaseWorkers' => $filterCaseWorkers,
             'filterCaseManagers' => $filterCaseManagers,
             'filterClientCaseWorkers' => $filterClientCaseWorkers,
             'filterClientCaseManagers'=>$filterClientCaseManagers
             
         ]);
 
     }



    /**

     * Displays a single Cases model.

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

     * Creates a new Cases model.

     * If creation is successful, the browser will be redirected to the 'view' page.

     * @return mixed

     */

    public function actionCreate()

    {

        $model = new Cases();

//        $applicant=Applicant::findOne($_GET['CasesSearch']['applicant_id']);
//
//        $model->applicant_id=$applicant->id;
//
//        // changed by Nemanja
//
//        $clientInfor = Client::find()->where(['id' => $applicant->client_id])->one();
//
//        if (@$clientInfor) {
//
//            $client_name = $clientInfor->client_name;
//
//            $organisation_id = $clientInfor->organisation_id;
//
//            $org = Organisation::find()->where(['id' => $organisation_id])->one();
//
//            // old flow where organization admin was used as prefix of case number
//            // $userId = $org->user_id;
//
//            // $user = User::find()->where(['id' => $userId])->one();
//
//            // $username = $user->username;
//
//            //new flow: where receipt_increment_alpahabetic_part of organization is used as prefix of case
//
//            $username = $org->receipt_increment_alpahabetic_part;
//
//        }
//
//        $model->client_name = $client_name;
//
//
//
//        $latestRow = Cases::getAll(@$organisation_id ? $organisation_id : null)->orderBy(['mID' => SORT_DESC])->one();
//
//        if (@$latestRow) {
//
//            $ID = $latestRow->mID + 1;
//
//        }else{
//
//            $ID = 1;
//
//        }
//
//
//
//        if (@$username) {
//
//            $uName = $username;
//
//        }else{
//
//            $uName = "None";
//
//        }
//
//
//
//        $model->mID = $ID;
//
//        $model->case_number = $uName . '-' . '10000' . $ID;
//
//        // end changing by Nemanja
//
//
//
//        $model->sending_country=$applicant->sending_country;
//
//        $model->applicant_first_name=$applicant->first_name;
//
//        $model->applicant_last_name=$applicant->last_name;
//
//        $model->date_of_birth=$applicant->date_of_birth;
//
//        $model->passport_number=$applicant->passport_number;
//
//        $model->mobile_number=$applicant->mobile_number;
//
//        $model->office_address=$applicant->office_address;

        if ($model->load(Yii::$app->request->post()) ) {

            $selectedClient = Client::find()->where(['id' => $model->client_id])->one();

            $model->client_billing_entity = Yii::$app->request->post('Cases')['client_billing_entity'];
            // $latestRow = Cases::getAll($model->organisation_id)->orderBy(['mID' => SORT_DESC])->one();
            $latestRow = Cases::find(['organisation_id' => $model->organisation_id])->orderBy(['mID' => SORT_DESC])->one();
            

            if (@$latestRow) {

                $ID = $latestRow->mID + 1;

            }else{

                $ID = 1;

            }

            $selectedOrg = Organisation::findOne($model->organisation_id);

            $username = $selectedOrg->receipt_increment_alpahabetic_part;

            if (@$username) {

                $uName = $username;

            }else{

                $uName = "None";

            }


            $model->mID = $ID;

            $model->case_number = $uName . '-' . '10000' . $ID;
            $model->case_work_office_id = Yii::$app->request->post('Cases')['case_work_office_id'];
        
            if($model->save()){
                
                $steps=CaseTypeStep::find()->where(['case_type_id'=>$model->case_type_id])->orderby('order asc')->all();

                $plannedDays=0;


             //steps are created at the time of case creation:

                foreach ($steps as  $order => $step) {

                    $caseStep = new CaseSteps();

//                    if($plannedDays==0){ // set status processing for first step

//                        $caseStep->status=GlobalConstant::CASE_STEP_STATUS_PROCESSING;

//                    }

                    $caseStep->status=GlobalConstant::CASE_STEP_STATUS_PROCESSING;// set status processing for all

                    $caseStep->case_id=$model->id;

                    $caseStep->case_type_step_id=$step->id;

                    $plannedDays+=$step->number_of_days;// added be after status

                     $caseStep->planned_completion_date=date("Y-m-d",strtotime("+".$plannedDays." days"));
                     $caseStep->order =  $order + 1;
                     -

                    $caseStep->save();

               }

              }

            return $this->redirect(['index']);

        } else {
            $caseTypes = ArrayHelper::map(CaseType::find()->orderBy([
                                                'order'=>SORT_ASC
                                            ])->all(),'id','name');

            return $this->render('create', [

                'model' => $model,
                'caseTypes' => $caseTypes

            ]);

        }

    }



    /**

     * Updates an existing Cases model.

     * If update is successful, the browser will be redirected to the 'view' page.

     * @param integer $id

     * @return mixed

     */

     public function actionUpdate($id)
     {
         $model = $this->findModel($id);
         $oldOrganisationId = $model->organisation_id;
         $oldCaseTypeId = $model->case_type_id;
     
         if ($model->load(Yii::$app->request->post())) {
            
             $model->client_billing_entity = Yii::$app->request->post('Cases')['client_billing_entity'];
             
             if ($oldOrganisationId != $model->organisation_id) {
                 $latestRow = Cases::find(['organisation_id' => $model->organisation_id])
                     ->orderBy(['mID' => SORT_DESC])
                     ->one();
     
                 $ID = $latestRow ? $latestRow->mID + 1 : 1;
                 $selectedOrg = Organisation::findOne($model->organisation_id);
                 $username = $selectedOrg ? $selectedOrg->receipt_increment_alpahabetic_part : "None";
                 $model->mID = $ID;
                 $model->case_number = $username . '-' . '10000' . $ID;
             }
     
             if ($model->save()) {
                 if ($oldCaseTypeId != $model->case_type_id) {
                     CaseSteps::deleteAll(['case_id' => $model->id]);
                     $steps = CaseTypeStep::find()->where(['case_type_id' => $model->case_type_id])
                         ->orderBy('order asc')
                         ->all();
                     
                     $plannedDays = 0;
                     foreach ($steps as $step) {
                         $caseStep = new CaseSteps();
                         $caseStep->status = GlobalConstant::CASE_STEP_STATUS_PROCESSING;
                         $caseStep->case_id = $model->id;
                         $caseStep->case_type_step_id = $step->id;
                         $plannedDays += $step->number_of_days;
                         $caseStep->planned_completion_date = date("Y-m-d", strtotime("+$plannedDays days"));
                         $caseStep->save();
                     }
                 }
                 return $this->redirect(['index']);
             }
         } else {
             $caseTypes = ArrayHelper::map(CaseType::find()->orderBy(['order' => SORT_ASC])->all(), 'id', 'name');
             return $this->render('update', [
                 'model' => $model,
                 'caseTypes' => $caseTypes
             ]);
         }
     }
     



    /**

     * Deletes an existing Cases model.

     * If deletion is successful, the browser will be redirected to the 'index' page.

     * @param integer $id

     * @return mixed

     */

    public function actionDelete($id)
    {
        $model = $this->findModel($id);


        //***** COMMENTED BLOCK BELOW WAS USED TO DELETE THE FILE ATTACHED TO THE APPLICANT IN THE OLD FLOW **********

//        //File Delete from S3/Server start
//        //getting all applicant fields
//        $client=\backend\modules\mii\jsonData\Applicant::returnData();
//        //getting json data of applicant info stored at the time of case creation/updation
//        $caseApplicantInfo = json_decode($model->case_applicant_info);
//
//        //looping over json attributes
//        foreach ($caseApplicantInfo as $columnName=>$value){
//            //looping over applicant fields
//            foreach ($client as $data){
//                //checking if an applicant field is of type file
//                if($data['type'] == 'file'){
//                    //checking if columnName from json matches field name from applicant fields
//                    if($columnName == str_replace("-","_",$data['name'])){
//                        $files = FileUpload::find()->where(['file_id' => $value])->all();
//                        if (!empty($files)) {
//                            foreach($files as $file) {
//                                if ($file->is_upload_to_s3 == 1) {
//                                    $bucket = getenv('AWS_S3_BUCKET');
//                                    $key = $file->s3_file_key;
//                                    //if deleteFromS3 returns false, then throw flash and redirect, otherwise proceed to next file
//                                    if (!Helper::deleteFromS3($bucket, $key)) {
//                                        Yii::$app->session->setFlash('error', 'Files could not be deleted properly, please try again.');
//                                        return $this->redirect(['index']);
//                                    }
//                                } else {
//                                    //if file is stored on the server, delete file
//                                    $filePath = \Yii::getAlias('@uploadPath'.'/'.MiiGlobalConstants::UPLOAD_IMAGES.'/'.$file->file_name);
//                                    unlink($filePath);
//                                }
//                            }
//                        }
//                    }
//                }
//            }
//        }

        if($model)
        {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Case Deleted Successfully.');
        }
        else
        {
            Yii::$app->session->setFlash('error', 'Case not found so could not be deleted.');
        }
        return $this->redirect(['index']);
    }



    /**

     * Finds the Cases model based on its primary key value.

     * If the model is not found, a 404 HTTP exception will be thrown.

     * @param integer $id

     * @return Cases the loaded model

     * @throws NotFoundHttpException if the model cannot be found

     */

    protected function findModel($id)

    {

        if (($model = Cases::findOne($id)) !== null) {

            return $model;

        } else {

            throw new NotFoundHttpException('The requested page does not exist.');

        }

    }



    public function actionCaseTypeMeta($case_type_id){

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $caseTypeModel = CaseType::find()->where(['id' => $case_type_id])->one();



        if(!empty($caseTypeModel)){

            $days = $caseTypeModel->totalStepDays();

            if($days > 0){

                $targetCompletionDate = Date('d-m-Y', strtotime("+". $days ." days"));

                return ["days"=>$days,"targetCompletionDate"=>$targetCompletionDate];

            }

        }





        return 'false';

    }

    public function actionAssignCase() {
        if (isset($_POST['caseWorkerID']) && isset($_POST['caseID'])) {
            $caseObject = new Cases();
            $caseObject->assignCase($_POST['caseWorkerID'], $_POST['caseID']);
        } 
    }
    public function actionAssignClientCase() {
        if (isset($_POST['clientCaseWorkerID']) && isset($_POST['caseID'])) {
            $caseObject = new Cases();
            $caseObject->assignClientCase($_POST['clientCaseWorkerID'], $_POST['caseID']);
        } 
    }
    public function actionAssignCaseManager() {
        if (isset($_POST['caseManagerID']) && isset($_POST['caseID'])) {
            $caseObject = new Cases();
            $caseObject->assignCaseManager($_POST['caseManagerID'], $_POST['caseID']);
        } 
    }

    public function actionAssignClientCaseManager() {
        if (isset($_POST['clientCaseManagerID']) && isset($_POST['caseID'])) {
            $caseObject = new Cases();
            $caseObject->assignClientCaseManager($_POST['clientCaseManagerID'], $_POST['caseID']);
        } 
    }

    public function actionMarkAsBilled() {
        if (isset($_POST['checked']) && isset($_POST['caseID'])) {
            $caseObject = Cases::findOne($_POST['caseID']);
            $caseObject->updateAttributes(['is_billed' => $_POST['checked']]);
        }
    }

    public function actionAttachDocuments($caseID) {
        $model = Cases::findOne($caseID);
        return $this->render('attach-documents', [
            'model' => $model,
        ]);
    }

    public function actionDownloadAttachment($attachmentID) {
        $fileModel = FileUpload::findOne($attachmentID);
        if (!empty($fileModel && isset($fileModel->s3_file_key))) {
            $file = Helper::getS3Object($fileModel->s3_file_key);
            if (!empty($file)) {
                $headers = Yii::$app->response->headers;
                $headers->set('Content-Description', 'File Transfer');
                $headers->set('Content-Disposition', 'attachment; filename=' . $fileModel->name);
                $headers->set('Content-Type', 'application/octet-stream');
                $headers->set('Expires', '0');
                $headers->set('Cache-Control', 'must-revalidate');
                $headers->set('Pragma', 'public');

                //send file to browser for download. 
                return $file["Body"];
            }
        }
    }

    public function actionSubmitAttachments() {
        if (Yii::$app->request->post() && isset(Yii::$app->request->post()['Cases']['additional_attachments'])) {
            $sessionID = Yii::$app->request->post()['Cases']['additional_attachments'];
            $tempFiles = TempFile::find()->where(['session_id' => $sessionID])->all();
            if (!empty($tempFiles)) {
                foreach ($tempFiles as $tempFile) {
                    $fileUploadModel = new FileUpload();
                    $fileUploadModel->file_id = $sessionID;
                    $fileUploadModel->name = $tempFile->name;
                    $fileUploadModel->extension = $tempFile->extension;
                    $fileUploadModel->file_name = $tempFile->file_name;
                    $fileUploadModel->created_at = $tempFile->created_at;
                    $fileUploadModel->updated_at = $tempFile->updated_at;
                    $fileUploadModel->uploaded_by = $tempFile->uploaded_by;

                    if (getenv('IS_UPLOAD_TO_S3') == 1) {
                        //Upload to S3 Start
                        //getting organisation
                        $id = Yii::$app->request->post()['Cases']['id'];
                        $caseModel = Cases::findOne($id);
                        $applicant = Applicant::findOne($caseModel->applicant_id);
                        // $client = Client::findOne($applicant->client_id);
                        $organisation = Organisation::findOne($caseModel->organisation_id);
                        
                        // $bucket = getenv('AWS_S3_BUCKET');
                        $bucket = 'pangea-live-bucket';
                        $filePath = \Yii::getAlias('@uploadPath'.'/'.MiiGlobalConstants::UPLOAD_IMAGES.'/'.$tempFile->file_name);
                        //Readable folder structure
                        $module = 'cases';
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
                                $tempFile->delete();
                                unlink($filePath); 
                            }
                        } else {
                            //if no url, error message will be displayed and redirect back to index
                            $this->redirect(['applicant/index']);
                        }
                    } else {
                        $fileUploadModel->attachment = $tempFile->attachment;
                        if ($fileUploadModel->save()) {
                            $tempFile->delete();
                        } 
                    }
                }
                $caseModel = Cases::findOne(Yii::$app->request->post()['Cases']['id']);
                $caseModel->updateAttributes(['additional_attachments' => $sessionID]);
            }
        }
        $caseID = Yii::$app->request->post()['Cases']['id'];
        return $this->redirect(['attach-documents', 'caseID' => $caseID]);
    }

    //removes temp file from the server, used with DropZone widget
    public function actionRemoveTempFile() {
        if (Yii::$app->request->post() && isset(Yii::$app->request->post()['sessionID']) && isset(Yii::$app->request->post()['fileName'])) {
            $sessionID = Yii::$app->request->post()['sessionID'];
            $fileName = Yii::$app->request->post()['fileName'];
            $file = TempFile::find()->where(['session_id' => $sessionID, 'uploaded_by' => Yii::$app->user->id, 'name' => $fileName])->orderBy(['id' => SORT_DESC])->one();
            if($file)
            {
                $file->delete();
                return json_encode([
                    'code' => 1,
                    'message' => 'File Removed!'
                ]);
            }
            else {
                return json_encode([
                    'code' => 0,
                    'message' => 'File not found!'
                ]);
            }
        }
    }

    //downloads the case data as csv and attached documents combined in a zip file
    public function actionDownloadCaseFile($caseID) {
        //Setting base path for temp files downloading from S3
        $tempFileBasePath = $_SERVER['DOCUMENT_ROOT'] . '/storage/web/temp/';
        if (!is_dir($tempFileBasePath)) {
            mkdir($tempFileBasePath);
        }

        //Setting base path for temp csv file
        $csvFileBasePath = $_SERVER['DOCUMENT_ROOT'] . '/storage/web/csv/';
        if (!is_dir($csvFileBasePath)) {
            mkdir($csvFileBasePath);
        }

        //Setting base path for temp zip file
        $zipFileBasePath = $_SERVER['DOCUMENT_ROOT'] . '/storage/web/zip/';
        if (!is_dir($zipFileBasePath)) {
            mkdir($zipFileBasePath);
        }

        $model = Cases::findOne($caseID);

        //Setting CSV Header using Case Attributes
        $columnNames = ['ID', 'Case Number', 'Client Name', 'Case Type', 'Case Status', 'Last Status Update', 'Target Completion Date', 'Pangea Case Worker', 'Client Case Worker', 'Is Sent For Billing?', 'Is Billed?'];
        
        $applicantLabels = (new Applicant)->attributeLabels();

        //getting applicant info from json
        // $caseApplicantFields = (array) json_decode($model->case_applicant_info);
        $caseApplicantFields = !empty($model->case_applicant_info) ? (array) json_decode($model->case_applicant_info, true) : [];

        unset($caseApplicantFields['client_id']);

        //Setting Column Names using applicant's information
        foreach($caseApplicantFields as $key => $field) {
            if (isset($applicantLabels[$key])) {
                //getting label from column name and assigning as CSV Header
                $columnNames[] = $applicantLabels[$key];
            }
        }

        $columnNames[] = 'Additional Attachments';
        //CSV Headers done
    
        //CSV Values start
        //Resolving boolean values into meaningful text
        $isSentForBilling = $model->is_sent_for_billing == 0 ? "No" : "Yes";
        $isBilled = $model->is_billed == 0 ? "No" : "Yes";

        //Resolving foreign keys into meaningful values
        $caseType = CaseType::findOne($model->case_type_id)->name;
        $caseStatus = ' ';
        $pangeaCaseWorker = ' ';
        $clientCaseWorker = ' ';
        if (!empty($model->case_status)) {
            $caseStatus = CaseStatus::findOne($model->case_status)->name;
        }
        if (!empty($model->assigned_to)) {
            $pangeaCaseWorker = User::findOne($model->assigned_to)->email;
        }
        if (!empty($model->raised_by_id)) {
            $clientCaseWorker = User::findOne($model->raised_by_id)->email;
        }

        //Filling Values as per Cases columns above
        $output = [$model->id, $model->case_number, $model->client_name, $caseType, $caseStatus, $model->last_status_update, $model->target_completion_date, $pangeaCaseWorker, $clientCaseWorker, $isSentForBilling, $isBilled];

        //Filling values as per Applicant columns above
        $fields = [];

        //Formatting data for csv and downloading s3 objects wherever applicable
        foreach($caseApplicantFields as $key => $field) {
            //this key comes when uploading using DropZone widget, hence skipping
            if (strpos($key, '_upload') !== false) {
                continue;
            }

            if (strpos($key, 'file_') !== false) {
                //finding all files associated with current field
                $files = FileUpload::find()->where(['file_id' => $field])->all();
                if (!empty($files)) {
                    $fileNames = '';
                    foreach($files as $file) {
                        if ($file->is_upload_to_s3) {
                            //constructing filenames to display in CSV
                            $fileNames = $fileNames . $file->name . ' | ';
                            $fileSavePath = $tempFileBasePath . $file->name;
                            //downloading file from S3 and temp saving on the server
                            Helper::downloadObjectFromS3($file->s3_file_key, $fileSavePath);
                        }
                    }

                    $output[] = $fileNames;
                } else {
                    $output[] = ' ';
                }
            } else {
                //if not file, add field value as is
                $output[] = $field;
            }
        }

        //if case has additional attachments, other than form fields, same logic as above
        if (!empty($model->additional_attachments)) {
            $additionalFiles = FileUpload::find()->where(['file_id' => $model->additional_attachments])->all(); 
            if (!empty($additionalFiles)) {
                $additionalFileNames = '';
                foreach($additionalFiles as $additionalFile) {
                    if ($additionalFile->is_upload_to_s3) {
                        $additionalFileNames = $additionalFileNames . $additionalFile->name . ' | ';
                        $fileSavePath = $tempFileBasePath . $additionalFile->name;
                        Helper::downloadObjectFromS3($additionalFile->s3_file_key, $fileSavePath);
                    }
                }
                $output[] = $additionalFileNames;
            }
        }

        //adding output as a row of CSV Values
        $csvValues = [];
        $csvValues[] = $output;

        //making temp csv file
        $csvFilePath = $csvFileBasePath . $model->case_number . '.csv';
        $csvFileOutput = fopen($csvFilePath, 'w+');
        ob_end_clean();
        fputcsv($csvFileOutput, $columnNames, ',');
        foreach($csvValues AS $columnValue){
            fputcsv($csvFileOutput, $columnValue, ',');
        }
        

        //Make Zip archive of S3 objects and CSV file
        $zip = new ZipArchive();

        //finding organisation of the case
        $applicant = Applicant::findOne($model->applicant_id);
        $client = Client::findOne($applicant->client_id);
        $organisation = Organisation::findOne($client->organisation_id);
        if (!empty($organisation) && isset($organisation->country_code)) {
            $zipName = 'Pangea-' . $organisation->country_code . '-' . $model->case_number;
        } else {
            $zipName = $model->case_number;
        }
        $zipPath = $zipFileBasePath . $zipName . '.zip';
        $zip->open($zipPath,  ZipArchive::CREATE | ZipArchive::OVERWRITE);

        //getting all temp files
        $tempFiles= scandir($tempFileBasePath . '/');

        //unsetting hidden files
        unset($tempFiles[1]);
        unset($tempFiles[0]);


        //adding S3 objects and additional attachments if any
        if (!empty($tempFiles)) {
            foreach ($tempFiles as $tempFile) {
                $zip->addFromString(basename($tempFile), file_get_contents($tempFileBasePath . $tempFile));
            }
        }   

        //adding CSV file
        $zip->addFromString(basename($csvFilePath), file_get_contents($csvFilePath));
        $zip->close();

        //removing S3 Objects from server
        foreach($tempFiles as $tempFile) {
            unlink($tempFileBasePath . $tempFile);
        }

        //removing CSV File from server
        unlink($csvFilePath);

        //sending zip file for download
        header('Content-Disposition: attachment; filename=' . basename($zipPath));
        header('Content-Type: application/zip');
        readFile($zipPath);

        //removing zip file from server
        unlink($zipPath);
    }
    // save issuance date , expiry date and interval days in databse
    public function actionSaveDates()
{
    $attachmentId = Yii::$app->request->post('id');
    $type = Yii::$app->request->post('type');
    $date = Yii::$app->request->post('date');
    $model = FileUpload::findOne($attachmentId);

    if ($model) {
        $success = false;

        // Update the model based on the type
        if ($type === 'issuance_date') {
            $model->issuance_date = $date;
            $success = true;
        }
        if ($type === 'expiry_date') {
            $model->expiry_date = $date;
            $success = true;
        }
        if ($type === 'interval_days_type_id') {
            $model->interval_days_type_id = $date;
            $success = true;
        }
      
        // Save and send a success/failure response
        if ($success && $model->save()) {
            return json_encode([
                'success' => true,
                'type' => $type,
            ]);
        } else {
            return json_encode([
                'success' => false,
            ]);
        }
    }

    return json_encode([
        'success' => false,
    ]);
}

    public function actionGetClientApplicants()
    {
        if(isset($_GET))
        {
            // $data = ArrayHelper::map(Applicant::find()->where(['client_id'=>$_GET['clientId'], 'parent_id' => null])->all(),'id','first_name');

            $data = ArrayHelper::map(
                Applicant::find()->where(['client_id' => $_GET['clientId'], 'parent_id' => null])->all(),
                'id',
                function($model) {
                    return trim($model->first_name . ' ' . $model->last_name);
                }
            );
            return json_encode($data);
            
        }
    }
    public function actionGetClientEntities()
    {
        if(isset($_GET))
        {
            $data = ArrayHelper::map(
                ClientEntity::find()->where(['client_id' => $_GET['clientId']])->all(),'id','name'
            );
            return json_encode($data);
            
        }
    }

    public function actionGetClientOrgs()
    {
        if(isset($_GET))
        {
            $data = ArrayHelper::map(Organisation::find()->leftJoin('tbl_client_organisation', 'tbl_client_organisation.organisation_id = tbl_organisation.id')->andWhere(['tbl_client_organisation.client_id' => $_GET['clientId']])->all(),'id','name');
            return json_encode($data);
        }
    }

    public function actionGetClientOrgsCaseManager()
    {
        if(isset($_GET))
        {
            $orgsIds = ArrayHelper::getColumn(Organisation::find()->leftJoin('tbl_client_organisation', 'tbl_client_organisation.organisation_id = tbl_organisation.id')->andWhere(['tbl_client_organisation.client_id' => $_GET['clientId']])->all(),'id');
            
            $data = ArrayHelper::map(User::find()->join('LEFT JOIN','tbl_rbac_auth_assignment','tbl_rbac_auth_assignment.user_id = id')->andWhere(['tbl_rbac_auth_assignment.item_name' => GlobalConstant::ROLE_CASE_MANAGER])->andWhere(['in', 'organisation_id',$orgsIds])->all(),
                                'id',
                                function($model) {
                                    if($model->userProfile)
                                    {
                                        $firstName = $model->userProfile->firstname;
                                        $lastName = $model->userProfile->lastname;

                                        if (!empty($firstName) || !empty($lastName)) {
                                            return trim($firstName . ' ' . $lastName);
                                        }
                                    }
                                    return $model->username;//will return if $model->userProfile doesn't exists or first or last name doesn't exist

                                }
                            );

            return json_encode($data);
        }
    }
    
    public function actionGetClientOrgsCaseWorker()
    {
        if(isset($_GET))
        {
            $orgsIds = ArrayHelper::getColumn(Organisation::find()->leftJoin('tbl_client_organisation', 'tbl_client_organisation.organisation_id = tbl_organisation.id')->andWhere(['tbl_client_organisation.client_id' => $_GET['clientId']])->all(),'id');

            $data = ArrayHelper::map(User::find()->join('LEFT JOIN','tbl_rbac_auth_assignment','tbl_rbac_auth_assignment.user_id = id')->andWhere(['tbl_rbac_auth_assignment.item_name' => GlobalConstant::ROLE_CASE_WORKER])->andWhere(['in', 'organisation_id',$orgsIds])->all(),
                                'id',
                                function($model) {
                                    if($model->userProfile)
                                    {
                                        $firstName = $model->userProfile->firstname;
                                        $lastName = $model->userProfile->lastname;

                                        if (!empty($firstName) || !empty($lastName)) {
                                            return trim($firstName . ' ' . $lastName);
                                        }
                                    }
                                    return $model->username;//will return if $model->userProfile doesn't exists or first or last name doesn't exist

                                }
                            );

            return json_encode($data);
        }
    }

    public function actionGetClientSideCaseManager()
    {
        if(isset($_GET))
        {

            $data = ArrayHelper::map(User::find()->join('LEFT JOIN','tbl_rbac_auth_assignment','tbl_rbac_auth_assignment.user_id = id')->andWhere(['tbl_rbac_auth_assignment.item_name' => GlobalConstant::ROLE_CLIENT_CASE_MANAGER])->andWhere(['client_id'=>$_GET['clientId']])->all(),
                                'id',
                                function($model) {
                                    if($model->userProfile)
                                    {
                                        $firstName = $model->userProfile->firstname;
                                        $lastName = $model->userProfile->lastname;

                                        if (!empty($firstName) || !empty($lastName)) {
                                            return trim($firstName . ' ' . $lastName);
                                        }
                                    }
                                    return $model->username;//will return if $model->userProfile doesn't exists or first or last name doesn't exist

                                }
                            );

            return json_encode($data);
        }
    }

    public function actionGetClientSideCaseWorker()
    {
        if(isset($_GET))
        {
            $data = ArrayHelper::map(User::find()->join('LEFT JOIN','tbl_rbac_auth_assignment','tbl_rbac_auth_assignment.user_id = id')->andWhere(['tbl_rbac_auth_assignment.item_name' => GlobalConstant::ROLE_CLIENT_CASE_WORKER])->andWhere(['client_id'=>$_GET['clientId']])->all(),
                                'id',
                                function($model) {
                                    if($model->userProfile)
                                    {
                                        $firstName = $model->userProfile->firstname;
                                        $lastName = $model->userProfile->lastname;

                                        if (!empty($firstName) || !empty($lastName)) {
                                            return trim($firstName . ' ' . $lastName);
                                        }
                                    }
                                    return $model->username;//will return if $model->userProfile doesn't exists or first or last name doesn't exist

                                }
                            );

            return json_encode($data);
        }
    }
    public function actionExportAll()
    {
        // Create a new instance of the search model
        $searchModel = new CasesSearch();
    
        // Load the search model with the query parameters
        $searchModel->load(Yii::$app->request->queryParams);
    
        // Prepare the dataProvider with the applied filters
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    
        // Disable pagination to get all filtered data
        $dataProvider->pagination = false;
    
        // Fetch all the filtered records
        $models = $dataProvider->getModels();
    
        // Prepare the data for export
        $data = [];
        foreach ($models as $model) {
            // Fetch the assigned case worker name
            $caseWorkerName = 'Not Assigned';
            if ($model->caseWorker) {
                $firstName = $model->caseWorker->userProfile->firstname ?? '';
                $lastName = $model->caseWorker->userProfile->lastname ?? '';
                $caseWorkerName = (!empty($firstName) || !empty($lastName)) 
                    ? trim($firstName . ' ' . $lastName) 
                    : $model->caseWorker->username;
            }
        
            // Base data for all users
            $caseData = [
                'Client Billing Entity' => $model->client_billing_entity,
                'Case Number' => $model->case_number,
                'Applicant Name' => $model->applicant->first_name . " " . $model->applicant->last_name,
                'Organisation' => $model->organisation->name,
                'Case type' => $model->caseType->name,
                'Case Status' => $model->caseStatus->name ?? 'No Status',
                'CaseWork Office' => $model->caseWorkOffice->name ?? 'No Case Work Office',
            ];
        
            // Add additional fields if the user is NOT a Client Case Worker
            if (!Yii::$app->user->identity->hasRole(GlobalConstant::ROLE_CLIENT_CASE_WORKER)) {
                $caseData['CaseWorker'] = $caseWorkerName ?? '';
                $caseData['Client Case Worker'] = $model->clientCaseWorker->username ?? '';
               
            }
            if (Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_ENTITY_MANAGER || 
            Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_GROUP_MANAGER) {
            $caseData['Client Case Worker'] = $model->clientCaseWorker->username ?? 'Not Assigned';
            $caseData['Client Case Manager'] = $model->clientCaseManager->username ?? 'Not Assigned';
            
        }
        $caseData['Created_at'] = $model->created_at;

        
            // Append data to the list
            $data[] = $caseData;
        }
        
        // Return JSON response
        return $this->asJson($data);
}    
}

