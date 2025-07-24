<?php
/**
 * Created by PhpStorm.
 * User: ome
 * Date: 27-06-2018
 * Time: 17:23
 */

namespace backend\models\clientFixed;


use app\models\ClientMultiSelect;
use backend\models\FileUpload;
use yii\db\ActiveRecord;

class CommonFixed extends ActiveRecord
{
    public function getFileData($attr){
        $fileModels=FileUpload::find()->where('file_id=:file_id',[':file_id'=>$this->$attr])->all();
        if(!empty($fileModels)){
            $attachmentsArrayFinal=[];
            foreach ($fileModels as $attachment){
                $attachmentsArray=[];
                $attachmentsArray['id']=$attachment->id;
                $attachmentsArray['attachment']=$attachment->attachment;
                $attachmentsArray['extension']=$attachment->extension;
                $attachmentsArray['name']=$attachment->name;
                array_push($attachmentsArrayFinal,$attachmentsArray);
            }
            $data= \backend\widgets\attachmentGallery\AttachmentGallery::widget(
                [
                    'label'=>'Attachments',
                    'attachmentArray' => $attachmentsArrayFinal,
                    'module_id'=>$this->id,
                    'cancel'=>false,
                    'gridView'=>true,
                    'style'=>'width:70px;box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);',
                    'imageButton'=>'function onClickImage(modelId,object){
                                             handleImageClickEvent(modelId,object);
                                            
                                     }',
                ]
            );
            return $data;
        }
    }
    public function getMultiSelectValue($attr){
        $multiSelectValueModels=ClientMultiSelect::find()->where('select_id=:select_id',[':select_id'=>$this->$attr])->all();
        $nameArray=[];
        if(!empty($multiSelectValueModels)){
            foreach ($multiSelectValueModels as $multiSelectValueModel){
                array_push($nameArray,$multiSelectValueModel->name);
            }
        }
        $value=implode(' , ',$nameArray);
        return $value;
    }
    public function getMultiSelectValueArray($attr){
        $value="";
        if(!empty($this->$attr)){
            $value=implode(' , ',$this->$attr);
        }
        return $value;
    }
}