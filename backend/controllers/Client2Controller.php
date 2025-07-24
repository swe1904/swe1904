<?php

namespace backend\controllers;

use Yii;
use backend\models\FileUpload;
use app\models\TempFile;
use backend\models\Client;
use backend\models\search\ClientSearch;
use yii\validators\RequiredValidator;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
        $searchModel = new ClientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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

        if ($model->load(Yii::$app->request->post())) {
            $errorAttributes=$this->checkIfFileUploadRequired($model->attributes,$model);
            foreach ($errorAttributes as $errorAttribute){
                $model->addError($errorAttribute,"file upload require");
            }

            if($model->hasErrors()){
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
            $this->checkIfFileUpload($model->attributes,$model);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    private function checkIfFileUploadRequired($attributes,$model){
        $requiredAttributes=[];
        $errorAttributes=[];
        foreach ($model->getValidators() as $validator){
            if($validator instanceof RequiredValidator){
                $requiredAttributes=$validator->attributes;
                break;
            }
        }
        $client=\backend\modules\mii\jsonData\Client::returnData();
        foreach ($attributes as $columnName=>$value){
            foreach ($client as $data){
                if($data['type']=='file'){
                    if($columnName==str_replace("-","_",$data['name'])){
                        if(in_array($columnName,$requiredAttributes)){
                            // save file uploads
                            $tempFileModels=TempFile::find()->where('session_id=:session_id',[':session_id'=>$value])->all();
                            if(empty($tempFileModels)){
                                array_push($errorAttributes,$columnName);
                            }
                        }
                    }
                }
            }
        }
        return $errorAttributes;

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

            // upload images
            $this->checkIfFileUpload($model->attributes,$model);

            // delete images
            foreach (Client::returnAttachmentAttr() as $attr){

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

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
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
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
