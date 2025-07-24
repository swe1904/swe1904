<?php

namespace backend\controllers;

use Yii;
use common\models\Organisation;
use common\models\User;
use backend\models\FileUpload;
use app\models\TempFile;
use backend\models\Client;
use backend\models\search\ClientSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\GlobalConstant;
use backend\modules\mii\components\MiiGlobalConstants;
use backend\components\Helper;
use yii\helpers\ArrayHelper;
use backend\models\ClientOrganisation;

/**
 * ClientController implements the CRUD actions for Client model.
 */
class ClientController extends Controller
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
     * Lists all Client models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Client();
        $searchModel = new ClientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $organisations = ArrayHelper::map(Organisation::find()->all(), 'id', 'name');

        $model->user_id=yii::$app->user->id; //confirm usage of 'user_id' attribute
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //assigning client to organisations
            $selectedOrganisationIds = Yii::$app->request->post()["Client"]["selectedOrganisations"];
            foreach($selectedOrganisationIds as $organisationId)
            {
                $clientOrg = new ClientOrganisation();
                $clientOrg->client_id = $model->id;
                $clientOrg->organisation_id = $organisationId;
                $clientOrg->save();
            }
            return $this->redirect(['index']);
        }
        return $this->render('index', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'organisations' => $organisations
        ]);
    }

    /**
     * Displays a single Client model.
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
     * Creates a new Client model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Client();
        // if (Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN) {
        //     $organisation=Organisation::find()->where('user_id=:user_id',[':user_id'=>yii::$app->user->id])->one();
        // } elseif (Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_MANAGER) {
        //     $organisation=Organisation::findOne(User::findOne(Yii::$app->user->id));
        // }
        // elseif(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_MANAGER)
        // {
        //     $organisationId = User::findOne(Yii::$app->user->id)->organisation_id;
        // }
        // if($organisation)
        //     $model->organisation_id=$organisation->id;
        // elseif($organisationId)
        //     $model->organisation_id=$organisationId;

        $model->user_id=yii::$app->user->id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //assigning client to organisations
            $selectedOrganisationIds = Yii::$app->request->post()["Client"]["selectedOrganisations"];
            foreach($selectedOrganisationIds as $organisationId)
            {
                $clientOrg = new ClientOrganisation();
                $clientOrg->client_id = $model->id;
                $clientOrg->organisation_id = $organisationId;
                $clientOrg->save();
            }

            // $this->checkIfFileUpload($model->attributes,$model);

            // $sessionID = Yii::$app->request->post()['Client']['additional_attachments'];
            // $tempFiles = TempFile::find()->where(['session_id' => $sessionID])->all();
            // if (!empty($tempFiles)) {
            //     foreach ($tempFiles as $tempFile) {
            //         $fileUploadModel = new FileUpload();
            //         $fileUploadModel->file_id = $sessionID;
            //         $fileUploadModel->name = $tempFile->name;
            //         $fileUploadModel->extension = $tempFile->extension;
            //         $fileUploadModel->file_name = $tempFile->file_name;
            //         $fileUploadModel->created_at = $tempFile->created_at;
            //         $fileUploadModel->updated_at = $tempFile->updated_at;
            //         $fileUploadModel->uploaded_by = $tempFile->uploaded_by;

            //         if (getenv('IS_UPLOAD_TO_S3') == 1) {
            //             //Upload to S3 Start
            //             //getting organisation
            //             $id = $model->id;
            //             // $caseModel = Cases::findOne($id);
            //             // $applicant = Applicant::findOne($caseModel->applicant_id);
            //             // $client = Client::findOne($id);
            //             // $organisation = Organisation::findOne($model->organisation_id);
            //             $organisation = Organisation::findOne(Yii::$app->user->identity->organisation_id);
                        

            //             $bucket = getenv('AWS_S3_BUCKET');
            //             $filePath = \Yii::getAlias('@uploadPath'.'/'.MiiGlobalConstants::UPLOAD_IMAGES.'/'.$tempFile->file_name);
            //             //Readable folder structure
            //             $module = 'client';
            //             $S3Key = $organisation->name . '-' . $organisation->id . '/' . $module . '/' . $id . '/' . basename($filePath);
            //             $errorMessage = 'Failed to upload files. Please try again.';
            //             //Uploading to S3 and getting URL
            //             $url = Helper::uploadToS3($bucket, $S3Key, $filePath, $errorMessage);
            //             if ($url) {
            //                 $fileUploadModel->attachment = $url;
            //                 $fileUploadModel->is_upload_to_s3 = 1;
            //                 $fileUploadModel->s3_file_key = $S3Key;
            //                 if($fileUploadModel->save()){
            //                     //Deleting temp file from DB and Server
            //                     $tempFile->delete();
            //                     unlink($filePath); 
            //                 }
            //             } else {
            //                 //if no url, error message will be displayed and redirect back to index
            //                 $this->redirect(['applicant/index']);
            //             }
            //         } else {
            //             $fileUploadModel->attachment = $tempFile->attachment;
            //             if ($fileUploadModel->save()) {
            //                 $tempFile->delete();
            //             } 
            //         }
            //     }
            //     // $caseModel = Cases::findOne(Yii::$app->request->post()['Cases']['id']);
            //     // $caseModel->updateAttributes(['additional_attachments' => $sessionID]);
            // }
            return $this->redirect(['index']);

        } else {
            $organisations = ArrayHelper::map(Organisation::find()->all(), 'id', 'name');
            return $this->render('create', [
                'model' => $model,
                'organisations' => $organisations
            ]);
        }
    }

    private function checkIfFileUpload($attributes,$model){
        $client=\backend\modules\mii\jsonData\Client::returnData();
        foreach ($attributes as $columnName=>$value){
            foreach ($client as $data){
                if($data['type']=='file'){
                    if($columnName==str_replace("-","_",$data['name'])){
                        // save file uploads
                        $tempFileModels=TempFile::find()->where('session_id=:session_id',[':session_id'=>$value])->all();
                        if(!empty($tempFileModels)){
                            foreach ($tempFileModels as $tempFileModel){
                                // create file uploads
                                $fileUploadModel=new FileUpload();
                                $fileUploadModel->file_id=$value;
                                $fileUploadModel->attachment=$tempFileModel->attachment;
                                $fileUploadModel->name=$tempFileModel->name;
                                $fileUploadModel->extension=$tempFileModel->extension;
                                if($fileUploadModel->save()){
                                    $tempFileModel->delete();
                                }
                            }
                        }
                    }
                }

            }
        }

    }

    /**
     * Updates an existing Client model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            // Removing existing client to organisation mapping
            ClientOrganisation::deleteAll(['client_id' => $model->id]);

            //saving selected client to organisations mapping
            $selectedOrganisationIds = Yii::$app->request->post()["Client"]["selectedOrganisations"];
            foreach($selectedOrganisationIds as $organisationId)
            {
                $clientOrg = new ClientOrganisation();
                $clientOrg->client_id = $model->id;
                $clientOrg->organisation_id = $organisationId;
                $clientOrg->save();
            }

            // // upload images
            // $this->checkIfFileUpload($model->attributes,$model);


            // $sessionID = Yii::$app->request->post()['Client']['additional_attachments'];
            // $tempFiles = TempFile::find()->where(['session_id' => $sessionID])->all();
            // if (!empty($tempFiles)) {
            //     foreach ($tempFiles as $tempFile) {
            //         $fileUploadModel = new FileUpload();
            //         $fileUploadModel->file_id = $sessionID;
            //         $fileUploadModel->name = $tempFile->name;
            //         $fileUploadModel->extension = $tempFile->extension;
            //         $fileUploadModel->file_name = $tempFile->file_name;
            //         $fileUploadModel->created_at = $tempFile->created_at;
            //         $fileUploadModel->updated_at = $tempFile->updated_at;
            //         $fileUploadModel->uploaded_by = $tempFile->uploaded_by;

            //         if (getenv('IS_UPLOAD_TO_S3') == 1) {
            //             //Upload to S3 Start
            //             //getting organisation
            //             // $id = Yii::$app->request->post()['Client']['id'];
            //             // $caseModel = Cases::findOne($id);
            //             // $applicant = Applicant::findOne($caseModel->applicant_id);
            //             // $client = Client::findOne($id);
            //             // $organisation = Organisation::findOne($model->organisation_id);
            //             $organisation = Organisation::findOne(Yii::$app->user->identity->organisation_id);

            //             $bucket = getenv('AWS_S3_BUCKET');
            //             $filePath = \Yii::getAlias('@uploadPath'.'/'.MiiGlobalConstants::UPLOAD_IMAGES.'/'.$tempFile->file_name);
            //             //Readable folder structure
            //             $module = 'client';
            //             $S3Key = $organisation->name . '-' . $organisation->id . '/' . $module . '/' . $id . '/' . basename($filePath);
            //             $errorMessage = 'Failed to upload files. Please try again.';
            //             //Uploading to S3 and getting URL
            //             $url = Helper::uploadToS3($bucket, $S3Key, $filePath, $errorMessage);
            //             if ($url) {
            //                 $fileUploadModel->attachment = $url;
            //                 $fileUploadModel->is_upload_to_s3 = 1;
            //                 $fileUploadModel->s3_file_key = $S3Key;
            //                 if($fileUploadModel->save()){
            //                     //Deleting temp file from DB and Server
            //                     $tempFile->delete();
            //                     unlink($filePath); 
            //                 }
            //             } else {
            //                 //if no url, error message will be displayed and redirect back to index
            //                 $this->redirect(['applicant/index']);
            //             }
            //         } else {
            //             $fileUploadModel->attachment = $tempFile->attachment;
            //             if ($fileUploadModel->save()) {
            //                 $tempFile->delete();
            //             } 
            //         }
            //     }
            //     // $caseModel = Cases::findOne(Yii::$app->request->post()['Cases']['id']);
            //     // $caseModel->updateAttributes(['additional_attachments' => $sessionID]);
            // }


            // // delete images
            // foreach (Client::returnAttachmentAttr() as $attr){

            //     if(!empty($model->$attr)){
            //         $ids=explode(",",$model->$attr);
            //         foreach ($ids as $id){
            //             $attachmentModel=FileUpload::find()->where('id=:id',[':id'=>$id])->one();
            //             if(!empty($attachmentModel)){
            //                 $attachmentModel->delete();
            //             }
            //         }
            //     }
            // }

             return $this->redirect(['index']);
        } else {
            $organisations = ArrayHelper::map(Organisation::find()->all(), 'id', 'name');
            return $this->render('update', [
                'model' => $model,
                'organisations' => $organisations
            ]);
        }
    }

    /**
     * Deletes an existing Client model.
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
     * Finds the Client model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Client the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Client::findOne($id)) !== null) {
            $client=\backend\modules\mii\jsonData\Client::returnData();
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
}
