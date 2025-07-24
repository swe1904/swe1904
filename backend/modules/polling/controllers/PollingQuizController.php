<?php

namespace backend\modules\polling\controllers;

use backend\modules\mii\jsonData\Applicant;
use backend\modules\polling\models\base\PollingQuizQuestion;
use backend\modules\polling\models\base\PollingQuizTeam;
use backend\modules\polling\models\PollingQuizQuestionAnswer;
use backend\modules\polling\models\search\PollingQuizQuestionSearch;
use Yii;
use backend\modules\polling\models\PollingQuiz;
use backend\modules\polling\models\search\PollingQuizSearch;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use common\components\GlobalConstants;

/**
 * PollingQuizController implements the CRUD actions for PollingQuiz model.
 */
class PollingQuizController extends Controller
{
    private $clientId=null;
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
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if ($action->id == 'quiz-question-answer') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    /**
     * Lists all PollingQuiz models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PollingQuizSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'model' => new PollingQuiz(),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PollingQuiz model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $pollingQuizQuestionSearchModel = new PollingQuizQuestionSearch();
        $pollingQuizQuestionDataProvider = $pollingQuizQuestionSearchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'pollingQuizQuestionSearchModel' => $pollingQuizQuestionSearchModel,
            'pollingQuizQuestionDataProvider' => $pollingQuizQuestionDataProvider,
        ]);
    }

    /**
     * Creates a new PollingQuiz model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PollingQuiz();
        // generate unique polling_id

        if ($model->load(Yii::$app->request->post())) {
            $pollingQuiz = Yii::$app->request->post();
            $model->user_id = Yii::$app->user->identity->id;
            // generate unique polling_id
            $model->polling_id=$this->generateUniqueId();
            if ($model->validate()) {
                // all inputs are valid
                if ($model->save()) {
                    if (isset($pollingQuiz['polling_quiz_team'])) {
                        $quizTeams = $pollingQuiz['polling_quiz_team'];
                        foreach ($quizTeams as $quizTeam) {
                            $pollingQuizTeamModel=new PollingQuizTeam();
                            $pollingQuizTeamModel->polling_quiz_id=$model->id;
                            $pollingQuizTeamModel->name=$quizTeam;
                            $pollingQuizTeamModel->save(false);
                        }
                    }

                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } else {
                // validation failed: $errors is an array containing error messages
                $errors = $model->errors;
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

        } else {
            $model->polling_id=$this->generateUniqueId();
            $model->polling_quiz_play_url=getenv('BACKEND_URL').'polling/polling-quiz/play-quiz?id='.$model->polling_id;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    private function generateUniqueId(){
        $returnPollingUniqueId=null;
        while(true){
            $unique_polling_id = mt_rand(10,9999);
            $pollingQuizModel=PollingQuiz::find()->where('polling_id=:polling_id',[':polling_id'=>$unique_polling_id])->one();
            if(empty($pollingQuizModel)){
                $returnPollingUniqueId=$unique_polling_id;
                break;
            }
        }
        return $returnPollingUniqueId;
    }

    /**
     * adding play quiz function
     * */

    public function actionPlayQuiz()
    {
        $id=-1;
        if(!empty($_GET['id'])){
            $id=$_GET['id'];
        }
        $pollingQuizModel=PollingQuiz::find()->where('polling_id=:polling_id',[':polling_id'=>$id])->one();

        $this->layout = "@backend/views/layouts/m-main";

        Yii::$app->session->set('participantId', 62);
//        return $this->render('play_quiz');

        /*
         * set url for template index file
         * */
        return $this->render('@backend/modules/polling/views/default/templates/index', ['pollingQuizModel'=>$pollingQuizModel]);

    }


    public function actionStep()
    {
        $this->layout=null;
        return $this->renderFile('@backend/modules/polling/views/default/templates/step.php');
    }

    public function actionWizard()
    {
        $this->layout=null;
        return $this->renderFile('@backend/modules/polling/views/default/templates/wizard.php');
    }
    public function actionTest(){
        echo '<pre>';
        print_r('test');
        echo '</pre>';
        die('die there');

    }
    public function actionGet(){

        header('Access-Control-Allow-Origin: *');
        Yii::$app->response->format = Response::FORMAT_JSON;
        $polling_id=-1;
        if(!empty($_GET['uuid'])){
            $polling_id=$_GET['uuid'];
        }
        $pollingQuiz= PollingQuiz::find()
            ->with(
                array(
                    'pollingQuizQuestions',
                    'pollingQuizQuestions.pollingQuizQuestionOptions',
                    'pollingQuizTeams'
                )
            )
            ->where('polling_id=:polling_id',[':polling_id'=>$polling_id])
            ->asArray()
            ->one();

        $response = [
            'success'=>true,
            'code'=>200,
            'payload'=>$pollingQuiz
        ];
        return $response;

        /*echo '<pre>';
        print_r($pollingQuiz);
        echo '</pre>';
        die('die there');*/
    }

