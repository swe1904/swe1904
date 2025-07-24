<?php

namespace backend\controllers;

use Yii;
use backend\models\Position;
use yii\data\Pagination;
use backend\models\search\PositionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class PositionController extends Controller
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
        $searchModel = new PositionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Get the pagination data
        $pagination = $dataProvider->pagination;

        // Fetch positions with the applied search
        $positions = $dataProvider->getModels();

        return $this->render('index', [
            'positions' => $positions,
            'dataProvider' => $dataProvider,
            'pagination' => $pagination,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate()
    {
        $model = new Position();

        if ($model->load(Yii::$app->request->post())) {
            // Convert name to uppercase before saving
            $model->name = strtoupper($model->name);

            // Check for duplicate position name
            $existingPosition = Position::findOne(['name' => $model->name]);
            if ($existingPosition) {
                // Set an error flash message if a duplicate is found
                Yii::$app->session->setFlash('error', 'This position already exists.');
            } elseif ($model->save()) {
                // Set a success flash message after saving
                Yii::$app->session->setFlash('success', 'Position created successfully.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            // Convert name to uppercase before saving
            $model->name = strtoupper($model->name);

            // Check for duplicate position name, excluding the current record
            $existingPosition = Position::findOne(['name' => $model->name]);
            if ($existingPosition && $existingPosition->id != $model->id) {
                // Set an error flash message if a duplicate is found
                Yii::$app->session->setFlash('error', 'This position already exists.');
            } elseif ($model->save()) {
                // Set a success flash message after saving
                Yii::$app->session->setFlash('success', 'Position updated successfully.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionDelete($id)
    {
        // Find the model based on the provided ID
        $model = $this->findModel($id);
        
        if ($model->delete()) {
            // Set a success flash message if the delete operation is successful
            Yii::$app->session->setFlash('success', 'Position deleted successfully.');
        } else {
            // Set an error flash message if something goes wrong
            Yii::$app->session->setFlash('error', 'Failed to delete the position.');
        }
    
        // Redirect to the index page
        return $this->redirect(['index']);
    }
    

    protected function findModel($id)
    {
        if (($model = Position::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
