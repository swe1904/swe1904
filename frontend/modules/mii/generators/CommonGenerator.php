<?php
/**
 * Created by PhpStorm.
 * User: ome
 * Date: 12-06-2018
 * Time: 15:29
 */

namespace frontend\modules\mii\generators;


use frontend\modules\mii\components\MiiGlobalConstants;
use yii\gii\CodeFile;

class CommonGenerator extends \yii\gii\Generator
{
    public $newFormData=[];
    public function getName()
    {
        // TODO: Implement getName() method.
    }

    public function generate()
    {
        // TODO: Implement generate() method.
    }
    public function generateString($string = '', $placeholders = [])
    {
        if($string=='id'){
            return "'" . "id" . "'";
        }else{
            return "'" . $this->returnLabelName($string) . "'";
        }

    }
    private function returnLabelName($string){
        if(!in_array($string,MiiGlobalConstants::returnFixedFields())){
            $string=str_replace('_','-',$string);
        }
        foreach ($this->newFormData as $data){
            if($data['name']==$string){
                return $data['label'];
                break;
            }
        }

        return "";
    }


}