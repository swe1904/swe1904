<?php

namespace backend\controllers;

use Yii;
use backend\models\EmergencyContactRelationship;
use backend\models\search\EmergencyContactRelationshipSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination; 

class EmergencyContactRelationshipController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all EmergencyContactRelationship models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmergencyContactRelationshipSearch();
        
        // Get the "per-page" parameter from the GET request, default to 10
        $perPage = Yii::$app->request->get('per-page', 10);
    
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $perPage);
    
        // Get pagination object from data provider
        $pagination = $dataProvider->pagination;
    
        return $this->render('index', [
            'searchModel' => $searchModel,
            'emergencyContacts' => $dataProvider->models,
            'pagination' => $pagination,
        ]);
    }
    

    


    /**
     * Displays a single EmergencyContactRelationship model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new EmergencyContactRelationship model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EmergencyContactRelationship();

        if ($model->load(Yii::$app->request->post())) {
            // Duplicate check
            $existing = EmergencyContactRelationship::find()
                ->where(['relationship_name' => $model->relationship_name])
                ->one();
            if ($existing) {
                Yii::$app->session->setFlash('error', 'This relationship name already exists.');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Emergency Contact Relationship has been created successfully.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing EmergencyContactRelationship model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            // Duplicate check
            $existing = EmergencyContactRelationship::find()
                ->where(['relationship_name' => $model->relationship_name])
                ->andWhere(['!=', 'id', $model->id]) // Exclude the current record
                ->one();

            if ($existing) {
                Yii::$app->session->setFlash('error', 'This relationship name already exists.');
                return $this->render('update', [
                    'model' => $model,
                ]);
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Emergency Contact Relationship has been updated successfully.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing EmergencyContactRelationship model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        Yii::$app->session->setFlash('success', 'Emergency Contact Relationship has been deleted successfully.');
        return $this->redirect(['index']);
    }

    /**
     * Finds the EmergencyContactRelationship model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EmergencyContactRelationship the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EmergencyContactRelationship::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
