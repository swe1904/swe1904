<?php

namespace backend\modules\polling\controllers;

use Yii;
use backend\modules\polling\models\EmailTemplate;
use backend\modules\polling\models\EmailTemplateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EmailTemplateController implements the CRUD actions for EmailTemplate model.
 */
class EmailTemplateController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all EmailTemplate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmailTemplateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = new EmailTemplate();
        $model->user_id = Yii::$app->user->identity->id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Yii::$app->request->referrer);
        } else {

            return $this->render('index', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Displays a single EmailTemplate model.
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
     * Creates a new EmailTemplate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EmailTemplate();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing EmailTemplate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
     //   $attachmentModel = EmailAttachment::find()->where(['email_template_id' => $id])->all();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('update', [
                'model' => $model,
          //      'attachmentModel' => $attachmentModel,
            ]);
        }
    }

    /**
     * Deletes an existing EmailTemplate model.
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
     * Finds the EmailTemplate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EmailTemplate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EmailTemplate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

/*    public function actionUpload($id)
    {
        $attachmentModel = new EmailAttachment();
        $attachmentModel->email_template_id = $id;
        $uploadPath = Yii::getAlias('@storage') . '/web/handyrecruiter/templateAttachment';

        if (isset($_FILES['attachment'])) {
            $file = \yii\web\UploadedFile::getInstanceByName('attachment');
            // $original_name = $file->baseName;
            $newFileName = \Yii::$app->security
                    ->generateRandomString(5) . $file->baseName . '.' . $file->extension;
// you can write save code here before uploading.
            if ($file->saveAs($uploadPath . '/' . $newFileName)) {
                $attachmentModel->image = Url::home() . '/storage/web/handyrecruiter/templateAttachment/' . $newFileName;
                // $model->original_name = $original_name;
                if ($attachmentModel->save(false)) {
                    echo \yii\helpers\Json::encode($file);
                } else {
                    echo \yii\helpers\Json::encode($attachmentModel->getErrors());
                }
            }
        } else {
            return $this->render('upload', [
                'model' => $attachmentModel,
            ]);
        }

        return false;
    }*/

 /*   public function actionDeleteAttachment($id)
    {
        if (($model = EmailAttachment::findOne($id)) !== null) {
$model->delete();
echo('success');
        }
        else{
            echo 'data not found';
        }
    }*/
}
