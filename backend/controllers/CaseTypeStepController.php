<?php

namespace backend\controllers;

use himiklab\sortablegrid\SortableGridBehavior;
use Yii;
use backend\models\CaseTypeStep;
use backend\models\search\CaseTypeStepSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use richardfan\sortable\SortableAction;
use himiklab\sortablegrid\SortableGridAction;
use yii\web\Response;

/**
 * CaseTypeStepController implements the CRUD actions for CaseTypeStep model.
 */
class CaseTypeStepController extends Controller
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

    public function actions()
    {

        return [
            'sort' => [
                'class' => SortableGridAction::className(),
                'modelName' => CaseTypeStep::className(),
            ],

        ];
    }

    /**
     * Lists all CaseTypeStep models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new CaseTypeStep();
        if ($model->load(Yii::$app->request->post()) ) {
            $model->save();
//            // to override sortable unique order
//            $model->order=Yii::$app->request->post('CaseTypeStep')['order'];
//            $model->save();
            
           // return $this->redirect(['index']);
        }

        $model = new CaseTypeStep();
        $searchModel = new CaseTypeStepSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model'=>$model
        ]);
    }

    /**
     * Displays a single CaseTypeStep model.
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
    * Creates a new CaseTypeStep model.
    * If creation is successful, the browser will be redirected to the 'view' page.
    * @return mixed
    */
   public function actionCreate()
   {
       $model = new CaseTypeStep();

       if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $caseTypeId = Yii::$app->request->get('CaseTypeStepSearch')['case_type_id'];

            if ($model->validate()) {
                    $model->save();
                    return ['status' => 'true', 'errors' => ''];
                } else {
                    $model = ActiveForm::validate($model);
                    return ['status' => 'false', 'errors' => $errors];
                }

        
        } else {
            return $this->renderAjax('_form', [
                'model' => $model,
                'case_type_id' => $caseTypeId,
    
            ]);
        }
   }

    /**
     * Updates an existing CaseTypeStep model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        // Yii::$app->response->format = Response::FORMAT_JSON;

        if ($model->load(Yii::$app->request->post())) {
            //return $this->redirect(['view', 'id' => $model->id]);
            // return $this->redirect(['index', 'CaseTypeStepSearch[case_type_id]'=>$model->case_type_id]);
            if ($model->validate()) {
                    $model->save();
                    return $this->redirect(['index', 'CaseTypeStepSearch[case_type_id]'=>$model->case_type_id]);
                    // return ['status' => 'true', 'errors' => ''];
                } else {
                    $model = ActiveForm::validate($model);
                    // return ['status' => 'false', 'errors' => $errors];
                    return $this->render('_form', [
                        'model' => $model,
                    ]);
                }
        } else {
            return $this->render('_form', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CaseTypeStep model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {  $model= $this->findModel($id);
        $model->delete();

        return $this->redirect(['index', 'CaseTypeStepSearch[case_type_id]'=>$model->case_type_id]);
    }

    /**
     * Finds the CaseTypeStep model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CaseTypeStep the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CaseTypeStep::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
