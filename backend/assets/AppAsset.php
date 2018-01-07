<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/themes/metronic';   
   // public $sourcePath = '@bower/metronic/views';
    public $css = [
        'style.css',              
        'assets/global/plugins/simple-line-icons/simple-line-icons.min.css',
        'assets/global/plugins/font-awesome/css/font-awesome.min.css', 
        'assets/global/plugins/bootstrap/css/bootstrap.min.css',
        'assets/global/plugins/uniform/css/uniform.default.css',
        'assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css',   
        'assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css',  
        'assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css', 
       /* 'assets/global/plugins/morris/morris.css',
        'assets/global/plugins/fullcalendar/fullcalendar.min.css',  
        'assets/global/plugins/jqvmap/jqvmap/jqvmap.css', */
        'assets/global/css/plugins.min.css',
        'assets/layouts/layout/css/custom.min.css', 
        'assets/global/css/components.min.css', 
        'assets/layouts/layout/css/layout.min.css',
        'assets/layouts/layout/css/themes/darkblue.min.css', 
        'assets/layouts/layout/css/fancybox.css',
        './../../../../common/css/global.css',
    
       
        
    ];
    public $js = [        
       // 'assets/global/plugins/bootstrap/js/bootstrap.min.js',
        'assets/global/plugins/js.cookie.min.js',
        'assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js',
        'assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
        'assets/global/plugins/jquery.blockui.min.js',
        'assets/global/plugins/uniform/jquery.uniform.min.js',
        'assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js',
        'assets/global/plugins/moment.min.js',
        'assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js',
        /* 'assets/global/plugins/morris/morris.min.js',
        'assets/global/plugins/morris/raphael-min.js',
        'assets/global/plugins/counterup/jquery.waypoints.min.js',
        'assets/global/plugins/counterup/jquery.counterup.min.js',
        'assets/global/plugins/amcharts/amcharts/amcharts.js',
        'assets/global/plugins/amcharts/amcharts/serial.js',
        'assets/global/plugins/amcharts/amcharts/pie.js',
        'assets/global/plugins/amcharts/amcharts/radar.js',
        'assets/global/plugins/amcharts/amcharts/themes/light.js',
        'assets/global/plugins/amcharts/amcharts/themes/patterns.js',
        'assets/global/plugins/amcharts/amcharts/themes/chalk.js',
        'assets/global/plugins/amcharts/ammap/ammap.js',
        'assets/global/plugins/amcharts/ammap/maps/js/worldLow.js',
        'assets/global/plugins/amcharts/amstockcharts/amstock.js',
        'assets/global/plugins/fullcalendar/fullcalendar.min.js',
        'assets/global/plugins/flot/jquery.flot.min.js',
        'assets/global/plugins/flot/jquery.flot.resize.min.js',
        'assets/global/plugins/flot/jquery.flot.categories.min.js',
        'assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js',
        'assets/global/plugins/jquery.sparkline.min.js',
        'assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js',
        'assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js',
        'assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js',
        'assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js',
        'assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js',
        'assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js',
        'assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js', */
        'assets/global/scripts/app.min.js',        
        'assets/layouts/layout/scripts/layout.min.js',
        'assets/layouts/layout/scripts/demo.min.js', 
        'assets/layouts/global/scripts/quick-sidebar.min.js', 
        'assets/layouts/global/scripts/fancybox.js',
        'assets/global/scripts/jquery.MultiFile.js',
        'assets/global/plugins/ckeditor/ckeditor.js',
        //'assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
        

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
