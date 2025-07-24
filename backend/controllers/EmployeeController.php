<?php

namespace backend\controllers;

use backend\models\Department;
use Yii;
use backend\models\Employee;
use backend\models\search\EmployeeSearch;
use yii\web\Controller;
use backend\models\Organisation;
use backend\models\UserForm;
use common\models\User;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;
/**
 * EmployeeController implements the CRUD actions for Employee model.
 */
class EmployeeController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Employee models.
     * @return mixed
     */
    // public function actionIndex()
    // {
    //     // Create the search model
    //     $searchModel = new EmployeeSearch();
    
    //     // Get the organization ID of the logged-in user (assuming it's stored in the identity)
    //     $organizationId = Yii::$app->user->identity->organisation_id;  // Adjust this as per your user identity structure
    
    //     // Get the search parameters from the GET request
    //     $params = Yii::$app->request->get();
    
    //     // Add organization_id condition to the search method
    //     $params['EmployeeSearch']['organisation_id'] = $organizationId; // Add organization_id filter
    
    //     // Call the search method with the parameters (including organization_id)
    //     $dataProvider = $searchModel->search($params);
    
    //     // Render the index view with the search form and data provider
    //     return $this->render('index', [
    //         'searchModel' => $searchModel,
    //         'dataProvider' => $dataProvider,
    //     ]);
    // }
    
