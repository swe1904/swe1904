<?php 
// Path: @app/components/FilterFormWidget.php

namespace backend\components;

use yii\base\Widget;
use yii\helpers\Html;

class CustomFormWidget extends Widget
{
    public $formId = 'default';
    public $formHeading = '';
    public $formClass = '';
    public $submitButtonClass = 'btn-success';
    public $submitButtonTextCondition = '$model->isNewRecord';
    public $submitButtonTextConditionIfYes = 'Create';
    public $submitButtonTextConditionIfNo = 'Update';
    public $model;
    public $instruction;
    public $options = [];

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('@backend/views/components/customForm', [


            'model' => $this->model,
            'formHeading' => $this->formHeading,
            'formId' => $this->formId,
            'formClass' => $this->formClass,
            'options' => $this->options,
            'instruction' => $this->instruction,
            'submitButtonClass' => $this->submitButtonClass,
            'submitButtonTextCondition' => $this->submitButtonTextCondition,
            'submitButtonTextConditionIfYes' => $this->submitButtonTextConditionIfYes,
            'submitButtonTextConditionIfNo' => $this->submitButtonTextConditionIfNo,


        ]);
    }
}


?>