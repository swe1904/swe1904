<?php

namespace backend\modules\messagesystem\controllers;

use backend\modules\messagesystem\components\MessageGlobalConstants;
use backend\modules\messagesystem\models\MessageTempFile;
use yii\db\StaleObjectException;
use yii\web\Controller;
use Yii;

class AttachmentController extends Controller
{
    public function actionUploadTempFile($session_id){
        if (isset($_FILES['attachment'])) {
            $file = \yii\web\UploadedFile::getInstanceByName('attachment');
            $extension = $file->extension;

            $fileName=$this->returnFileName().".".$extension;
            $uploadPath=Yii::getAlias('@uploadPath'.'/'.MessageGlobalConstants::UPLOAD_IMAGES.'/'.$fileName);
            $storageUrl=Yii::getAlias('@storageUrl'.'/'.MessageGlobalConstants::UPLOAD_IMAGES.'/'.$fileName);
            if($file->saveAs($uploadPath)){
                $tempFileModel=new MessageTempFile();
                $tempFileModel->session_id=$session_id;
                $tempFileModel->attachment=$storageUrl;
                $tempFileModel->name=$file->name;
                $tempFileModel->extension=$file->extension;
                $tempFileModel->attachment=$storageUrl;
                $tempFileModel->save();
            }
        }
        return $tempFileModel->id;
    }
    public function actionDeleteTempFile(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $tempFileModel=MessageTempFile::find()->where('name=:name and session_id=:session_id',[':name'=>$_POST['name'],':session_id'=>$_POST['session_id']])->one();
        try {
            $tempFileModel->delete();
        } catch (StaleObjectException $e) {
        } catch (\Exception $e) {
        }
    }
    public function returnFileName(){
        $milliseconds = round(microtime(true) * 1000);
        return $milliseconds;
    }
}
