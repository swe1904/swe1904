<?php
namespace backend\controllers;

use backend\models\Employee;
use backend\models\search\TeamSearch as SearchTeamSearch;
use Yii;
use backend\models\Team;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * TeamController implements the CRUD actions for Team model.
 */
class TeamController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'update', 'view', 'delete'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    // Index method for listing teams with search and pagination
 

    public function actionIndex()
    {
        $searchModel = new SearchTeamSearch();
    
        // Get the "per-page" parameter from the GET request (default to 5)
        // $perPage = Yii::$app->request->get('per-page', 5);
    
        // Use custom search method with per-page support
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    
        // Get pagination object from data provider
        $pagination = $dataProvider->getPagination();
    
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider, // 🔁 Pass this too if you want to use it in GridView later
            'teams' => $dataProvider->getModels(),
            'pagination' => $pagination,
        ]);
    }
    

    // Create method for creating a new team
    public function actionCreate()
    {
        $model = new Team();

        if ($model->load(Yii::$app->request->post())) {
            // Duplicate check before save
            if (Team::find()->where(['name' => $model->name])->exists()) {
                Yii::$app->session->setFlash('error', 'Team with this name already exists.');
                return $this->refresh();
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Team created successfully.');
                return $this->redirect(['index']);
            }
        }
        $managers = Employee::find()->all(); // or whatever model you use for managers
        $parentTeams = Team::find()->all();
        
        return $this->render('create', [
            'model' => $model,
            'managers' => $managers,
            'parentTeams' => $parentTeams,
        ]);
       
    }

    // Update method for updating team information
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            // Duplicate check before save
            if (Team::find()->where(['name' => $model->name])->andWhere(['!=', 'id', $model->id])->exists()) {
                Yii::$app->session->setFlash('error', 'Team with this name already exists.');
                return $this->refresh();
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Team updated successfully.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    // View method for displaying team details
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    // Delete method for deleting a team
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Team deleted successfully.');
        return $this->redirect(['index']);
    }

    // Find model by ID (helper function)
    protected function findModel($id)
    {
        if (($model = Team::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested team does not exist.');
        }
    }
}
