<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 7/3/14
 * Time: 3:14 PM
 */

namespace backend\assets;

use yii\web\AssetBundle;

class BackendAsset extends AssetBundle
{
    public $basePath = '@web/';
    public $baseUrl = '@web/';

    public $css = [
        'css/style.css',
        'css/custom.css',
        'css/_modify.css',
//        'fonts/dejavu-sans-condensed/css/dejavu-sans-condensed.css'
    ];
    public $js = [
        'js/app.js',
        'js/helpers.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'common\assets\AdminLte',
        'common\assets\Html5shiv'
    ];
    public $jsOptions = array(
        'position' => \yii\web\View::POS_HEAD
    );
}
