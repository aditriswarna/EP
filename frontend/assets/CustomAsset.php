<?php 
namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\YiiAsset;

class CustomAsset extends AssetBundle{
    public $basePath = '@webroot';
    public $baseUrl = '@web/themes/metronic';
    public $css = [
        'style.css',
        'assets/global/plugins/font-awesome/css/font-awesome.min.css',       
        'assets/global/plugins/simple-line-icons/simple-line-icons.min.css',
        'assets/global/plugins/bootstrap/css/bootstrap.min.css',
	// 'assets/global/plugins/ckeditor/skins/moono/editor.css',
        'assets/global/plugins/uniform/css/uniform.default.css',
        'assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css',   
        'assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css',    
        'assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css',    
     //   'assets/global/plugins/morris/morris.css',
      //  'assets/global/plugins/fullcalendar/fullcalendar.min.css',  
     //   'assets/global/plugins/jqvmap/jqvmap/jqvmap.css',
        'assets/global/css/components.min.css',       
        'assets/global/css/plugins.min.css',
        'assets/layouts/layout/css/layout.min.css',
        'assets/layouts/layout/css/themes/darkblue.min.css',       
        'assets/layouts/layout/css/custom.min.css',
        'assets/layouts/layout/css/fancybox.css',
        './../../../../common/css/global.css'
      ];
    public $js = [       
        'assets/global/plugins/bootstrap/js/bootstrap.min.js',
        'assets/global/plugins/js.cookie.min.js',
        'assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js',
        'assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
        'assets/global/plugins/jquery.blockui.min.js',
        'assets/global/plugins/uniform/jquery.uniform.min.js',
        'assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js',
        'assets/global/plugins/moment.min.js',
        'assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js',
       // 'assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
        'assets/global/scripts/app.min.js',      
        'assets/layouts/layout/scripts/layout.min.js',      
        'assets/layouts/global/scripts/quick-sidebar.min.js',      
        'assets/global/scripts/jquery.MultiFile.js',
        'assets/global/scripts/fancybox.js',
        'assets/global/plugins/ckeditor/ckeditor.js'
        
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}

?>