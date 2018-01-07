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
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/themes/custom';
    public $css = [        
        'css/main.css',
        'css/fancybox.css',
        'fonts/flaticon.css',
        'plugins/font-awesome/css/font-awesome.min.css',        
        'css/plugins.css',        
        './../../../../common/css/global.css',
		'./../../themes/metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css',
	
    ];
    public $js = [ 
     
    ];
    public $depends = [
//        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',	
		
    ];
}
