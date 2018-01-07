<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use frontend\assets\CustomAsset;

//AppAsset::register($this);
$asset = ((Yii::$app->controller->action->id=='dynamic-map')? frontend\assets\GoogleAsset::register($this):frontend\assets\AppAsset::register($this));
$baseUrl = $asset->baseUrl;

?>
<?php
$bundle = new \DotsUnited\BundleFu\Bundle();
$bundle
    // Set the document root
    ->setDocRoot(Yii::getAlias('@webroot').'/themes/custom')

    // Set the css cache path (relative to the document root)
    ->setCssCachePath('css/cache')

    // Set the javascript cache path (relative to the document root)
    ->setJsCachePath('js/cache');
?>
<?php $bundle->start(); ?>     
    <link rel="stylesheet" type="text/css" href="css/components.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="css/hover.css" media="screen" />   
    <link rel="stylesheet" type="text/css" href="plugins/cubeportfolio/css/cubeportfolio.min.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="plugins/owl-carousel/owl.carousel.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="plugins/owl-carousel/owl.theme.css" media="screen" />   
    <!--<link rel="stylesheet" type="text/css" href="css/fancybox.css" media="screen" />-->    
    
    <script type="text/javascript" src="plugins/cubeportfolio/js/jquery.cubeportfolio.min.js"></script>
    <script type="text/javascript" src="plugins/owl-carousel/owl.carousel.min.js"></script>
    <script type="text/javascript" src="js/components.js"></script>
    <script type="text/javascript" src="js/masonry-portfolio.js"></script>
    <script type="text/javascript" src="js/app.js"></script>
      
<?php $bundle->end(); ?>
    
<?php $this->beginPage() ?>

<!DOCTYPE html>
 <html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:fb="http://ogp.me/ns/fb#" lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <?php echo Html::csrfMetaTags() ?>
    <title><?php echo Html::encode($this->title);    //Html::encode("EquiPPP - " . (Yii::$app->controller->action->id)=='dynamic-new' ? 'Search Projects' : $this->title);?></title>
	<link rel="icon" href="<?=Yii::getAlias('@web').'/images/favicon.png';?>" type="image/x-icon" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>    
    <script src="<?php // echo Yii::getAlias('@web').'/js/bootstrap.min.js'?>"></script>
     <script src="<?php echo Yii::getAlias('@web').'/js/animate.min.js'?>"></script>
     <script src="<?php echo Yii::getAlias('@web'); ?>/js/google_map_new/js/notify.min.js"></script>
    <!-- Bootstrap -->
    <link href="<?=Yii::getAlias('@web').'/css/bootstrap.min.css'?>" rel="stylesheet">
    <link href="<?=Yii::getAlias('@web').'/css/main-style.css'?>" rel="stylesheet">
    <link href="<?=Yii::getAlias('@web').'/css/fonts.css'?>" rel="stylesheet">
    <link href="<?=Yii::getAlias('@web').'/css/animate.css'?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Anton&amp;subset=latin-ext" rel="stylesheet">  
    <?php // echo $bundle->renderCss(); ?>
        
    <!--  Load twitter SDK for JavaScript  -->
    <?php if(Yii::$app->controller->action->id=='dynamic-new'){?>   
        <script>
           var siteUrl = '<?php echo Yii::getAlias('@web'); ?>';
            window.twttr = (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0],
            t = window.twttr || {};
            if (d.getElementById(id)) return t;
            js = d.createElement(s);
            js.id = id;
            js.src = siteUrl+"/themes/custom/js/tsdk.js";
            fjs.parentNode.insertBefore(js, fjs);

            t._e = [];
            t.ready = function(f) {
            t._e.push(f);
            };

            return t;
            }(document, "script", "twitter-wjs"));

        </script>
     <?php } ?>

    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>  
   
<?php if(Yii::$app->controller->action->id != 'index') 
		{ 
			require_once("_header.php");
		} else { 
			require_once("_header-construction.php"); 
	} ?>
     <?= $content ?>
<!--    <div class="wrap home-toggle">

        <div class="page-container">
            <?php //require_once ('_content.php');?>

           
        </div>
        </div>-->

        <?php
           if(Yii::$app->controller->action->id != 'dynamic-new') { 
                require_once('_footer.php');
            }
        ?>
 <?php // echo $bundle->renderJs(); ?>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
<script>

$('.dropdown').hover(
       function(){ $(this).addClass('open') },
       function(){ $(this).removeClass('open') }
)

</script>
