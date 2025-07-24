<?php

namespace backend\controllers;

use backend\models\DocumentTemplate;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl; // For RBAC
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;



class DocumentTemplateController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'update', 'view', 'delete'],
                        'roles' => ['hr_admin'], // Only users with 'hr_admin' role can access
                    ],
                    [
                        'allow' => false, // Deny all other actions for unauthenticated users
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all DocumentTemplate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => DocumentTemplate::find()->orderBy(['document_type' => SORT_ASC, 'language' => SORT_ASC, 'version' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 20, // Adjust as needed
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DocumentTemplate model.
     * @param int $id
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
     * Creates a new DocumentTemplate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DocumentTemplate();
        // Default values
        $model->version = '1.0';
        $model->is_active = 1;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                // Before saving, ensure any other active template for this type/language is deactivated
                if ($model->is_active) {
                    DocumentTemplate::updateAll(
                        ['is_active' => 0],
                        ['document_type' => $model->document_type, 'language' => $model->language, 'is_active' => 1]
                    );
                }
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Document template created successfully!');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing DocumentTemplate model.
     * When updating, a new version is created and the old one is deactivated.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $oldModel = $this->findModel($id); // This is the template being "updated"

        $newModel = new DocumentTemplate();
        // Pre-fill new model with data from the old model
        $newModel->document_type = $oldModel->document_type;
        $newModel->language = $oldModel->language;
        $newModel->content = $oldModel->content; // Copy existing content
        $newModel->version = $this->getNextVersion($oldModel->version); // Calculate next version
        $newModel->is_active = 1; // New template will be active by default

        if ($this->request->isPost) {
            if ($newModel->load($this->request->post())) {
                 // If the new template is marked active, deactivate any existing active ones
                if ($newModel->is_active) {
                    DocumentTemplate::updateAll(
                        ['is_active' => 0],
                        ['document_type' => $newModel->document_type, 'language' => $newModel->language, 'is_active' => 1]
                    );
                }
                // Also, ensure the specific old model being updated is set to inactive
                $oldModel->is_active = 0;
                $oldModel->save(false); // Save old model's status without re-validating

                if ($newModel->save()) {
                    Yii::$app->session->setFlash('success', 'Template updated and a new version created successfully!');
                    return $this->redirect(['view', 'id' => $newModel->id]);
                }
            }
        }

        return $this->render('update', [
            'model' => $newModel,
            'oldModel' => $oldModel, // Optionally pass old model for reference in view
        ]);
    }

    /**
     * Deletes an existing DocumentTemplate model.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Document template deleted successfully.');

        return $this->redirect(['index']);
    }

    /**
     * Finds the DocumentTemplate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return DocumentTemplate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DocumentTemplate::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Helper to calculate the next version number.
     * Example: '1.0' -> '1.1', '1.9' -> '2.0'
     * @param string $currentVersion
     * @return string
     */
    protected function getNextVersion($currentVersion)
    {
        $parts = explode('.', $currentVersion);
        $major = intval($parts[0]);
        $minor = isset($parts[1]) ? intval($parts[1]) : 0;

        $minor++;
        if ($minor >= 10) { // If minor goes to 10, increment major and reset minor
            $major++;
            $minor = 0;
        }
        return $major . '.' . $minor;
    }
}