<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 7/3/14
 * Time: 3:14 PM
 */

namespace frontend\assets;

use yii\web\AssetBundle;

class LoginFinalAsset extends AssetBundle
{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/pangea_css/style.css'
    ];

    public $js = [
        'js/bootstrap.min.js',
        'js/jquery.slimscroll.js',
        'js/jquery.init.js',
        'js/jquery.min.js',
    ];

    public $depends = [
//        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
//        'common\assets\Html5shiv',
    ];

}