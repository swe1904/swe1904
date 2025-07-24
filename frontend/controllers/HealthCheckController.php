<?php

namespace frontend\controllers;

class HealthCheckController extends \yii\web\Controller
{
    public function actionIndex()
    {
        echo "Health Check Successful";
    }

}
