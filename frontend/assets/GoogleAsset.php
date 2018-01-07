<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class GoogleAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/themes/custom';
    public $css = [
       /* 'css/bootstrap.min.css',
        'css/components.css', 
        'css/hover.css', */
        'css/main.css',
        'fonts/flaticon.css',
        'plugins/font-awesome/css/font-awesome.min.css',
      //  'css/plugins.css',
      //  'css/fancybox.css',
        './../../../../common/css/global.css',
		'./../../themes/metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css',
		//'./../../themes/metronic/assets/global/css/components.min.css', 
    ];
    public $js = [  
       /* 'js/components.js',
           'js/app.js',
           'js/fancybox.js', */
    ];
    public $depends = [
      //  'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
		
		
    ];
}
