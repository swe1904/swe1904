<?php
/**
 * Created by PhpStorm.
 * User: OWNER
 * Date: 14-10-2016
 * Time: 11:06 AM
 */
namespace backend\components;

use yii\base\Widget;
use yii\helpers\Html;

class MultipleFields extends Widget{
    public $models;

    public function init(){
        parent::init();

    }

    public function run(){
        //return Html::encode($this->message);
        return $this->render('addField',['models' => $this->models]);
    }
}