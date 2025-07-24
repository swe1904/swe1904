<?php

namespace backend\controllers;
use Yii;

use backend\models\KnowledgeModule;
use backend\models\search\KnowledgeModuleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\CaseType;
use backend\models\search\CaseTypeSearch;

/**
 * KnowledgeModuleController implements the CRUD actions for KnowledgeModule model.
 */
class KnowledgeModuleController extends Controller
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
     * Lists all Case Types.
     *
     * @return string
     */
    public function actionIndex()
    {
        $caseTypeSearchModel = new CaseTypeSearch();
        $caseTypeDataProvider = $caseTypeSearchModel->search($this->request->queryParams);

        return $this->render('index', [
            'caseTypeSearchModel' => $caseTypeSearchModel,
            'caseTypeDataProvider' => $caseTypeDataProvider,
        ]);
    }

    /**
     * Displays a single KnowledgeModule model with their queries and notes.
     * @param int $caseTypeID ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($caseTypeID)
    {   
        $searchModel = new KnowledgeModuleSearch();
        $dataProvider = $searchModel->search(['case_type_id' => $caseTypeID]);

        $model = CaseType::findOne($caseTypeID);
        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'model' => $model
        ]);
    }

    /**
     * Creates a new KnowledgeModule model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($caseTypeID)
    {
        $model = new KnowledgeModule();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'caseTypeID' => $model->case_type_id]);
            }
        } else {
            $model->loadDefaultValues();
            $caseTypeModel = CaseType::findOne($caseTypeID);
        }
        
        return $this->render('create', [
            'model' => $model,
            'caseTypeModel' => $caseTypeModel,
        ]);
    }

    /**
     * Updates an existing KnowledgeModule model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'caseTypeID' => $model->case_type_id]);
        }

        $caseTypeModel = CaseType::findOne($model->case_type_id);

        return $this->render('update', [
            'model' => $model,
            'caseTypeModel' => $caseTypeModel,
        ]);
    }

    /**
     * Deletes an existing KnowledgeModule model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        $model->delete();

        return $this->redirect(['view', 'caseTypeID' => $model->case_type_id]);
    }


    // Views a single query for a KnowledgeModule Model
    public function actionViewQuery($id) {
        $model = $this->findModel($id);
        return $this->render('query', [
            'model' => $model
        ]);
    }

    /**
     * Finds the KnowledgeModule model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return KnowledgeModule the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KnowledgeModule::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
