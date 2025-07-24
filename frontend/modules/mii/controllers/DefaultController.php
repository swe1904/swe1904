<?php

namespace frontend\modules\mii\controllers;

use frontend\modules\mii\components\MiiGlobalConstants;
use frontend\modules\mii\jsonData\Client;
use frontend\modules\mii\migration\DatabaseMigration;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{
   /* public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'delete-list'=>['post']
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['custom-builder'],
                        'allow' => true,
                        'roles' => ['?'],
                        'denyCallback' => function () {
                            return Yii::$app->controller->redirect(['/user/default/index']);
                        }
                    ],
                ]
            ],
        ];
    }*/
    public $layout='@frontend/modules/mii/views/layouts/main';
    /**
     * @var \yii\gii\Module
     */
    public $module;
    /**
     * @var \yii\gii\Generator
     */
    public $generator;
    public $newFormData;
    public function beforeAction($action) {
        $this->enableCsrfValidation = false; return parent::beforeAction($action);
    }
    public function actionIndex()
    {
        /*$d=new DatabaseMigration();
        $d->up();
        $this->createModel();
        $this->createCrud();*/
        $this->createModel2();

    }
    public function actionCustomBuilder(){
        $oldFormData=$this->getOldData();

       return $this->render('custom-builder2',['defaultVal'=>$oldFormData]);
    }

    private function createModel2($extendedRulesArray=[]){
        $id="model";
        $_POST=[
            '_csrf' => 'aklLMzYxYTIPfyFQWUUPeRoFO39nQRFLGicKQFkFVmcuLQ1UXmYTaA==',
            'Generator'=>[
                "tableName" => "client",
                "modelClass" => "Client",
                "ns" => "frontend\models",
                "baseClass" => "yii\db\ActiveRecord",
                "db" => "db",
                "useTablePrefix" => "0",
                "generateRelations" => "1",
                "generateLabelsFromComments" => "0",
                "generateQuery" => "0",
                "queryNs" => "frontend\models",
                "queryClass" => "ClientQuery",
                "queryBaseClass" => "yii\db\ActiveQuery",
                "enableI18N" => "0",
                "messageCategory" => "app",
                "template" => "default"
            ],
            "preview" =>""
        ];

        /**
         * @var \yii\gii\generators\model\Generator $generator
         */
        $generator = $this->loadGenerator($id);

        /*array_push($generator->extendedRulesArray,['email','email']);
        array_push($generator->extendedRulesArray,['password','password']);*/
        $params = ['generator' => $generator, 'id' => $id];
        $answerArray=[];


        $generator->saveStickyAttributes();
        $files = $generator->generate();
        foreach ($files as $key=>$file){
            $answerArray[$file->id]=1;
        }

        $_POST['answers']=$answerArray;

        $params['hasError'] = !$generator->save($files, (array) $_POST['answers'], $results);
        $params['results'] = $results;

    }

    private function createModel($extendedRulesArray=[]){
        $id="model";
            $_POST=[
                '_csrf' => 'aklLMzYxYTIPfyFQWUUPeRoFO39nQRFLGicKQFkFVmcuLQ1UXmYTaA==',
                'Generator'=>[
                    "tableName" => "client",
                    "modelClass" => "Client",
                    "ns" => "frontend\models",
                    "baseClass" => "yii\db\ActiveRecord",
                    "db" => "db",
                    "useTablePrefix" => "0",
                    "generateRelations" => "1",
                    "generateLabelsFromComments" => "0",
                    "generateQuery" => "0",
                    "queryNs" => "frontend\models",
                    "queryClass" => "ClientQuery",
                    "queryBaseClass" => "yii\db\ActiveQuery",
                    "enableI18N" => "0",
                    "messageCategory" => "app",
                    "template" => "default"
                ],
                "preview" =>""
    ];

        /**
         * @var \frontend\modules\mii\generators\model\Generator $generator
         */
        $generator = $this->loadGenerator($id);

        $generator->newFormData=$this->newFormData;
        if(!empty($extendedRulesArray)){
            $generator->modify=true;
            $generator->extendedRulesArray=$extendedRulesArray;
        }
        /*array_push($generator->extendedRulesArray,['email','email']);
        array_push($generator->extendedRulesArray,['password','password']);*/
        $params = ['generator' => $generator, 'id' => $id];
        $answerArray=[];


        $generator->saveStickyAttributes();
        $files = $generator->generate();
        foreach ($files as $key=>$file){
            $answerArray[$file->id]=1;
        }

        $_POST['answers']=$answerArray;

        $params['hasError'] = !$generator->save($files, (array) $_POST['answers'], $results);
        $params['results'] = $results;

    }
    private function createCrud(){
        $id="crud";
        $_POST=[
            '_csrf' => 'aklLMzYxYTIPfyFQWUUPeRoFO39nQRFLGicKQFkFVmcuLQ1UXmYTaA==',
            'Generator'=>[
                "modelClass" => "frontend\models\Client",
                "searchModelClass" => "frontend\models\search\ClientSearch",
                "controllerClass" => "frontend\controllers\ClientController",
                "viewPath" => "../views/client",
                "baseControllerClass" => "yii\web\Controller",
                "indexWidgetType" => "grid",
                "enableI18N" => "0",
                "messageCategory" => "app",
                "template" => "default",
            ],
            "preview" =>""
        ];

        /**
         * @var \frontend\modules\mii\generators\crud\Generator $generator
         */
        $generator = $this->loadGenerator($id);
        $generator->newFormData=$this->newFormData;
        $params = ['generator' => $generator, 'id' => $id];
        $answerArray=[];


        $generator->saveStickyAttributes();
        $files = $generator->generate();
        foreach ($files as $key=>$file){
            $answerArray[$file->id]=1;
        }

        $_POST['answers']=$answerArray;

        $params['hasError'] = !$generator->save($files, (array) $_POST['answers'], $results);
        $params['results'] = $results;
    }
    /**
     * Loads the generator with the specified ID.
     * @param string $id the ID of the generator to be loaded.
     * @return \yii\gii\Generator the loaded generator
     * @throws NotFoundHttpException
     */
    protected function loadGenerator($id)
    {

        if (isset($this->module->generators[$id])) {
            $this->generator = $this->module->generators[$id];
            $this->generator->loadStickyAttributes();
            $this->generator->load($_POST);
            return $this->generator;
        } else {
            throw new NotFoundHttpException("Code generator not found: $id");
        }
    }
    public function actionSaveData(){
        $fileName=\Yii::getAlias("@frontend") . '/modules/mii/jsonData/Client.php';

        $databaseMigration=new DatabaseMigration();


        $newFormData=json_decode($_POST['data'],true);
        $this->newFormData=$newFormData;
        $finalModelArray=[];

        foreach ($newFormData as $formData){
            array_push($finalModelArray,$this->returnFieldTypes($formData));
        }

        // check if old data exist
        $oldFormData=$this->getOldData();
        $oldModelArray=[];
        $newModelArray=[];
        if(!empty($oldFormData)){

            foreach ($oldFormData as $formData){
                array_push($oldModelArray,$this->returnFieldTypes($formData));
            }
            foreach ($newFormData as $formData){
                array_push($newModelArray,$this->returnFieldTypes($formData));
            }
            $modifiedData=$this->returnNewDataArray($oldModelArray,$newModelArray);

            $sqlAlterFieldsArray=$modifiedData['alteredDataArray'];
            $sqlNewFieldsArray=$modifiedData['newDataArray'];
            $sqlDeleteFieldsArray=$modifiedData['oldDataArray'];

            // get sql fields
            $sqlAlteredFields=$this->returnSqlFields($sqlAlterFieldsArray);
            $sqlNewFields=$this->returnSqlFields($sqlNewFieldsArray);
            $sqlDeleteFields=$this->returnSqlFields($sqlDeleteFieldsArray);

            // generate from scratch
            $this->createArrayFile($fileName,$newFormData);

            $databaseMigration->sqlAlterFieldsArray=$sqlAlteredFields;
            $databaseMigration->sqlNewFieldsArray=$sqlNewFields;
            $databaseMigration->sqlOldFieldsArray=$sqlDeleteFields;
            // manipulate column
            $databaseMigration->manipulateColumns();

            $extendedModelRuleArray=$this->returnExtendedModelRuleArray($finalModelArray);
            // create model
            $this->createModel($extendedModelRuleArray);
            $this->createCrud();
           /* echo "<pre>";
            echo "alter";
            print_r($sqlAlteredFields);
            echo "new";
            print_r($sqlNewFields);
            echo "old";
            print_r($sqlDeleteFields);
            echo "</pre>";
            die("die here");*/
        }else{

            $extendedModelRuleArray=$this->returnExtendedModelRuleArray($finalModelArray);
            $sqlFields=$this->returnSqlFields($finalModelArray);

            // generate from scratch
            $this->createArrayFile($fileName,$newFormData);
            // set sql fields
            $databaseMigration->sqlNewFieldsArray=$sqlFields;
            $databaseMigration->createNewTable();
            $this->createModel($extendedModelRuleArray);
            $this->createCrud();
        }
        die;


        echo "<pre>";
        print_r($extendedModelRulArray);
        echo "</pre>";
        die("die here");
        die("die here");
        $fileName=\Yii::getAlias("@frontend") . '/modules/mii/jsonData/Client.php';
        if (file_exists($fileName)) {
          file_put_contents($fileName,$fileData);
        }
    }
    private function createArrayFile($fileName,$newFormData){
        $fileData=$this->renderPartial('client_array_file',['formData'=>$newFormData]);
        if (file_exists($fileName)) {
            return file_put_contents($fileName,$fileData);
        }
        return false;
    }
    private function returnExtendedModelRuleArray($finalModelArray){
        $extendedModelRuleArray=[];
        if(!empty($finalModelArray)){
            foreach ($finalModelArray as $finalModel){

                // check for email an password types
                if($finalModel['fieldType']=='text'){
                    if($finalModel['fieldSubType']=='email'){
                        array_push($extendedModelRuleArray,['name'=>$finalModel['fieldName'],'value'=>'email']);
                    }else if($finalModel['fieldSubType']=='password'){
                        array_push($extendedModelRuleArray,['name'=>$finalModel['fieldName'],'value'=>'password']);
                    }
                }

            }
        }
        return $extendedModelRuleArray;
    }
    private function returnSqlFields($finalModelArray){
        $sqlFields=[];
        if(!empty($finalModelArray)){
            foreach ($finalModelArray as $finalModel){

                // adding sql field to generate tables and columns.
                $value=$finalModel['sqlFieldVarType']." ".$finalModel['sqlFieldNullType'];
                array_push($sqlFields,['column'=>$finalModel['fieldName'],'value'=>$value]);
            }
        }
        return $sqlFields;
    }
    private function returnNewDataArray($oldFormArray,$newFormArray){
        $oldIndexedArray=$this->returnIndexedArray($oldFormArray);
        $newIndexedArray=$this->returnIndexedArray($newFormArray);

        $matchedArray=[];
        $newArray=[];
        $deletedArray=[];
        foreach ($newIndexedArray as $index=>$fieldName){
            $foundIndex=in_array($fieldName,$oldIndexedArray);
            if($foundIndex){
                echo $index."<br>";
                array_push($matchedArray,$index);
            }else{

                array_push($newArray,$index);
            }
        }
        // find deleteArray index
        foreach ($oldIndexedArray as $index=>$fieldName){
            $foundIndex2=in_array($fieldName,$newIndexedArray);
            if(!$foundIndex2){
                array_push($deletedArray,$index);
            }
        }

        // get new modifications
        $newDataToBeAltered=[];
        $newDataToBeEntered=[];
        $oldDataToBeDeleted=[];

        foreach($matchedArray as $index1){
            array_push($newDataToBeAltered,$newFormArray[$index1]);
        }
        foreach($newArray as $index2){
            array_push($newDataToBeEntered,$newFormArray[$index2]);
        }
        foreach($deletedArray as $index3){
            array_push($oldDataToBeDeleted,$oldFormArray[$index3]);
        }

        return ['alteredDataArray'=>$newDataToBeAltered,'newDataArray'=>$newDataToBeEntered,'oldDataArray'=>$oldDataToBeDeleted];
    }
    private function returnIndexedArray($array){
        $newArray=[];
        foreach ($array as $data){
            array_push($newArray,$data['fieldName']);
        }
        return $newArray;
    }
    /*
     * @var string $type
     * */
    private function returnFieldTypes($formData){

        $modelInArray=[
            'fieldLabel'=>null,
            'fieldPlaceholder'=>null,
            'fieldType'=>null,
            'fieldName'=>null,
            'fieldSubType'=>null,
            'fieldRequired'=>false,
            'sqlFieldVarType'=>null,
            'sqlFieldNullType'=>null,
            'fieldSelectValueArray'=>[],
            'fieldSelectValueMultiple'=>false,

        ];
        switch($formData['type']){
            case "text":
                $modelInArray['fieldType']=$formData['type'];
                $modelInArray['fieldName']=$this->returnFixedFieldsData($formData['name']);

                $modelInArray['fieldLabel']=!empty($formData['label'])?$formData['label']:"form text";
                $modelInArray['fieldPlaceholder']=!empty($formData['placeholder'])?$formData['placeholder']:"type...";

                $modelInArray['sqlFieldVarType']="string(255)";
                $modelInArray['sqlFieldNullType']=!empty($formData['required'])?"NOT NULL":"";
                $modelInArray['fieldSubType']=$formData['subtype'];
                $modelInArray['fieldRequired']=!empty($formData['required'])?true:false;


                break;
            case "textarea":
                $modelInArray['fieldType']=$formData['type'];
                $modelInArray['fieldName']=$this->returnFixedFieldsData($formData['name']);

                $modelInArray['fieldLabel']=!empty($formData['label'])?$formData['label']:"form text";
                $modelInArray['fieldPlaceholder']=!empty($formData['placeholder'])?$formData['placeholder']:"type...";

                if($formData['subtype']=='textarea'){
                    $modelInArray['sqlFieldVarType']="text";
                    $modelInArray['sqlFieldNullType']=!empty($formData['required'])?"NOT NULL":"";
                    $modelInArray['fieldRequired']=!empty($formData['required'])?true:false;
                }
                break;
            case "date":
                $modelInArray['fieldType']=$formData['type'];
                $modelInArray['fieldName']=$this->returnFixedFieldsData($formData['name']);

                $modelInArray['fieldLabel']=!empty($formData['label'])?$formData['label']:"form text";
                $modelInArray['fieldPlaceholder']=!empty($formData['placeholder'])?$formData['placeholder']:"type...";

                $modelInArray['sqlFieldVarType']="datetime";
                $modelInArray['sqlFieldNullType']=!empty($formData['required'])?"NOT NULL":"";
                $modelInArray['fieldRequired']=!empty($formData['required'])?true:false;
                break;
            case "select":
                $modelInArray['fieldType']=$formData['type'];
                $modelInArray['fieldSelectValueArray']=$formData['values'];
                $modelInArray['fieldName']=$this->returnFixedFieldsData($formData['name']);

                $modelInArray['fieldLabel']=!empty($formData['label'])?$formData['label']:"form text";
                $modelInArray['fieldPlaceholder']=!empty($formData['placeholder'])?$formData['placeholder']:"type...";

                $modelInArray['sqlFieldVarType']="string(255)";
                $modelInArray['sqlFieldNullType']=!empty($formData['required'])?"NOT NULL":"";
                $modelInArray['fieldRequired']=!empty($formData['required'])?true:false;
                $modelInArray['fieldSelectValueMultiple']=!empty($formData['multiple'])?true:false;
                break;

        }
        return $modelInArray;
    }
    private function returnFixedFieldsData($fieldName){
        if(in_array($fieldName,MiiGlobalConstants::returnFixedFields())){
            return $fieldName;
        }else{
            return str_replace('-','_',$fieldName);
        }
    }
    public function getOldData(){
        $data=Client::returnData();
        return $data;
    }
    public function actionD(){
        $new=new \frontend\models\Client();
        $fileName=\Yii::getAlias("@frontend") . '/models/Client.php';
        if (file_exists($fileName)) {
            $data=file_get_contents($fileName);

            echo "<pre>";
            print_r($data);
            echo "</pre>";
            die("die here");
        }
    }
}
