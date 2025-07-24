<?php

namespace backend\controllers;

use backend\models\CaseStatus;
use backend\models\search\CaseStatusSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use backend\models\Cases;

/**
 * CaseStatusController implements the CRUD actions for CaseStatus model.
 */
class CaseStatusController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all CaseStatus models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CaseStatusSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $model = new CaseStatus();
        return $this->render('index', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CaseStatus model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CaseStatus model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new CaseStatus();

        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            Yii::$app->session->setFlash('success', 'Case Status has been created successfully.');
            return $this->redirect('index');
        } else {
            return $this->redirect('index');
        }
    }

    /**
     * Updates an existing CaseStatus model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Case Status has been updated successfully.');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CaseStatus model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        Yii::$app->session->setFlash('success', 'Case Status has been deleted successfully.');
        return $this->redirect(['index']);
    }

    /**
     * Finds the CaseStatus model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return CaseStatus the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CaseStatus::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
    }

    //Updates status of a case
    public function actionUpdateStatusOfCase() {
        if (isset(Yii::$app->request->post()['caseID']) && isset(Yii::$app->request->post()['statusID'])) {
            $caseID = Yii::$app->request->post()['caseID'];
            $statusID = Yii::$app->request->post()['statusID'];
            $case = Cases::findOne($caseID);
            if (!empty($case)) {
                $case->updateAttributes(['case_status' => $statusID]);
                return json_encode([
                    'code' => 1,
                    'message' => 'Status has been updated successfully'
                ]);
            } 

            return json_encode([
                'code' => 0,
                'message' => 'Status could not be updated, please try again'
            ]);
        } 
    }
}
