<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 7/3/14
 * Time: 3:14 PM
 */

namespace frontend\assets;

use yii\web\AssetBundle;

class LoginAsset extends AssetBundle
{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
//        'css/login.css',
//        'css/pangea_css/style2.css',
    'css/pangea_css/bootstrap.css',
        'css/pangea_css/all-themes.css',
        'css/pangea_css/style.css',
        'css/pangea_css/waves.css',
    ];

    public $depends = [
//        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
//        'common\assets\Html5shiv',
    ];

}