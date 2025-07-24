<?php

namespace backend\widgets\attachmentGallery;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Widget asset bundle
 */
class AttachmentGalleryAsset extends AssetBundle
{
    /*public $jsOptions = [
        'position' => View::POS_LOAD
    ];*/

    /**
     * @inheritdoc
     */
    public $sourcePath = '@attachmentGallery/web/';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/_view.css',
       // 'css/main.css'
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        //'js/custom.js',
        //'js/cropper.js',
        //'js/main.js'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
        /*'hscropper\cropper\assets\JcropAsset',
        'hscropper\cropper\assets\SimpleAjaxUploaderAsset',*/
    ];
}
