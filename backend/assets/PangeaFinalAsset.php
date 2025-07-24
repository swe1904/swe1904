<?php


namespace backend\assets;

use yii\web\AssetBundle;

class PangeaFinalAsset extends AssetBundle
{
    public $basePath = '@web/';
    public $baseUrl = '@web/';

    public $css = [
       'css/pangea_css_final/animate.css',
       'css/pangea_css_final/awesome-bootstrap-checkbox.css',
       'css/pangea_css_final/bootstrap.min.css',
       'css/pangea_css_final/chartist.min.css',
       'css/pangea_css_final/filter.css',
        'css/pangea_css_final/font-awesome.min.css',
        'css/pangea_css_final/jquery.dataTables.min.css',
        'css/pangea_css_final/jquery.toast.min.css',
       'css/pangea_css_final/jquery-jvectormap-2.0.2.css',
       'css/pangea_css_final/lightgallery.css',
       'css/pangea_css_final/linea-icon.css',
        'css/pangea_css_final/material-design-iconic-font.min.css',
        'css/pangea_css_final/meanmenu.css',
       'css/pangea_css_final/morris.css',
       'css/pangea_css_final/owl.carousel.min.css',
       'css/pangea_css_final/owl.theme.default.min.css',
       'css/pangea_css_final/pe-icon-7-stroke.css',
       'css/pangea_css_final/pe-icon-7-styles.css',
       'css/pangea_css_final/simple-line-icons.css',
        'css/pangea_css_final/style.css',
        'css/pangea_css_final/fa5.css',
       'css/pangea_css_final/switchery.min.css',
        'css/pangea_css_final/themify-icons.css',
        'libraries/toastr/toastr.min.css'

    ];
    public $js = [
       // 'js/pangea_js_final/bootstrap.min.js',
//        'js/pangea_js_final/Chart.min.js',
//        'js/pangea_js_final/chartist.min.js',
        'js/pangea_js_final/dashboard-data.js',
        'js/pangea_js_final/dataTables-data.js',
        'js/pangea_js_final/dropdown-bootstrap-extended.js',
        'js/pangea_js_final/init.js',
//        'js/pangea_js_final/jquery-jvectormap-2.0.2.min.js',
//        'js/pangea_js_final/jquery-jvectormap-us-aea-en.js',
//        'js/pangea_js_final/jquery-jvectormap-world-mill-en.js',
//        'js/pangea_js_final/jquery.counterup.min.js',
        'js/pangea_js_final/jquery.dataTables.min.js',
    //    'js/pangea_js_final/jquery.min.js',
//        'js/pangea_js_final/jquery.peity.min.js',
        'js/pangea_js_final/jquery.slimscroll.js',
//        'js/pangea_js_final/jquery.sparkline.min.js',
        'js/pangea_js_final/jquery.toast.min.js',
//        'js/pangea_js_final/jquery.waypoints.min.js',
       // 'js/pangea_js_final/mainmenu.js',
//        'js/pangea_js_final/morris.min.js',
        'js/pangea_js_final/owl.carousel.min.js',
        'js/pangea_js_final/peity-data.js',
        'js/pangea_js_final/raphael.min.js',
        // 'js/pangea_js_final/polling-quiz.js',
//        'js/pangea_js_final/switchery.min.js',
        'js/pangea_js_final/vectormap-data.js',
        'libraries/toastr/toastr.min.js',
    ];

    public $depends = [
          'yii\web\YiiAsset',
        'backend\assets\BackendAsset',
    ];
    public $jsOptions = array(
        //'position' => \yii\web\View::POS_HEAD
    );
    // public $publishOptions = ['forceCopy' => true];

}