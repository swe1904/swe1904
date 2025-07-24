<?php


namespace backend\widgets\gridview;
use yii\helpers\ArrayHelper;

class ActiveField extends \yii\widgets\ActiveField{

    //Error Options For Active Field Error Tag
   // public $errorOptions= ['class' => 'error', 'tag' => 'span'];

    public function init(){

        //Changing Input Options Merge with form-class

            $this->inputOptions = ['class' => 'formInput'];

        parent::init();
    }

    /**
     * @param null $label Setting Label Value to false
     * @param array $options
     */
//    public function label($label = null, $options = [])
//    {
//
//        $this->parts['{label}'] = '';
//
//    }

}