<?php

namespace backend\controllers;

use app\components\GlobalConstant;
use Yii;
use backend\models\DocumentRequest;
use backend\models\DocumentTemplates;
use backend\models\Employee;
use backend\models\search\DocumentRequestSearch;
use backend\models\UserForm;
use yii\filters\AccessControl; 
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;
use common\models\User;
class DocumentRequestController extends Controller
{
    // public function behaviors()
    // {
    //     return [
    //         'verbs' => [
    //             'class' => VerbFilter::class,
    //             'actions' => [
    //                 'delete' => ['POST'],
    //             ],
    //         ],
    //     ];
    // }
   public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        // Allow 'index', 'create', 'view', 'preview', 'generate', and the new 'get-preview-content'
                        'actions' => ['index', 'create', 'view', 'preview', 'generate-document', 'generate', 'get-preview-content'],
                        'allow' => true,
                        // Make sure the relevant roles (e.g., GlobalConstant::ROLE_EMPLOYEE) are here
                        'roles' => ['@', GlobalConstant::ROLE_ORGANISATION_ADMIN, GlobalConstant::ROLE_EMPLOYEE],
                    ],
                    // Other rules for 'update', 'delete' etc.
                    [
                        'actions' => ['update', 'delete'],
                        'allow' => true,
                        'roles' => [
                            GlobalConstant::ROLE_ORGANISATION_ADMIN,
                            GlobalConstant::ROLE_SUPERVISOR,
                            GlobalConstant::ROLE_COUNTRY_MANAGER,
                            GlobalConstant::ROLE_PAYROLL_MANAGER,
                            GlobalConstant::ROLE_HR_MANAGER
                        ],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['*'],
                    ],
                ],
            ],
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
        $searchModel = new DocumentRequestSearch();
        $perPage = Yii::$app->request->get('per-page', 10);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $perPage);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new DocumentRequest();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Document request submitted successfully.');
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Document request updated successfully.');
            return $this->redirect(['index']);
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Document request deleted successfully.');
        return $this->redirect(['index']);
    }


    public function actionPreview($id)
    {
        // Find the DocumentRequest model by ID
        $model = DocumentRequest::findOne($id);
        if (!$model) {
            throw new \yii\web\NotFoundHttpException("DocumentRequest not found.");
        }
        Yii::error('DocumentRequest model: ' . print_r($model, true));  // Debugging
    
        $employee = User::findOne($model->employee_id);

        if (!$employee) {
            throw new \yii\web\NotFoundHttpException("Employee not found.".$model->employee_id);
        }
        Yii::error('Employee model: ' . print_r($employee, true));  // Debugging
    
        // Fetch the DocumentTemplate based on the document type and language
        $template = DocumentTemplates::find()
            ->where([
                'document_type' => $model->document_type,
                'language' => $model->language_of_document
            ])
            ->one();
    
        if (!$template) {
            throw new \yii\web\NotFoundHttpException("Template not found.");
        }
        Yii::error('Document template: ' . print_r($template, true));  // Debugging
    
        // Define the replacements for the placeholders in the template
        $replacements = [
            '{{employee_name}}' => $employee->fullname,
        ];
    
        // Perform the replacement on the template content
        $rendered = strtr($template->content, $replacements);
    
        Yii::error('Rendered content: ' . $rendered);  // Debugging
    
        // Return the rendered preview view
        return $this->render('preview', [
            'rendered' => $rendered,
            'model' => $model
        ]);
    }
    

    public function actionGenerateDocument($employeeId, $document_type, $language)
    {
    $template = DocumentTemplates::findOne([
        'document_type' => $document_type,
        'language' => $language
    ]);

    $employee = Employee::findOne($employeeId);

    if (!$template || !$employee) {
        throw new \yii\web\NotFoundHttpException('Template or Employee not found.');
    }

    // Replace placeholders with actual data
    $content = str_replace(
        ['{{employee_name}}', '{{position}}'],
        [$employee->full_name, $employee->position],
        $template->content
    );

    return $this->render('preview', ['content' => $content]);
    }

    public function beforeAction($action)
    {
        if (!in_array(Yii::$app->user->identity->getRole(), [
            GlobalConstant::ROLE_ORGANISATION_ADMIN,
            GlobalConstant::ROLE_SUPERVISOR,
            GlobalConstant::ROLE_COUNTRY_MANAGER,
            GlobalConstant::ROLE_PAYROLL_MANAGER,
            // GlobalConstant::ROLE_EMPLOYEE,
            GlobalConstant::ROLE_HR_MANAGER
        ])) {
            throw new \yii\web\ForbiddenHttpException('You are not allowed to access this page.');
        }
    
        return parent::beforeAction($action);
    }

    public function actionGenerate($id)
    {
        $model = DocumentRequest::findOne($id);
        $employee = Employee::findOne($model->employee_id);
    
        $template = DocumentTemplates::find()
            ->where([
                'document_type' => $model->document_type,
                'language' => $model->language_of_document
            ])
            ->one();
    
        if (!$template) {
            throw new \yii\web\NotFoundHttpException("Template not found.");
        }
    
        $replacements = [
            '{{employee_name}}' => $employee->name,
            '{{employee_id}}' => $employee->user_id,
            '{{designation}}' => $employee->designation,
            '{{join_date}}' => date('F Y', strtotime($employee->join_date)),
        ];
    
        $html = strtr($template->content, $replacements);
    
        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);
        $mpdf->Output('Certificate_' . $model->id . '.pdf', \Mpdf\Output\Destination::INLINE);
    }
    
     public function actionDownload($type, $lang)
    {
        // Get the processed HTML content for the certificate
        $htmlContent = $this->generateCertificateContent($type, $lang);

        if ($htmlContent === null) {
            throw new NotFoundHttpException('The requested document template or employee data could not be found for download.');
        }

        // --- DIRECT Mpdf\Mpdf INSTANTIATION ---
        $mpdf = new Mpdf([
            'format' => 'A4',
            'orientation' => 'P', // 'P' for Portrait, 'L' for Landscape
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_left' => 10,
            'margin_right' => 10,
            'default_font_size' => 10, // Recommended to set a default font size
            'default_font' => 'dejavusans', // Recommended for UTF-8 support (e.g., for multi-language)
        ]);

        // Optional: Add a header
        $mpdf->SetHTMLHeader('
            <div style="text-align: right; font-size: 8pt; color: #555;">Generated by ' . (Yii::$app->params['companyName'] ?? 'Your Company') . ' HR</div>
            <hr>
        ');

        // Optional: Add a footer
        $mpdf->SetHTMLFooter('
            <div style="text-align: center; font-size: 8pt; color: #555;">Page {PAGENO} of {nbpg}</div>
            <hr>
        ');

        // Write the HTML content to the PDF
        $mpdf->WriteHTML($htmlContent);

        // Output the PDF for download
        $filename = Yii::$app->user->identity->username . '_' . str_replace(' ', '_', $type) . '_' . $lang . '_Certificate.pdf';
        $mpdf->Output($filename, \Mpdf\Output\Destination::DOWNLOAD);

        // Important: Exit to prevent Yii from rendering anything else
        Yii::$app->end();
    }

    // ... (Your existing generateCertificateContent helper method and other actions) ...

   
    // ... (your existing actionIndex, actionCreate, etc. methods) ...

    /**
     * Returns the rendered HTML content of a document template for AJAX preview.
     * This action will be called via AJAX from the 'create' form.
     * @param string $document_type
     * @param string $language
     * @return string Raw HTML content
     * @throws NotFoundHttpException if template or employee not found
     */
    public function actionGetPreviewContent($document_type = null, $language = null)
    {
        // Important: Disable layout for AJAX requests
        $this->layout = false;
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW; // Ensure raw output

        if (empty($document_type) || empty($language)) {
            return ''; // Or throw an error, depending on desired behavior
        }

        // Fetch the DocumentTemplate based on the document type and language
        $template = DocumentTemplates::find()
            ->where([
                'document_type' => $document_type,
                'language' => $language
            ])
            ->one();

        if (!$template) {
            // Consider logging this or returning a user-friendly message
            // return 'Template not found for selected type and language.';
            throw new NotFoundHttpException("Template not found for type: {$document_type}, language: {$language}");
        }

        // Get the current logged-in employee (assuming the request is made by an employee)
        // You might need to adjust how you get employee data based on your User/Employee models.
        // Assuming current user is an employee or linked to an employee record.
        $employee = null;
        if (Yii::$app->user->isGuest) {
            // Handle guest users if necessary, perhaps return empty string or login prompt
            return '';
        }

        // If your User model has direct employee relation, use that
        // OR fetch Employee based on a linked ID in User::identity
        // For example:
        // $employee = Employee::findOne(['user_id' => Yii::$app->user->id]);
        // For now, let's just use some dummy data or assume employee details are always available.
        // A more robust solution would fetch the actual employee details linked to the current user.
        $user = Yii::$app->user->identity;
        // Placeholder for employee data. Replace with actual fetching from Employee model if needed.
        $employeeName = 'Employee Name Placeholder'; // Replace with actual employee->fullname or similar
        // If your User model or linked Employee model has a 'full_name' or 'name' attribute
        // $employee = Employee::findOne(['user_id' => $user->id]); // or similar logic
        // if ($employee) {
        //    $employeeName = $employee->full_name;
        // }


        // --- IMPORTANT: Adapt placeholder replacements based on your actual template placeholders ---
        // Example: If your template has {{employee_name}}, {{employee_id}}, {{designation}}, {{join_date}}
        // You'll need to fetch these from your Employee model linked to the current user.

        $currentEmployee = null; // Initialize
        $currentUserId = Yii::$app->user->id; // Get the ID of the logged-in user

        // Assuming your User model has a relation to an Employee model, or direct fields
        // Adapt this logic to how your user is linked to an employee.
        // Option A: User model itself has employee details (less common for separate Employee model)
        // $employeeName = $user->fullname ?? 'N/A';
        // $employeeId = $user->employee_id ?? 'N/A';
        // $designation = $user->designation ?? 'N/A';
        // $joinDate = $user->join_date ? date('F Y', strtotime($user->join_date)) : 'N/A';

        // Option B: Fetch Employee model based on user ID or some other link
        $currentEmployee = Employee::findOne(['user_id' => $currentUserId]); // Adjust if 'user_id' is not the column
        if ($currentEmployee) {
            $employeeName = $currentEmployee->full_name ?? $currentEmployee->name ?? 'N/A'; // Use appropriate attribute
            $employeeId = $currentEmployee->id ?? 'N/A';
            $designation = $currentEmployee->designation ?? 'N/A';
            $joinDate = $currentEmployee->date_of_joining ? date('F Y', strtotime($currentEmployee->date_of_joining)) : 'N/A';
        } else {
            // Fallback if employee record not found for the user
            $employeeName = 'N/A';
            $employeeId = 'N/A';
            $designation = 'N/A';
            $joinDate = 'N/A';
        }


        $replacements = [
            '{{employee_name}}' => $employeeName,
            '{{employee_id}}' => $employeeId,
            '{{designation}}' => $designation,
            '{{join_date}}' => $joinDate,
            // Add any other placeholders your templates might have
            // e.g., '{{current_date}}' => date('F j, Y'),
            // '{{company_name}}' => Yii::$app->params['companyName'], // If you store company name in params
        ];

        // Perform the replacement on the template content
        $renderedContent = strtr($template->content, $replacements);

        return $renderedContent; // Return raw HTML
    }

    // ... (rest of your controller methods) ...
}
