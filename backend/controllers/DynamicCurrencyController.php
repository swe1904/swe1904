<?php

namespace backend\controllers;

use backend\models\DynamicCurrency;
use backend\models\Currency;
use backend\models\search\DynamicCurrencySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * DynamicCurrencyController implements the CRUD actions for DynamicCurrency model.
 */
class DynamicCurrencyController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all DynamicCurrency models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DynamicCurrencySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DynamicCurrency model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DynamicCurrency model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new DynamicCurrency();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
            $dynamicCurrencies = ArrayHelper::getColumn(DynamicCurrency::find()->select('currency_id')->all(), 'currency_id');
            $currencies = ArrayHelper::map(Currency::find()->where(['not in', 'id', $dynamicCurrencies])->all(), 'id', 'name');
        }

        return $this->render('create', [
            'model' => $model,
            'currencies' => $currencies,
        ]);
    }

    /**
     * Updates an existing DynamicCurrency model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        $currency = Currency::findOne($model->currency_id)->name;

        return $this->render('update', [
            'model' => $model,
            'currency' => $currency,
        ]);
    }

    /**
     * Deletes an existing DynamicCurrency model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DynamicCurrency model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return DynamicCurrency the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DynamicCurrency::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