    /**
     * Updates an existing PollingQuiz model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->polling_quiz_play_url=getenv('BACKEND_URL').'polling/polling-quiz/play-quiz?id='.$model->polling_id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $pollingQuiz = Yii::$app->request->post();
            if (isset($pollingQuiz['polling_quiz_team'])) {
                PollingQuizTeam::deleteAll(['polling_quiz_id'=>$id]);
                $quizTeams = $pollingQuiz['polling_quiz_team'];
                foreach ($quizTeams as $quizTeam) {
                    $pollingQuizTeamModel=new PollingQuizTeam();
                    $pollingQuizTeamModel->polling_quiz_id=$model->id;
                    $pollingQuizTeamModel->name=$quizTeam;
                    $pollingQuizTeamModel->save(false);
                }
            }else
                PollingQuizTeam::deleteAll(['polling_quiz_id'=>$id]);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->show_question_url_result=getenv('BACKEND_URL').'polling/show-result/index?id='.$model->polling_id;
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionQuizQuestionAnswer(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $pollingQuizTeamId=null;
        $participantId = Yii::$app->session->get("participantId");

        if(isset($_POST) && is_array($_POST)){
            if((int)$_POST['selectedTeamId']!=-1){
                $pollingQuizTeamId =$_POST['selectedTeamId'];
            }

            $answerId=[];
            $result=[];
            $questionModelArray=[];

            // check for file uploads
            if(!empty($_POST['fileData'])){
                foreach ($_POST['fileData'] as $fileData){
                    $_POST[$fileData['questionId']]=$fileData['sessionId'];
                }
            }
            // check for client id
            if(!empty($_POST['clientId'])){
                $this->clientId=$_POST['clientId'];
            }

            foreach($_POST as $key=>$value){
                if($key!='selectedTeamId' && $key!='fileData' && $key!='clientId'){
                    $pollingQuizQuestionAnswer = new PollingQuizQuestionAnswer();
                    $pollingQuizQuestionModel=PollingQuizQuestion::find()->where('id=:id',[':id'=>$key])->one();
                    if(!empty($pollingQuizQuestionModel)){
                        if($pollingQuizQuestionModel->team_based==1){
                            $pollingQuizQuestionAnswer->polling_quiz_team_id=$pollingQuizTeamId;
                        }
                    }

                    $pollingQuizQuestionAnswer->participant_id = $participantId;
                    $pollingQuizQuestionAnswer->polling_quiz_question_id = $key;
                    $pollingQuizQuestionAnswer->answer = $value;


                    if($pollingQuizQuestionAnswer->save(false)){

                        $pollingQuizQuestionModel->applicantAnswerModel=$pollingQuizQuestionAnswer;
                        array_push($questionModelArray,$pollingQuizQuestionModel);

                    }else {echo '<pre>';
                    print_r($pollingQuizQuestionAnswer->getErrors());
                    echo '<pre>';
                    die('die here');}

                }
            }

            $setApplicantData=$this->setApplicantDetails($questionModelArray);
            if($setApplicantData['message']=='success'){
                foreach ($questionModelArray as $questionModelData){
                    $pollingQuizQuestionAnswerModelNew=$questionModelData->applicantAnswerModel;
                    $pollingQuizQuestionAnswerModelNew->applicant_id=$setApplicantData['id'];
                    if($pollingQuizQuestionAnswerModelNew->save(false)){
                        $result = [
                            "code" => 200,
                            "message" => "Data Saved Successfully!",
                        ];

                    }  else   $result = [
                        "code" => 200,
                        "message" => "Error while saving Data.",
                    ];
                }
            }
            
            Yii::$app->session->remove("participantId");
        }


        return $result;
    }

    private function setApplicantDetails(array $questionModelArray){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $applicantDetails=[];
        $applicantData=Applicant::returnData();
        /* @var PollingQuizQuestion $pollingQuizQuestionModel */
        foreach ($applicantData as $applicantRow){
            foreach ($questionModelArray as $pollingQuizQuestionModel){
                if($pollingQuizQuestionModel->applicant_attribute==$applicantRow['name']){
                    $applicantDetails[str_replace('-','_',$applicantRow['name'])]= $pollingQuizQuestionModel->applicantAnswerModel->answer;
                }
            }
        }
        
        try{
            $applicantModel=\backend\models\Applicant::find()->where('email=:email',[':email'=>$applicantDetails['email']])->one();
            if(empty($applicantModel)){
                $applicantModel=new \backend\models\Applicant();
            }else{
                if(empty($applicantModel->client_id)){
                    $applicantModel->client_id=$this->clientId;
                }
                elseif(!empty($applicantModel->client_id )&&($applicantModel->client_id==$this->clientId)){
                //  update row
                }else{// create new applicant
                    $applicant=\backend\models\Applicant::find()->where(['and',['email'=>$applicantDetails['email']],['client_id' => $this->clientId]])->one();
                    if(!empty($applicant)){
                        $applicantModel =$applicant;
                    }else {
                        $applicantModel = new \backend\models\Applicant();
                    }
                }

            }
            foreach ($applicantDetails as $key=>$applicantDetail){
                $applicantModel->$key=$applicantDetail;
            }
            $applicantModel->client_id=$this->clientId;
            if($applicantModel->save(false)){
                $result = [
                    "id" => $applicantModel->getPrimaryKey(),
                    "message" => "success",
                ];  
            }
            else   $result = [
                "message" => "failure",
            ];
        }catch(Exception $exception){
            echo '<pre>';
            print_r($applicantModel->getErrors());
            print_r($exception);
            echo '<pre>';
        }
        return $result;
    }
    /**
     * Deletes an existing PollingQuiz model.
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
     * Finds the PollingQuiz model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PollingQuiz the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PollingQuiz::find()->with('pollingQuizTeams')->where('id=:id',[':id'=>$id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
