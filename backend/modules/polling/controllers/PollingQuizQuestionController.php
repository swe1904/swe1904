<?php

namespace backend\modules\polling\controllers;

use backend\modules\mii\jsonData\Applicant;
use backend\modules\polling\models\base\PollingQuizQuestionCorrectAnswer;
use backend\modules\polling\models\PollingQuizQuestionOption;
use Yii;
use backend\modules\polling\models\PollingQuizQuestion;
use backend\modules\polling\models\search\base\PollingQuizQuestionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PollingQuizQuestionController implements the CRUD actions for PollingQuizQuestion model.
 */
class PollingQuizQuestionController extends Controller
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
     * Lists all PollingQuizQuestion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PollingQuizQuestionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PollingQuizQuestion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    private function generateUniqueId(){
        $returnPollingUniqueId=null;
        while(true){
            $unique_polling_id = mt_rand(10,9999);
            $pollingQuizQuestionModel=PollingQuizQuestion::find()->where('polling_quiz_question_direct_id=:polling_quiz_question_direct_id',[':polling_quiz_question_direct_id'=>$unique_polling_id])->one();
            if(empty($pollingQuizModel)){
                $returnPollingUniqueId=$unique_polling_id;
                break;
            }
        }
        return $returnPollingUniqueId;
    }
    /**
     * Creates a new PollingQuizQuestion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionCreate($pqi = null)
    {
        $model = new PollingQuizQuestion();

        if ($model->load(Yii::$app->request->post())) {
            $quizQuestionForm = Yii::$app->request->post();
            $model->polling_quiz_id = $pqi;
            // set is_correct rating only for rating type question
            if($model->type != PollingQuizQuestion::RATING){
                $model->is_correct=0;
            }

      //      if(isset($_POST['PollingQuizQuestion']['required']))
       //         $model->required = $_POST['PollingQuizQuestion']['required'];

            // add required field
            $model->required = 0;
            $applicantData=Applicant::returnData();
            foreach ($applicantData as $attribute) {
                if (($attribute['label'] == $model->applicant_attribute) && !empty($attribute['required'])&& ($attribute['required']== 1)) {
                    $model->required = 1;
                }
            }
            if ($model->save()) {
                /*save question option value in tbl_polling_quiz_question_option table */
                if ($model->type == PollingQuizQuestion::MULTIPLE_CHOICE_QUESTION) {
                    if (isset($quizQuestionForm['question_type_option'])) {
                        $questionType = $quizQuestionForm['question_type_option'];

                        foreach ($questionType as $key=>$type) {
                            $PollingQuizQuestionOption = new PollingQuizQuestionOption();
                            $PollingQuizQuestionOption->polling_quiz_question_id = $model->id;
                            $PollingQuizQuestionOption->value = $type;
                            if($PollingQuizQuestionOption->save()){
                                if(isset($quizQuestionForm['question_option_correct'])){
                                    $questionTypeOption=$quizQuestionForm['question_option_correct'];
                                    if($key==$questionTypeOption){
                                        // save correct answer
                                        $PollingQuizQuestionCorrectAnswer = new PollingQuizQuestionCorrectAnswer();
                                        $PollingQuizQuestionCorrectAnswer->polling_quiz_question_id = $model->id;
                                        $PollingQuizQuestionCorrectAnswer->answer = $PollingQuizQuestionOption->id;
                                        $PollingQuizQuestionCorrectAnswer->save(false);
                                    }

                                }
                            }
                        }
                    }
                }
                if ($model->type == PollingQuizQuestion::MULTIPLE_RESPONSE) {
                    if (isset($quizQuestionForm['question_type_response'])) {
                        $questionType = $quizQuestionForm['question_type_response'];
                        $correct_response_id_string_array=[];
                        foreach ($questionType as $key=>$type) {
                            $PollingQuizQuestionOption = new PollingQuizQuestionOption();
                            $PollingQuizQuestionOption->polling_quiz_question_id = $model->id;
                            $PollingQuizQuestionOption->value = $type;
                            if($PollingQuizQuestionOption->save()){
                                if (isset($quizQuestionForm['question_response_correct'])) {
                                    $questionTypeResponseCorrect = $quizQuestionForm['question_response_correct'];
                                    foreach ($questionTypeResponseCorrect as $index_value) {
                                              if($key==$index_value){
                                                   array_push($correct_response_id_string_array,$PollingQuizQuestionOption->id);
                                              }
                                    }

                                }
                            }
                        }
                        if(count($correct_response_id_string_array)>0){
                            $answer=implode(',',$correct_response_id_string_array);
                            $PollingQuizQuestionCorrectAnswer = new PollingQuizQuestionCorrectAnswer();
                            $PollingQuizQuestionCorrectAnswer->polling_quiz_question_id = $model->id;
                            $PollingQuizQuestionCorrectAnswer->answer = $answer;
                            $PollingQuizQuestionCorrectAnswer->save(false);
                        }
                    }
                }
                if ($model->type == PollingQuizQuestion::RATING) {
                    if (isset($quizQuestionForm['question_type_rating'])) {
                        $questionType = $quizQuestionForm['question_type_rating'];
                        $PollingQuizQuestionOption = new PollingQuizQuestionOption();
                        $PollingQuizQuestionOption->polling_quiz_question_id = $model->id;
                        $PollingQuizQuestionOption->value = $questionType;
                        if ($PollingQuizQuestionOption->save()) {
                            if (isset($quizQuestionForm['question_type_rating_correct'])) {
                                $questionTypeAnswer = $quizQuestionForm['question_type_rating_correct'];
                                $PollingQuizQuestionCorrectAnswer = new PollingQuizQuestionCorrectAnswer();
                                $PollingQuizQuestionCorrectAnswer->polling_quiz_question_id = $model->id;
                                $PollingQuizQuestionCorrectAnswer->answer = $questionTypeAnswer;
                                $PollingQuizQuestionCorrectAnswer->save();
                            }
                        }
                    }
                }
                if ($model->type == PollingQuizQuestion::TRUE_FALSE) {
                    if (isset($quizQuestionForm['question_type_tf_correct'])) {
                        $questionType = $quizQuestionForm['question_type_tf_correct'];
                        $PollingQuizQuestionCorrectAnswer = new PollingQuizQuestionCorrectAnswer();
                        $PollingQuizQuestionCorrectAnswer->polling_quiz_question_id = $model->id;
                        $PollingQuizQuestionCorrectAnswer->answer = $questionType;
                        $PollingQuizQuestionCorrectAnswer->save(false);
                    }
                }
                if ($model->type == PollingQuizQuestion::NUMBER) {
                    if (isset($quizQuestionForm['question_type_number'])) {
                        $questionTypeNumber = $quizQuestionForm['question_type_number'];
                        $PollingQuizQuestionCorrectAnswer = new PollingQuizQuestionCorrectAnswer();
                        $PollingQuizQuestionCorrectAnswer->polling_quiz_question_id = $model->id;
                        $PollingQuizQuestionCorrectAnswer->answer = $questionTypeNumber;
                        $PollingQuizQuestionCorrectAnswer->save(false);
                    }
                }


                return $this->redirect(['polling-quiz/view', 'id' => $pqi]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            $model->polling_quiz_question_direct_id=$this->generateUniqueId();
            $model->show_question_url_result=getenv('BACKEND_URL').'polling/show-result/index?id='.$pqi."&question_id=".$model->polling_quiz_question_direct_id;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PollingQuizQuestion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            // set is_correct rating only for rating type question
            if($model->type != PollingQuizQuestion::RATING){
                $model->is_correct=0;
            }

            $quizQuestionForm = Yii::$app->request->post();

          //  if(isset($_POST['PollingQuizQuestion']['required']))
           //     $model->required = $_POST['PollingQuizQuestion']['required'];

        // update required field
            $model->required = 0;
            $applicantData=Applicant::returnData();
            foreach ($applicantData as $attribute) {
                if (($attribute['label'] == $model->applicant_attribute) && !empty($attribute['required'])&& ($attribute['required']== 1)) {
                    $model->required = 1;
                }
            }
            if($model->validate()){
                if ($model->save()) {
                    /*
                     * Delete data in the pollingQuizQuestion Table
                     * */
                    $pollingQuestionOptionModel= PollingQuizQuestionOption::deleteAll(['polling_quiz_question_id'=>$id]);
                    $pollingQuestionCorrectAnswerModel= PollingQuizQuestionCorrectAnswer::deleteAll(['polling_quiz_question_id'=>$id]);
                    /*save question option value in tbl_polling_quiz_question_option table */
                    if ($model->type == PollingQuizQuestion::MULTIPLE_CHOICE_QUESTION) {
                        if (isset($quizQuestionForm['question_type_option'])) {
                            $questionType = $quizQuestionForm['question_type_option'];
                            foreach ($questionType as $key=>$type) {
                                $PollingQuizQuestionOption = new PollingQuizQuestionOption();
                                $PollingQuizQuestionOption->polling_quiz_question_id = $model->id;
                                $PollingQuizQuestionOption->value = $type;
                                if($PollingQuizQuestionOption->save()){
                                    if(isset($quizQuestionForm['question_option_correct'])){
                                        $questionTypeOption=$quizQuestionForm['question_option_correct'];
                                        if($key==$questionTypeOption){
                                            // save correct answer
                                            $PollingQuizQuestionCorrectAnswer = new PollingQuizQuestionCorrectAnswer();
                                            $PollingQuizQuestionCorrectAnswer->polling_quiz_question_id = $model->id;
                                            $PollingQuizQuestionCorrectAnswer->answer = $PollingQuizQuestionOption->id;
                                            $PollingQuizQuestionCorrectAnswer->save(false);
                                        }

                                    }
                                }
                            }
                        }
                    }
                    if ($model->type == PollingQuizQuestion::MULTIPLE_RESPONSE) {
                        if (isset($quizQuestionForm['question_type_response'])) {
                            $questionType = $quizQuestionForm['question_type_response'];
                            $correct_response_id_string_array=[];
                            foreach ($questionType as $key=>$type) {
                                $PollingQuizQuestionOption = new PollingQuizQuestionOption();
                                $PollingQuizQuestionOption->polling_quiz_question_id = $model->id;
                                $PollingQuizQuestionOption->value = $type;
                                if($PollingQuizQuestionOption->save()){
                                    if (isset($quizQuestionForm['question_response_correct'])) {
                                        $questionTypeResponseCorrect = $quizQuestionForm['question_response_correct'];
                                        foreach ($questionTypeResponseCorrect as $index_value) {
                                            if($key==$index_value){
                                                array_push($correct_response_id_string_array,$PollingQuizQuestionOption->id);
                                            }
                                        }

                                    }
                                }
                            }
                            if(count($correct_response_id_string_array)>0){
                                $answer=implode(',',$correct_response_id_string_array);
                                $PollingQuizQuestionCorrectAnswer = new PollingQuizQuestionCorrectAnswer();
                                $PollingQuizQuestionCorrectAnswer->polling_quiz_question_id = $model->id;
                                $PollingQuizQuestionCorrectAnswer->answer = $answer;
                                $PollingQuizQuestionCorrectAnswer->save(false);
                            }
                        }
                    }
                    if($model->type == PollingQuizQuestion::RATING){
                        if (isset($quizQuestionForm['question_type_rating'])) {
                            $questionType = $quizQuestionForm['question_type_rating'];
                            $PollingQuizQuestionOption = new PollingQuizQuestionOption();
                            $PollingQuizQuestionOption->polling_quiz_question_id = $model->id;
                            $PollingQuizQuestionOption->value = $questionType;
                            if ($PollingQuizQuestionOption->save()) {
                                $pollingQuestionCorrectAnswerModel= PollingQuizQuestionCorrectAnswer::deleteAll(['polling_quiz_question_id'=>$id]);
                                if (isset($quizQuestionForm['question_type_rating_correct'])) {
                                    $questionTypeAnswer = $quizQuestionForm['question_type_rating_correct'];
                                    $PollingQuizQuestionCorrectAnswer = new PollingQuizQuestionCorrectAnswer();
                                    $PollingQuizQuestionCorrectAnswer->polling_quiz_question_id = $model->id;
                                    $PollingQuizQuestionCorrectAnswer->answer = $questionTypeAnswer;
                                    $PollingQuizQuestionCorrectAnswer->save();
                                }
                            }
                        }
                    }
                    if ($model->type == PollingQuizQuestion::TRUE_FALSE) {
                        if (isset($quizQuestionForm['question_type_tf_correct'])) {
                            $pollingQuestionCorrectAnswerModel= PollingQuizQuestionCorrectAnswer::deleteAll(['polling_quiz_question_id'=>$id]);
                            $questionType = $quizQuestionForm['question_type_tf_correct'];
                            $PollingQuizQuestionCorrectAnswer = new PollingQuizQuestionCorrectAnswer();
                            $PollingQuizQuestionCorrectAnswer->polling_quiz_question_id = $model->id;
                            $PollingQuizQuestionCorrectAnswer->answer = $questionType;
                            $PollingQuizQuestionCorrectAnswer->save(false);
                        }
                    }
                    if ($model->type == PollingQuizQuestion::NUMBER) {
                        if (isset($quizQuestionForm['question_type_number'])) {
                            $pollingQuestionCorrectAnswerModel= PollingQuizQuestionCorrectAnswer::deleteAll(['polling_quiz_question_id'=>$id]);
                            $questionTypeNumber = $quizQuestionForm['question_type_number'];
                            $PollingQuizQuestionCorrectAnswer = new PollingQuizQuestionCorrectAnswer();
                            $PollingQuizQuestionCorrectAnswer->polling_quiz_question_id = $model->id;
                            $PollingQuizQuestionCorrectAnswer->answer = $questionTypeNumber;
                            $PollingQuizQuestionCorrectAnswer->save(false);
                        }
                    }
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }else{
                $model->show_question_url_result=getenv('BACKEND_URL').'polling/show-result/index?id='.$model->pollingQuiz->polling_id."&question_id=".$model->polling_quiz_question_direct_id;
                return $this->render('update', [
                    'model' => $model,
                    'pollingQuizQuestionOption' => PollingQuizQuestionOption::find()->where(['polling_quiz_question_id' => $id])->all(),
                    'pollingQuizQuestionCorrectAnswers' => PollingQuizQuestionCorrectAnswer::find()->where(['polling_quiz_question_id' => $id])->all(),
                ]);
            }

        } else {
            $model->show_question_url_result=getenv('BACKEND_URL').'polling/show-result/index?id='.$model->pollingQuiz->polling_id."&question_id=".$model->polling_quiz_question_direct_id;
            return $this->render('update', [
                'model' => $model,
                'pollingQuizQuestionOption' => PollingQuizQuestionOption::find()->where(['polling_quiz_question_id' => $id])->all(),
                'pollingQuizQuestionCorrectAnswers' => PollingQuizQuestionCorrectAnswer::find()->where(['polling_quiz_question_id' => $id])->all(),
            ]);
        }
    }

    /**
     * Deletes an existing PollingQuizQuestion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
       $model = $this->findModel($id);
        $polling_quiz_id=$model->polling_quiz_id;
        $model->delete();
        return $this->redirect(['./polling-quiz/view?id='.$polling_quiz_id]);

    }

    /**
     * Finds the PollingQuizQuestion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PollingQuizQuestion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PollingQuizQuestion::find()->with('pollingQuizQuestionCorrectAnswer')->where('id=:id',[':id'=>$id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
