<?php

namespace backend\modules\polling\controllers;

use backend\models\FileUpload;
use app\models\TempFile;
use backend\modules\polling\models\PollingTempFile;
use yii\web\Controller;

class DefaultController extends Controller
{
    const UPLOAD_IMAGES = "images";
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    public function actionUploadTempFile($session_id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (isset($_FILES['attachment'])) {
            $file = \yii\web\UploadedFile::getInstanceByName('attachment');
            $extension = $file->extension;

            $fileName=$this->returnFileName().".".$extension;
            $uploadPath=\Yii::getAlias('@uploadPath'.'/'.self::UPLOAD_IMAGES.'/'.$fileName);
            $storageUrl=\Yii::getAlias('@storageUrl'.'/'.self::UPLOAD_IMAGES.'/'.$fileName);
            if($file->saveAs($uploadPath)){
                $tempFileModel=new FileUpload();
                $tempFileModel->file_id=$session_id;
                $tempFileModel->attachment=$storageUrl;
                $tempFileModel->name=$file->name;
                $tempFileModel->extension=$file->extension;
                $tempFileModel->attachment=$storageUrl;
                $tempFileModel->save();
                return ["status"=>true,"id"=>$tempFileModel->id];
            }
        }
    }
    public function returnFileName(){
        $milliseconds = round(microtime(true) * 1000);
        return $milliseconds;
    }
    public function actionDeleteTempFile(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $tempFileModel=FileUpload::find()->where('id=:id',[':id'=>$_POST['id']])->one();
        $tempFileModel->delete();
    }
    public function actionCheck(){
        return \yii\helpers\Url::to(['room-listing/upload-temp-file','session_id'=>"s"]);
    }
}
