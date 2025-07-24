<?php

namespace backend\modules\mii\controllers;

use app\models\TempFile;
use backend\modules\mii\components\MiiGlobalConstants;
use backend\modules\mii\jsonData\Client;
use backend\modules\mii\migration\DatabaseMigration;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;

class FileUploadController extends Controller
{
   /* public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'delete-list'=>['post']
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['custom-builder'],
                        'allow' => true,
                        'roles' => ['?'],
                        'denyCallback' => function () {
                            return Yii::$app->controller->redirect(['/user/default/index']);
                        }
                    ],
                ]
            ],
        ];
    }*/
    public function actionUploadTempFile($session_id)
    {
        if (isset($_FILES['attachment'])) {
            $file = \yii\web\UploadedFile::getInstanceByName('attachment');
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
            $fileName = $this->returnFileName() . '.' . $file->extension;
            $uploadPath = \Yii::getAlias('@uploadPath/' . MiiGlobalConstants::UPLOAD_IMAGES . '/' . $fileName);
            $storageUrl = \Yii::getAlias('@storageUrl/' . MiiGlobalConstants::UPLOAD_IMAGES . '/' . $fileName);
    
            if ($file->saveAs($uploadPath)) {
                $tempFileModel = new TempFile();
                $tempFileModel->session_id = $session_id;
                $tempFileModel->attachment = $storageUrl;
                $tempFileModel->name = $file->name;
                $tempFileModel->extension = $file->extension;
                $tempFileModel->file_name = $fileName;
                $tempFileModel->created_at = date('Y-m-d');
                $tempFileModel->updated_at = date('Y-m-d');
                $tempFileModel->uploaded_by = Yii::$app->user->id;
                $tempFileModel->save();
            }
        }
    }
    public function returnFileName(){
        $milliseconds = round(microtime(true) * 1000);
        return $milliseconds;
    }
}
