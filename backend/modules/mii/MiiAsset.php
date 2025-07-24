<?php

namespace backend\modules\mii;

use yii\web\AssetBundle;
use yii\web\View;

class MiiAsset extends AssetBundle {


    public $css = [
       /* 'css/main.css',
        'css/main_style.css',*/
        'css/main.css',
        'css/demo.css',
        'css/demoRender.css',
    ];
    public $js = array(
         'js/demo.js',
        'js/vendor.js',
        'js/form-builder.min.js',
        'js/form-render.min.js',
    );
    public $jsOptions = array('position' => View::POS_HEAD);
    public $depends = array('yii\web\YiiAsset','yii\bootstrap\BootstrapAsset');

    public function init()
    {
        $this->sourcePath = __DIR__ . "/asset";

        parent::init();
    }
}
