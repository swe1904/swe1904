<?php

namespace backend\controllers;

use app\components\GlobalConstant;
use backend\models\Client;
use backend\models\ClientEntity;
use backend\models\search\ClientEntitySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Organisation;
use Yii;
use common\models\User;
use app\models\TempFile;
use backend\models\FileUpload;
use backend\components\Helper;
use backend\modules\mii\components\MiiGlobalConstants;

/**
 * ClientEntityController implements the CRUD actions for ClientEntity model.
 */
class ClientEntityController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all ClientEntity models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ClientEntitySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ClientEntity model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ClientEntity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ClientEntity();

        if ($this->request->isPost) {
            
            if ($model->load($this->request->post()) && $model->save()) {

            $sessionID = Yii::$app->request->post()['ClientEntity']['additional_attachments'];
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
                        $id = $model->id;
                        
                        // $organisation = Organisation::findOne(Yii::$app->user->identity->organisation_id);
                        

                        // $bucket = getenv('AWS_S3_BUCKET');
                        $bucket = 'pangea-live-bucket';
                        $filePath = \Yii::getAlias('@uploadPath'.'/'.MiiGlobalConstants::UPLOAD_IMAGES.'/'.$tempFile->file_name);
                        //Readable folder structure
                        $module = 'client';
                        $organisation = Organisation::findOne(Yii::$app->user->identity->organisation_id); //will have some value if role not client
                        if($organisation)
                        {
                            $S3Key = $organisation->name . '-' . $organisation->id . '/' . $module . '/' . $id . '/' . basename($filePath);    
                        }
                        else // else block for client
                        {
                            $client = Client::findOne(['user_id'=> Yii::$app->user->identity->id]);
                            $S3Key = $client->name . '-' . $client->id . '/' . $module . '/' . $id . '/' . basename($filePath);
                        }
                        // $S3Key = $organisation->name . '-' . $organisation->id . '/' . $module . '/' . $id . '/' . basename($filePath);
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
                            if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT)
                                return $this->redirect(['index']);
                            else
                                return $this->redirect(['client/view','id'=>$model->client_id]);
                            // $this->redirect(['applicant/index']);
                        }
                    } else {
                        $fileUploadModel->attachment = $tempFile->attachment;
                        if ($fileUploadModel->save()) {
                            $tempFile->delete();
                        } 
                    }
                }
                
            }
                Yii::$app->session->setFlash('success', 'Client Entity Created Successfully.');
                if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT)
                    return $this->redirect(['index']);
                else
                    return $this->redirect(['client/view','id'=>$model->client_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        if(!(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT) ) {
            if(isset($_GET['client_id']))
            {
                $client = Client::findOne($_GET['client_id']);
                if (!$client) {
                    Yii::$app->session->setFlash('error', 'Client not found');
                    return $this->redirect(['client/index']);
                }
            }
            else
            {
                Yii::$app->session->setFlash('error', 'Create client entity from Client index page');
                    return $this->redirect(['client/index']);
            }
        }
        

        // $organization = Organisation::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
        // if($organization)
        //     $organizationId = $organization->id;
        // else
        //     $organizationId = User::findOne(Yii::$app->user->id)->organisation_id;
        
        // if ($organizationId) {
        //     $clients = Client::find()->select(['id', 'client_name'])->where(['organisation_id' => $organizationId])->all();
        // } else {
        //     $clients = Client::find()->select(['id', 'client_name'])->all();
        // }

        // $clients = \yii\helpers\ArrayHelper::map($clients, 'id', 'client_name');

        return $this->render('create', [
            'model' => $model,
            // 'clients' => $clients,
        ]);
    }

    /**
     * Updates an existing ClientEntity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Client Entity Updated Successfully.');

            if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT)
                    return $this->redirect(['index']);
                else
                    return $this->redirect(['client/view','id'=>$model->client_id]);
        }

        if (isset(Yii::$app->user->identity->organisation_id)) {
            $clients = Client::find()->select(['id', 'client_name'])->where(['organisation_id' => Yii::$app->user->identity->organisation_id])->all();
        } else {
            $clients = Client::find()->select(['id', 'client_name'])->all();
        }
        
        $clients = \yii\helpers\ArrayHelper::map($clients, 'id', 'client_name');

        return $this->render('update', [
            'model' => $model,
            'clients' => $clients
        ]);
    }

    /**
     * Deletes an existing ClientEntity model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        Yii::$app->session->setFlash('success', 'Client Entity Deleted Successfully.');
        // return $this->redirect(['index']);
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the ClientEntity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return ClientEntity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ClientEntity::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
    }

    public function actionGetClientEntities($clientID) {
        $clientEntites = ClientEntity::find()->where(['client_id' => $clientID])->asArray()->all();
        if (!empty($clientEntites)) {
            return json_encode([
                'code' => 1,
                'clientEntities' => $clientEntites
            ]);
        } else {
            return json_encode([
                'code' => 0,
                'message' => 'No client entities found.'
            ]);
        }
    }
}
