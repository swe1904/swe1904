<?php

namespace backend\controllers;


use Yii;
use backend\models\Nationality;
use backend\models\search\NationalitySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class NationalityController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new NationalitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Nationality();
        
        if ($model->load(Yii::$app->request->post())) {
            // Check if the nationality already exists
            $existingNationality = Nationality::findOne(['name' => $model->name]);
    
            if ($existingNationality) {
                // Add a flash message for duplicate nationality
                Yii::$app->session->setFlash('error', 'This nationality already exists.');
            } else {
                // Save the model if no duplicate is found
                if ($model->validate() && $model->save()) {
                    // Add flash message on successful creation
                    Yii::$app->session->setFlash('success', 'Nationality has been created successfully.');
                    return $this->redirect(['index', 'id' => $model->id]);
                }
            }
        }
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }
    
    

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Nationality::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
