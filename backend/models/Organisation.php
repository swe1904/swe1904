<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use yii\helpers\Json;
use Imagine\Image\Box;
use Imagine\Image\Point;
use yii\db\Expression;

class Organisation extends \common\models\Organisation
{

    public $crop_info;


    /*public static function setDefaultDate(){
        $model = Organisation::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
        if(empty($model)){
            $model = Organisation::find()->where(['id'=>Yii::$app->user->identity->organisation_id])->one();
        }
        if($model->date_format=='1'){
            return 'yyyy-MM-dd';
        }elseif($model->date_format=='2'){
            return 'yyyy/MM/dd';
        }elseif($model->date_format=='3'){
            return 'MM-dd-yyyy';
        }elseif($model->date_format=='4'){
            return 'MM-dd-yyyy';
        }elseif($model->date_format=='5'){
            return 'dd-MM-yyyy';
        }elseif($model->date_format=='6'){
            return 'dd/MM/yyyy';
        }
        else{
            return 'dd-MM-yyyy';
        }
    }*/


    public static function setDefaultDate()
{
    $model = Organisation::find()->where(['user_id' => Yii::$app->user->identity->id])->one();
    if ($model === null) {
        $model = Organisation::find()->where(['id' => Yii::$app->user->identity->organisation_id])->one();
    }

    if ($model !== null && $model->date_format == '1') {
        return 'yyyy-MM-dd';
    } elseif ($model !== null && $model->date_format == '2') {
        return 'yyyy/MM/dd';
    } elseif ($model !== null && $model->date_format == '3') {
        return 'MM-dd-yyyy';
    } elseif ($model !== null && $model->date_format == '4') {
        return 'MM-dd-yyyy';
    } elseif ($model !== null && $model->date_format == '5') {
        return 'dd-MM-yyyy';
    } elseif ($model !== null && $model->date_format == '6') {
        return 'dd/MM/yyyy';
    } else {
        return 'dd-MM-yyyy';
    }
}






    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }
}
