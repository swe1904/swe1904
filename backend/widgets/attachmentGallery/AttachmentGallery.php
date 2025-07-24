<?php

namespace backend\widgets\attachmentGallery;

//use hscropper\assets\HsCropperAsset;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Json;
use Yii;
use yii\web\View;

class AttachmentGallery extends Widget
{
    public $label = null;
    public $cancelButton;
    public $imageButton;
    public $attachmentArray = [];
    public $cancel = true;
    public $module_id;
    public $uId=-1;
    public $gridView=false;
    public $style=null;
    /**
     * @var string
     */
    public $function_name, $function_name_image;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::setAliases();
        if (!empty($this->cancelButton)) {
            $this->function_name = $this->generateRandomString(7);
            $this->function_name = $this->function_name . "_image_button";
            $this->cancelButton = str_replace('onClickCancel', $this->function_name, $this->cancelButton);

        }
        $this->function_name_image = $this->generateRandomString(7);
        $this->function_name_image = $this->function_name_image . "_image_button";
        $this->imageButton = str_replace('onClickImage', $this->function_name_image, $this->imageButton);
        //$this->registerClientAssets();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerClientAssets();
        return $this->render('_view', ['attachmentArray' => $this->attachmentArray, 'obj' => $this, 'clickFnName' => $this->function_name, 'clickFnNameImage' => $this->function_name_image, 'cancel' => $this->cancel, 'module_id' => $this->module_id, 'label' => $this->label,'uid'=>$this->uId,'gridView'=>$this->gridView,'style'=>$this->style]);
    }

    protected function setAliases()
    {
        Yii::setAlias('@attachmentGallery', realpath(__DIR__ . '/../attachmentGallery'));
    }

    /**
     * Register widget asset.
     */
    public function registerClientAssets()
    {
        $view = $this->getView();
        $assets = AttachmentGalleryAsset::register($view);
        $this->registerAssets();
    }

    public function registerAssets()
    {
        $this->view->registerJs($this->cancelButton, View::POS_END);
        $this->view->registerJs($this->imageButton, View::POS_END);
    }

    public function returnExtICon($ext)
    {
        switch ($ext) {
            case 'pdf':
                return Yii::getAlias('@storageUrl' . '/source/pdf.png');
                break;
            case 'docx':
                return Yii::getAlias('@storageUrl' . '/source/word.png');
                break;
            case 'xlsx':
                return Yii::getAlias('@storageUrl' . '/source/Excel.png');
                break;
            case 'sql':
                return Yii::getAlias('@storageUrl' . '/source/sql.png');
                break;
            case 'bmpr':
                return Yii::getAlias('@storageUrl' . '/source/balsamiq.png');
                break;
            case 'csv':
                return Yii::getAlias('@storageUrl' . '/source/csv.png');
                break;
            case 'zip':
                return Yii::getAlias('@storageUrl' . '/source/zip.png');
                break;
            case 'rtf':
                return Yii::getAlias('@storageUrl' . '/source/rtf.png');
                break;
            default:
                return Yii::getAlias('@storageUrl' . '/source/default.png');
                break;

        }
    }

    public function returnImageIcon($attachment)
    {
        if (!empty($attachment)) {
            return $attachment['attachment'];
        }
        return null;

    }

    private function generateRandomString($length = 10)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Register widget translations.
     */
}
