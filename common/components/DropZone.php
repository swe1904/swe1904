<?php
  namespace common\components;

  use yii\helpers\Json;
  use kato\assets\DropZoneAsset;
  use kato\DropZone as KatoDropZone;
  
  class DropZone extends KatoDropZone
  {
      public function registerAssets()
      {
          $view = $this->getView();
          $autoDiscover = $this->autoDiscover;
          $id = $this->id;
          $dropzoneContainer = $this->dropzoneContainer;
          $options = Json::encode($this->options);
  
          $js = <<<JS
          Dropzone.autoDiscover = $autoDiscover;
          var $id= new Dropzone("div#$dropzoneContainer",$options);
  JS;
  
          if (!empty($this->clientEvents)) {
              foreach ($this->clientEvents as $event => $handler) {
                  $js .= "$this->id.on('$event', $handler);";
              }
          }
  
          $view->registerJs($js, $view::POS_END);
          DropZoneAsset::register($view);
      }
  }
?>