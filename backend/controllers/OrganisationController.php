<?php

namespace backend\controllers;

use app\components\GlobalConstant;
use backend\models\Currency;
use backend\models\Service;
use backend\models\ServiceSearch;
use common\models\User;
use Intervention\Image\ImageManagerStatic;
use trntv\filekit\actions\DeleteAction;
use trntv\filekit\actions\UploadAction;
use Yii;
use common\models\Organisation;
use backend\models\OrganisationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrganisationController implements the CRUD actions for Organisation model.
 */
class OrganisationController extends CustomBaseController
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
            'avatar-upload' => [
                'class' => UploadAction::className(),
                'deleteRoute' => 'avatar-delete',
                'on afterSave' => function ($event) {
                    /* @var $file \League\Flysystem\File */
                    $file = $event->file;
                    //$img = ImageManagerStatic::make($file->read())->fit(215, 215);
                    $img = ImageManagerStatic::make($file->read());
                    $file->put($img->encode());

                    //$file->put($img->encode());
                }
            ],
            'avatar-delete' => [
                'class' => DeleteAction::className()
            ]
        ];
    }
    /**
     * Lists all Organisation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new \backend\models\search\OrganisationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Organisation model.
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
     * Creates a new Organisation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN)){
            throw new NotFoundHttpException('Only organisation admin can create organisation.');
        }
        $organisation=Organisation::find()->where(['user_id'=>Yii::$app->user->id])->one();
         // if already have organisation
        if(!empty($organisation)){
            return $this->redirect(['update', 'id' => $organisation->id]);

        }
        if(Yii::$app->getResponse()->getStatusCode() == 302) {
            Yii::$app->session->setFlash('warning', "Please fill out organisation details before updating other sections.");
        }

        $searchModel = new \backend\models\search\ServiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        /*fetch all currency*/
        $currencyModel = Currency::find()->select(['id','iso'])->all();
        $currencyArray = array();
        foreach($currencyModel as $key=>$value){
            $currencyArray[$value['id']] = $value['iso'];
        }
        $model = new \backend\models\Organisation();

        if ($model->load(Yii::$app->request->post())) {
            //$model->image = \yii\web\UploadedFile::getInstance($model, 'image');
            $model->user_id = Yii::$app->user->identity->id;
            
            if ($model->save(false)) {
                if (!empty($_POST['Organisation']['picture'])) {
                    $path=$_POST['Organisation']['picture']['path'];
                    $base_url=$_POST['Organisation']['picture']['base_url'];
                    $model->avatar_base_url=$base_url;
                    $model->avatar_path=$path;
                    $model->save(false);
                }
                $user = User::findOne(Yii::$app->user->id);
                if (!empty($user)) {
                    $user->updateAttributes(['organisation_id' => $model->id]);
                }
            }
            Yii::$app->session->setFlash('success', "Organisation Created.");
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'currencyArray'=>$currencyArray,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,

            ]);
        }
    }
   /*Add new service */
    public function actionAddService(){
       if(!empty($_POST['serviceVal'])){
           $model = new Service();
           $model->user_id = Yii::$app->user->identity->id;
           $model->name = $_POST['serviceVal'];
           if($model->save()){
               echo '1';
           }else{
               echo '2';
           }
       }
    }

    /*Delete existing service*/
    public function actionDeleteService(){
        $connection = Yii::$app->db;
        $connection	->createCommand('DELETE FROM tbl_service WHERE id='.$_POST['id'].'')->execute();
    }

    /**
     * Updates an existing Organisation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $searchModel = new \backend\models\search\ServiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        /*fetch all currency*/
        $currencyModel = Currency::find()->select(['id','iso'])->all();
        $currencyArray = array();
        if(!empty($currencyModel)){
            foreach($currencyModel as $key=>$value){
                $currencyArray[$value['id']] = $value['iso'];
            }
        }
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->save(false);
            if (!empty($_POST['Organisation']['picture'])) {
                /*start save crop images data*/
               // \backend\models\Organisation::cropImageData($model);
                /*end save crop images*/
                $path=$_POST['Organisation']['picture']['path'];
                $base_url=$_POST['Organisation']['picture']['base_url'];
                $model->avatar_base_url=$base_url;
                $model->avatar_path=$path;
                $model->save(false);
            }
            
            else
            {
                $model->avatar_base_url="";
                $model->avatar_path="";
                $model->save(false);
            }
            Yii::$app->session->setFlash('success', "Organisation Updated.");
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'currencyArray'=>$currencyArray,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Deletes an existing Organisation model.
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
     * Finds the Organisation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Organisation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Organisation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionServiceUpdate($id)
    {
        if(isset($_GET['org_id'])){
            $modelId = $_GET['org_id'];
        }else{
            $modelId = '0';
        }
        $model = Service::findOne($id);
        return $this->renderPartial('_serviceUpdate', array(
            'model' => $model,
            'org_id'=>$modelId
        ));
    }
    public function actionServiceUpdateModel()
    {
        if($_POST){
            $connection = Yii::$app->db;
            $sql =  'UPDATE tbl_service SET name= "'.$_POST['Service']['name'].'" , user_id = '.Yii::$app->user->identity->id.'  WHERE id='.$_POST['Service']['id'].'';
            $command = $connection->createCommand($sql);
            $command->execute();
            if($_POST['org_id']==''){
                return $this->redirect(['organisation/create']);
            }else{
                return $this->redirect(['organisation/update','id'=>$_POST['org_id']]);
            }
        }
    }
}
