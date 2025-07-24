<?php

namespace backend\controllers;

use common\models\ArticleRequest;
use common\models\ArticleTmp;
use Yii;
use common\models\Article;
use backend\models\search\ArticleSearch;
use \common\models\ArticleCategory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post']
                ]
            ]
        ];
    }

    /**
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = [
            'defaultOrder' => ['published_at' => SORT_DESC]
        ];
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = null)
    {
        if (isset($id) && $id != '') {
        }
        $model = new Article();
        $model->article_request_id = $id;

        $article_request = ArticleRequest::find()->where(['id' => $id])->one();
        if ($article_request->status != 'Claim') {
            $article_request->status = 'Claim';
            date_default_timezone_set('Asia/Kolkata');
            $claim_at = date('Y-m-d H:i:s');
            $user_id = Yii::$app->user->id;
            $article_request->claim_at = $claim_at;
            $article_request->claim_by = $user_id;
            $article_request->save();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $article_request_new = ArticleRequest::find()->where(['id' => $id])->one();
            $article_request_new->status = 'Applied';
            $article_request_new->claim_by = '0';
            $article_request_new->save();

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'categories' => ArticleCategory::find()->active()->all(),
                'id' => $id,
            ]);
        }
    }

    /*
     * save article for tmp
     */
    public function actionArticleDataSave()
    {
        $_POST['id']='45';
        $_POST['body']='sdfsdfsd';
        $article_request_id = $_POST['id'];
        $body = $_POST['body'];
        $user_id = Yii::$app->user->id;
        $model = new ArticleTmp();
        $model->article_request_id = $article_request_id;
        $model->body = $body;
        $model->author_id = $user_id;
        $tmp_data=ArticleTmp::find()->where(['article_request_id'=>$article_request_id])->one();
        if(count($tmp_data)>0){
            $tmp_data->body=$body;
            if ($tmp_data->save(false)) {
                return 'success';
            }
            else{
                return 'unsuccess';
            }
        }
        else{
            if ($model->save(false)) {
                return 'success';
            }
            else{
                return 'unsuccess';
            }
        }



    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $article_request_id = $model->article_request_id;
        $article_request_data = ArticleRequest::findOne($article_request_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $article_request_data->status = 'Resubmit';
            $article_request_data->save();

            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'categories' => ArticleCategory::find()->active()->all(),
            ]);
        }
    }

    /**
     * Deletes an existing Article model.
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
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
