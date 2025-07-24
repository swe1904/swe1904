<?php

namespace backend\controllers;

use Yii;
use backend\models\CaseType;
use backend\models\CaseTypeApplicantField;
use backend\models\Applicant;
use backend\models\search\CaseTypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use himiklab\sortablegrid\SortableGridAction;
/**
 * CaseTypeController implements the CRUD actions for CaseType model.
 */
class CaseTypeController extends Controller
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
                'modelName' => CaseType::className(),
            ],

        ];
    }
    /**
     * Lists all CaseType models.
     * @return mixed
     */
    public function actionIndex()
    {

        $model = new CaseType();
        $model_case = new CaseTypeApplicantField();
        
        
        if ($model->load(Yii::$app->request->post())) {
            $name = Yii::$app->request->post()["CaseType"]["name"];
            // $requiredFields = Yii::$app->request->post()["CaseTypeApplicantField"]["applicant_field_key"];
            // $optionalFields = Yii::$app->request->post()["CaseTypeApplicantField"]["applicant_field_value"];
            $model->name = $name;
           
            $model->save();
            $id=$model->id;

            // if (empty($requiredFields) && empty($optionalFields)) {
            //     $fields = (new Applicant)->attributeLabels();
            //     unset($fields['id']); unset($fields['client_id']);
            //     $fields = array_keys($fields);
            //     $this->saveCaseTypeFields($fields, 0, $id);
            // } else {
            //     $this->saveCaseTypeFields($requiredFields, 1, $id);
            //     $this->saveCaseTypeFields($optionalFields, 0, $id);
            // }
            
            Yii::$app->session->setFlash('success', "Case-type has been created successfully");
            // return $this->redirect(['/case-type-step/index','CaseTypeStepSearch[case_type_id]'=> $model->id]);
            return $this->redirect('index');

            
        } else {
            $searchModel = new CaseTypeSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'model' => $model,
                'model_case' => $model_case,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Displays a single CaseType model.
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
     * Creates a new CaseType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CaseType();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
        

    }

    /**
     * Updates an existing CaseType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $page)
    {
        $model = $this->findModel($id);
        // $model_case = new CaseTypeApplicantField();

        if ($model->load(Yii::$app->request->post())) {
            $model->name = Yii::$app->request->post()["CaseType"]["name"];
            $model->save();
            // $id = $model->id;
            // CaseTypeApplicantField::deleteAll(['case_type_id' => $id]);
            // $requiredFields = Yii::$app->request->post()["CaseTypeApplicantField"]["applicant_field_key"];
            // $optionalFields = Yii::$app->request->post()["CaseTypeApplicantField"]["applicant_field_value"];
           
            // $model->save();
            // $id=$model->id;
            // if (empty($requiredFields) && empty($optionalFields)) {
            //     $fields = (new Applicant)->attributeLabels();
            //     unset($fields['id']); unset($fields['client_id']);
            //     $fields = array_keys($fields);
            //     $this->saveCaseTypeFields($fields, 0, $id);
            // } else {
            //     $this->saveCaseTypeFields($requiredFields, 1, $id);
            //     $this->saveCaseTypeFields($optionalFields, 0, $id);
            // }


            Yii::$app->session->setFlash('success', "Case-type has been updated successfully");

            return $this->redirect(['index', 'page' => $page]);
        } else {
            $referralUrl = Yii::$app->request->referrer;
            return $this->render('update', [
                'model' => $model,
                // 'model_case' => $model_case,
                'referralUrl' => $referralUrl
            ]);
        }
    }

    /**
     * Deletes an existing CaseType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        // CaseTypeApplicantField::deleteAll(['case_type_id' => $id]);
        
        Yii::$app->session->setFlash('success', "Case-type has been deleted successfully");
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the CaseType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CaseType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CaseType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionMultidrop()
    {
        return $this->render('multidrop');
    }
    public function actionQuickadd()
    {
        $model = new CaseType();
        $modelCase = new CaseTypeApplicantField();

        $searchModel = new CaseTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if ((Yii::$app->request->post())) {
            $caseType= Yii::$app->request->post()["CaseType"]["name"];

            $requiredFields = Yii::$app->request->post()["CaseTypeApplicantField"]["applicant_field_key"];
            
            $optionalFields = Yii::$app->request->post()["CaseTypeApplicantField"]["applicant_field_value"];
            
            foreach($caseType as $case)
            {
               
                $modelCase::deleteAll(['case_type_id' => $case]);

                if (empty($requiredFields) && empty($optionalFields)) {
                    $fields = (new Applicant)->attributeLabels();
                    unset($fields['id']); unset($fields['client_id']);
                    $fields = array_keys($fields);
                    $this->saveCaseTypeFields($fields, 0, $case);
                } else {
                    $this->saveCaseTypeFields($requiredFields, 1, $case);
                    $this->saveCaseTypeFields($optionalFields, 0, $case);
                }
            }

            return $this->redirect(['index']);
        }
        else
        {
            return $this->render('quickadd', [
                'model' => $model,
                'modelCase' => $modelCase,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]); 
        }   
    }
    
    private static function saveCaseTypeFields($fields, $isRequired, $caseTypeID) {
        if(!empty($fields))
        {
            foreach($fields as $field) 
            {
                $row = new CaseTypeApplicantField();
                $row->case_type_id = $caseTypeID;
                $row->applicant_field_key = $field;
                $row->is_required = $isRequired;
                $row->save();                
            }
        }
    }
}
