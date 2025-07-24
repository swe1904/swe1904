<?php
namespace backend\controllers;

use Yii;
use backend\models\BloodGroup;
use backend\models\search\BloodGroupSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BloodGroupController implements the CRUD actions for BloodGroup model.
 */
class BloodGroupController extends Controller
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

    /**
     * Lists all BloodGroup models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BloodGroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BloodGroup model.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new BloodGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BloodGroup();
        
        if ($model->load(Yii::$app->request->post())) {
            // Check if the blood group already exists in the database
            $existingBloodGroup = BloodGroup::find()->where(['name' => $model->name])->one();
            
            if ($existingBloodGroup) {
                // If the blood group exists, show a flash message and reload the form
                Yii::$app->session->setFlash('error', 'This blood group already exists.');
            } else {
                // If no duplicate is found, save the blood group
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Blood group has been created successfully.');
                    return $this->redirect(['index']);
                }
            }
        }
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }
    
    /**
     * Updates an existing BloodGroup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
    
        if ($model->load(Yii::$app->request->post())) {
            // Check if a record with the same name exists, excluding the current record
            $duplicate = BloodGroup::find()
                ->where(['name' => $model->name])
                ->andWhere(['!=', 'id', $model->id]) // Exclude the current record
                ->one();
    
            if ($duplicate) {
                Yii::$app->session->setFlash('error', 'This Blood Group name already exists.');
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
    
            // Save the model if no duplicates
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Blood Group has been updated successfully.');
                return $this->redirect(['index', 'id' => $model->id]);
            }
        }
    
        return $this->render('update', [
            'model' => $model,
        ]);
    }
    

    /**
     * Deletes an existing BloodGroup model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Blood Group has been deleted successfully.');
        return $this->redirect(['index']);
    }

    /**
     * Finds the BloodGroup model based on its primary key value.
     * @param int $id
     * @return BloodGroup
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = BloodGroup::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