public function actionIndex()
{
    $searchModel = new EmployeeSearch();
    $params = Yii::$app->request->get();

    if (!isset($params['EmployeeSearch'])) {
        $params['EmployeeSearch'] = [];
    }

    // Comment this line if you want full dropdown to appear:
    // $params['EmployeeSearch']['organisation_id'] = Yii::$app->user->identity->organisation_id;

    $dataProvider = $searchModel->search($params);

    return $this->render('index', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]);
}


    /**
     * Displays a single Employee model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
public function actionProfile()
{
    // Get the logged-in user ID
    $userId = Yii::$app->user->id;

    // Fetch the employee record linked to this user
    $model = Employee::findOne(['user_id' => $userId]);

    if (!$model) {
        throw new NotFoundHttpException('Employee profile not found.');
    }

    // Save profile changes if form is submitted
    if ($model->load(Yii::$app->request->post()) && $model->save()) {
        Yii::$app->session->setFlash('success', 'Profile updated successfully.');
        return $this->redirect(['profile']);
    }

    // Render the profile update form
    return $this->render('profile', [
        'model' => $model,
    ]);
}

  
    
    public function actionAaa()
{
    Yii::$app->response->format = Response::FORMAT_JSON;
    try {
        $deptId = Yii::$app->request->post('dept_id');
        // process and return response
    } catch (\Exception $e) {
        Yii::error("Error in actionAaa: " . $e->getMessage(), __METHOD__);
        return ['error' => 'Internal Server Error'];
    }
}
// public function actionGetTeamsByDepartment()
// {
//     Yii::$app->response->format = Response::FORMAT_JSON;

//     ini_set('display_errors', 1);
//     error_reporting(E_ALL);
//     // print_r($_SESSION);
//     try {
//         $deptId = Yii::$app->request->post('dept_id');

//         // Just for debugging
//         return ['received' => $deptId];

//         // Once confirmed, add your DB logic here

//     } catch (\Exception $e) {
//         Yii::error("Error in actionGetTeamsByDepartment: " . $e->getMessage(), __METHOD__);
//         return ['error' => $e->getMessage()];
//     }
// }

public function actionGetTeamsByDepartment()
{
    Yii::$app->response->format = Response::FORMAT_JSON;

    try {
        $deptId = Yii::$app->request->post('dept_id');

        if (!$deptId) {
            return ['error' => 'Department ID is required.'];
        }

        // Adjust model name to your actual Department model
        $department = \backend\models\Department::findOne($deptId);

        if (!$department) {
            return ['error' => 'Department not found.'];
        }

        // Assuming manager_id is a foreign key to User or Employee table
        $manager = $department->manager; // if relation is defined as getManager()

        if (!$manager) {
            return ['error' => 'Manager not found for this department.'];
        }

        return [
            'department_id' => $deptId,
            'manager_id' => $manager->id,
            'manager_name' => $manager->username, // adjust fields
            'manager_email' => $manager->email, // if needed
        ];

    } catch (\Exception $e) {
        Yii::error("Error in actionGetTeamsByDepartment: " . $e->getMessage(), __METHOD__);
        return ['error' => $e->getMessage()];
    }
}

// public function actionGetTeamsByDepartment()
// {
//     Yii::$app->response->format = Response::FORMAT_JSON;

//     $deptId = Yii::$app->request->post('dept_id');

//     return ['received' => $deptId];  // No RBAC/permission check
// }

    /**
     * Creates a new Employee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // echo 'asdfs';die;
        $model = new Employee();
    
        // Get the organization ID for the logged-in user
        $organizationId = Yii::$app->user->identity->organisation_id;
    
        if ($organizationId) {
            $model->organisation_id = $organizationId;
    
            // Generate Employee ID before rendering the form
            $model->employee_id = $this->generateEmployeeId($organizationId);
        } else {
            Yii::$app->session->setFlash('error', 'Organisation not found for the user.');
            return $this->redirect(['index']);
        }
    
        if ($model->load(Yii::$app->request->post())) {

            // Handle file uploads
            $model->passport_copy = UploadedFile::getInstance($model, 'passport_copy');
            $model->id_card_copy = UploadedFile::getInstance($model, 'id_card_copy');
            $model->educational_document_1 = UploadedFile::getInstance($model, 'educational_document_1');
            $model->educational_document_2 = UploadedFile::getInstance($model, 'educational_document_2');
            $model->resume = UploadedFile::getInstance($model, 'resume');
        
            // Save employee (ONLY ONCE)
            if ($model->save(false)) {  // Use false to bypass validation since data is already validated
        
                // Save uploaded files
                $this->saveUploadedFiles($model);
        
                Yii::$app->session->setFlash('success', 'Employee record created successfully.');
        
                return $this->redirect(['index', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to save employee.');
            }
        }
        
        // Render the form with the generated Employee ID
        return $this->render('create', [
            'model' => $model,
        ]);
    }
    
    /**
     * Save uploaded files helper function
     */
    private function saveUploadedFiles($model)
    {
        if ($model->passport_copy) {
            $model->passport_copy->saveAs('uploads/passports/' . $model->passport_copy->baseName . '.' . $model->passport_copy->extension);
        }
        if ($model->id_card_copy) {
            $model->id_card_copy->saveAs('uploads/id_cards/' . $model->id_card_copy->baseName . '.' . $model->id_card_copy->extension);
        }
        if ($model->educational_document_1) {
            $model->educational_document_1->saveAs('uploads/education/' . $model->educational_document_1->baseName . '.' . $model->educational_document_1->extension);
        }
        if ($model->educational_document_2) {
            $model->educational_document_2->saveAs('uploads/education/' . $model->educational_document_2->baseName . '.' . $model->educational_document_2->extension);
        }
        if ($model->resume) {
            $model->resume->saveAs('uploads/resumes/' . $model->resume->baseName . '.' . $model->resume->extension);
        }
    }
    


    // Function to Generate Employee ID
    private function generateEmployeeId($organisationId)
    {
        // Fetch Organization details including the code
        $organisation = Organisation::findOne($organisationId);
        
        if (!$organisation) {
            // Handle case where organization is not found
            return 'ORG0001'; // Default or fallback value
        }
        
        // Retrieve the organization code (com_code)
        $orgCode = $organisation->receipt_increment_alpahabetic_part ?: 'ORG'; // Default to 'ORG' if com_code is not set

        // Get the last employee record across all organizations (not just the given organization)
        $lastEmployee = Employee::find()
            ->orderBy(['id' => SORT_DESC])
            ->one();

        // Extract last sequence number from the last employee ID
        $lastNumber = 0;
        if ($lastEmployee && preg_match('/(\d+)$/', $lastEmployee->employee_id, $matches)) {
            $lastNumber = (int)$matches[1];  // Extract the last number
        }

        // Generate the new Employee ID with the organization code and the incremented number
        return $orgCode . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }


public function actionGetDepartmentManager() {

   print_r($_REQUEST);DIE;
}



public function actionTest()
{
    return 'route works';
}
    /**
     * Updates an existing Employee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Employee model.
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
     * Finds the Employee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Employee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Employee::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionAjaxData()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    
        $searchModel = new \backend\models\search\EmployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    
        $employees = $dataProvider->getModels();
        $totalCount = $dataProvider->getTotalCount();
    
        if (empty($employees)) {
            return [
                'draw' => intval(Yii::$app->request->post('draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
            ];
        }
    
        $data = [];
        foreach ($employees as $employee) {
            $data[] = [
                'id' => $employee->id,
                'first_name' => $employee->first_name,
                'employee_id' => $employee->employee_id,
                'address' => $employee->address,
                'position' => $employee->position,
                'date_of_joining' => Yii::$app->formatter->asDate($employee->date_of_joining, 'php:Y-m-d'),
            ];
        }
    
        return [
            'draw' => intval(Yii::$app->request->post('draw')),
            'recordsTotal' => $totalCount,
            'recordsFiltered' => $totalCount,
            'data' => $data,
        ];
    }

    
    

    

}
