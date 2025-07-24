<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 6/29/2018
 * Time: 5:35 PM
 */
namespace backend\assets;

use yii\web\AssetBundle;

class PangeaAsset extends AssetBundle
{
    public $basePath = '@web/';
    public $baseUrl = '@web/';

    public $css = [
        'css/pangea_css/style.css',
        'css/pangea_css/select2.css',
        'css/_modify.css',
        'https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700,800',
//        'fonts/dejavu-sans-condensed/css/dejavu-sans-condensed.css'
    ];
    public $js = [
        'js/pangea_js/custom.js',
        'js/pangea_js/form.js',
        'js/pangea_js/scoop.min.js',
        'js/pangea_js/select2.js',
    ];

    public $depends = [
      //  'yii\web\YiiAsset',
        'backend\assets\BackendAsset',
    ];
    public $jsOptions = array(
        'position' => \yii\web\View::POS_HEAD
    );
}