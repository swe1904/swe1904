<?php

namespace backend\modules\messagesystem;

use yii\web\AssetBundle;
use yii\web\View;

class MessageAsset extends AssetBundle {


    public $css = [
       /* 'css/main.css',
        'css/main_style.css',*/
        'css/main2.css',
        'css/new2.css',
    ];
    public $js = array(
         'js/timeAgo.js'
    );
    public $jsOptions = array('position' => View::POS_HEAD);
    public $depends = array('yii\web\YiiAsset','yii\bootstrap\BootstrapAsset');

    public function init()
    {
        $this->sourcePath = __DIR__ . "/asset";

        parent::init();
    }
}
