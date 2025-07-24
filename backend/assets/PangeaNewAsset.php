<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 6/29/2018
 * Time: 5:35 PM
 */
namespace backend\assets;

use yii\web\AssetBundle;

class PangeaNewAsset extends AssetBundle
{
    public $basePath = '@web/';
    public $baseUrl = '@web/';

    public $css = [
        'css/pangea_css/style2.css',
//        'css/pangea_css/select2.css',
//        'css/_modify.css',
        'https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700,800',
//        'fonts/dejavu-sans-condensed/css/dejavu-sans-condensed.css',
        'libraries/toastr/toastr.min.css'
    ];
    public $js = [
//        'js/pangea_js/custom.js',
//        'js/pangea_js/form.js',
//        'js/pangea_js/scoop.min.js',
//        'js/pangea_js/select2.js',
        'libraries/toastr/toastr.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
//        'backend\assets\BackendAsset', // Causes auto csrf injection by Yii in ajax calls to stop working..
//        'yii\jui\JuiAsset',
//        'yii\bootstrap\BootstrapPluginAsset',
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'common\assets\FontAwesome',
        'common\assets\JquerySlimScroll',
        'common\assets\Html5shiv'
    ];

//    public $jsOptions = array(
//        'position' => \yii\web\View::POS_HEAD
//    );
}