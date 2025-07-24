<?php

namespace backend\modules\i18n\controllers;

use backend\modules\i18n\models\I18nMessage;
use Yii;
use backend\modules\i18n\models\I18nSourceMessage;
use backend\modules\i18n\models\search\I18nSourceMessageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * I18nSourceMessageController implements the CRUD actions for I18nSourceMessage model.
 */
class I18nSourceMessageController extends Controller
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
     * Lists all I18nSourceMessage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new I18nSourceMessageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $searchModelMessage = new I18nSourceMessageSearch();
        $dataProviderMessage = $searchModelMessage->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'model' => new I18nSourceMessage(),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single I18nSourceMessage model.
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
     * Creates a new I18nSourceMessage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new I18nSourceMessage();

        if ($model->load(Yii::$app->request->post())) {

            $model->category='backend';
            if ($model->save()) {
                /*
             * get all the language and update translation in message table
             * */
                $languageModel = Yii::$app->params['translationLocales'];

                
                foreach ($languageModel as $key=>$language) {
                    $modelMessage = new I18nMessage();
                    $modelMessage->id = $model->id;
                    $modelMessage->language = $key;
                    if($key=='es'){ // for espanol
                        $modelMessage->translation = $model->espanolLanguage;
                    }
                    else if($key=='ar-AE'){ // for arabic
                        $modelMessage->translation = $model->arabicLanguage;
                    }
                    else{ //for english as it is
                        $modelMessage->translation = $model->message;
                    }
                    $modelMessage->save(false);
                }

            } else {

            }

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing I18nSourceMessage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $i18nMessageModel =I18nMessage::find()->andWhere(['id'=>$id])->all();

        $model = $this->findModel($id);

        /*for dutch translation textbox*/
        foreach($i18nMessageModel as $i18Model) {
            if($i18Model->language == "es") {
                $model->espanolLanguage = $i18Model->translation;
            }
            else if($i18Model->language == "ar-AE") {
                $model->arabicLanguage = $i18Model->translation;
            }
        }

        if ($model->load(Yii::$app->request->post()) ) {
            $model->category='backend';
            if ($model->save()) {
                /*
             * get all the language and update translation in message table
             * */
                $i18nMessageModel = I18nMessage::deleteAll(['id' => $id]);
                $languageModel = Yii::$app->params['translationLocales'];

                foreach ($languageModel as $key=>$language) {
                    $modelMessage = new I18nMessage();
                    $modelMessage->id = $model->id;
                    $modelMessage->language = $key;
                    if($key=='es'){ // for espanol
                        $modelMessage->translation = $model->espanolLanguage;
                    }
                    else if($key=='ar-AE'){ // for arabic
                        $modelMessage->translation = $model->arabicLanguage;
                    }
                    else{ //for english as it is
                        $modelMessage->translation = $model->message;
                    }
                    if($modelMessage->save(false))
                    {

                    }else {

                    }
                }

            } else {


            }
            return $this->redirect(['index']);

        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing I18nSourceMessage model.
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
     * Finds the I18nSourceMessage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return I18nSourceMessage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = I18nSourceMessage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
