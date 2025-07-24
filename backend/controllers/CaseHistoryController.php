<?php

namespace backend\controllers;

use backend\models\CaseSteps;
use Yii;
use backend\models\CaseHistory;
use backend\models\Cases;
use backend\models\search\CaseHistorySearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CaseHistoryController implements the CRUD actions for CaseHistory model.
 */
class CaseHistoryController extends Controller
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
     * Lists all CaseHistory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CaseHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        // $e=CaseHistory::find()->where('case_id',Cases::find()->where('id',15))->one();
       // $d=Cases::find()->select('case_number')->where("id,CaseHistory::find()->select('case_id')->one()['case_id']");
                // $e=CaseHistory::find()->select('case_id')->one()['case_id'];
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

        ]);


    }


    /**
     * Displays a single CaseHistory model.
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
     * Creates a new CaseHistory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CaseHistory();
        $case_his_step=CaseSteps::find()->select('case_type_step_id')->where(['case_id'=>15])->orderBy(['id'=>'desc'])->limit(1);
        //map helper
        $data = ArrayHelper::map(Cases::find()->all(), 'id', 'case_number');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'data' => $data,
                'case' => $case_his_step->all()[0] ["case_type_step_id"]
            ]);


//var_dump($case_his_step->all()[0] ["case_type_step_id"]);
        }
    }

    /**
     * Updates an existing CaseHistory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $caseHis=CaseHistory::find()->select('case_id')->where(['id'=>$id])->one()['case_id'];
        $case=Cases::find()->select('id')->where(['id'=>$caseHis])->one()['id'];
        $data = ArrayHelper::map(Cases::find()->all(), 'id', 'case_number');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'data' => $data,

            ]);

        }
    }

    /**
     * Deletes an existing CaseHistory model.
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
     * Finds the CaseHistory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CaseHistory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CaseHistory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
