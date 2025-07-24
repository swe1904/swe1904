<?php

namespace backend\modules\messagesystem\controllers;

use Yii;
use backend\modules\messagesystem\models\MessageInbox;
use backend\modules\messagesystem\models\search\MessageInboxSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MessageInboxController implements the CRUD actions for MessageInbox model.
 */
class MessageInboxController extends Controller
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
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create', 'update', 'delete','contact-user','view','index'],
                        'allow' => true,
                        'roles' => ['user','administrator'],
                        'denyCallback' => function () {
                            return Yii::$app->controller->redirect(['/user/default/index']);
                        }
                    ],
                ]
            ],
        ];
    }
    public function beforeAction($action) {
        $this->enableCsrfValidation = false; return parent::beforeAction($action);
    }
    /**
     * Lists all MessageInbox models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MessageInboxSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MessageInbox model.
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
     * Creates a new MessageInbox model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MessageInbox();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    public function actionContactUser(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new MessageInbox();
        $roomListingId=$_POST['roomListingId'];
        $receiverId=$_POST['receiverId'];
        $model->sender_id=Yii::$app->user->id;
        $model->receiver_id=$receiverId;
        $model->private_id=$model->returnPrivateId();
        return ['status'=>1,'html'=>$this->renderPartial('create',['model'=>$model])];
    }

    /**
     * Updates an existing MessageInbox model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MessageInbox model.
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
     * Finds the MessageInbox model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MessageInbox the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MessageInbox::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
