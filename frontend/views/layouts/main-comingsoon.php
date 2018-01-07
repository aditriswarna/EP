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

<link rel="stylesheet" type="text/css" href="<?php echo yii::getAlias('@web'); ?>/themes/custom/css/bootstrap.css" media="screen" />
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400" rel="stylesheet">

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
	<link rel="icon" href="<?=Yii::getAlias('@web').'/images/favicon.ico';?>" type="image/x-icon" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>    
<script src="<?php echo Yii::getAlias('@web'); ?>/js/google_map_new/js/notify.min.js"></script>
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    <?php  echo $bundle->renderCss(); ?>
    <?php $this->head() ?>

</head>
<body>
<!--
 style='background-image: url("<?php //echo yii::getAlias('@web'); ?>/themes/custom/images/Background-image.jpg"); background-size: cover; background-repeat: no-repeat;background-attachment: fixed;    background-position: center;'
-->
<?php $this->beginBody() ?>  
<?php require_once("_header-comingsoon.php"); ?>



<div class="container">
        <div class="company-logo"><img src="<?php echo yii::getAlias('@web'); ?>/themes/custom/images/equippp-coming-soon-logo.png" alt="EquiPPP" /></div>
	<div class="content-section">
		<h4>TOGETHER TO BETTER SOCIETY</h4>
		<p><strong>EquiPPP</strong> is a collaborative platform that vitalizes crowd participation in <strong>Public-Private Projects</strong> and connects <strong>Individuals and Organizations</strong> with the <strong>Government</strong> to <strong>Initiate</strong> and <strong>Participate</strong> together in socially relevant projects. </p>
        </div>
</div>
<div class="coming-soon">Coming Soon</div>
<div class="copyright">&copy; copyrights EquiPPP 2017</div>


<style type="text/css">

body, html{width:100%; height: 100%;}
html { 
	background:  url(<?php echo yii::getAlias('@web'); ?>/themes/custom/images/Background_Image.jpg) no-repeat center center fixed; 
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover;}

body{padding:0px; margin: 0px; background: none;}



.container{ width: 340px; margin: 0 auto; text-align: center; color: rgba(0, 34, 76, 0.71); font-family:'Klavika-light'; overflow: hidden;}
.container .company-logo{ margin-top: 48px; margin-bottom: 35px;}
.container h4{font-family:'Klavika-regular-italic'; font-size: 15px; color: rgba(0, 34, 76, 0.81); margin-bottom: 26px;}
.container p{font-size: 15px; line-height: 18px; text-align: justify;
    -moz-text-align-last: center;
    text-align-last: center;}
.container p strong{font-family:'Klavika-regular'; font-weight: normal; color: rgba(0, 34, 76, 0.81);}

.coming-soon{position: absolute;
bottom: 0;
left: 0;
color: #e9e9e9;
text-transform: uppercase;
font-family: 'Open Sans', sans-serif;
font-weight: 300;
font-size: 24px;
padding: 20px;
letter-spacing: 2px;
}

.copyright {
    width: 100%;
    text-align: center;
    position: absolute;
    bottom: 5px;
    color: #777777;
    font-size: 12px;
}

/* Large desktops and laptops */
@media (min-width: 1200px) {

}

/* Landscape tablets and medium desktops */
@media (min-width: 992px) and (max-width: 1199px) {

}

/* Portrait tablets and small desktops */
@media (min-width: 768px) and (max-width: 991px) {

}

/* Landscape phones and portrait tablets */
@media (max-width: 767px) {
    
    .container{ width: 60%; padding: 0 20px; color: rgb(0, 34, 76);}
    .container p strong{ color: rgb(1, 27, 59);}
    .container .company-logo{ width: 100px; margin: 20px auto;}
    .container .company-logo img{ width: 100%;}
    .container p{ font-size: 14px; padding: 10px; background: rgba(255, 255, 255, 0.3);}
    .container h4{ margin-bottom: 6px;}
    .coming-soon{ width: 100%; text-align: center;}

}

/* Portrait phones and smaller */
@media (max-width: 480px) {

    .container{ width: 100%; padding: 0 20px; color: rgb(0, 34, 76);}
    .container p strong{ color: rgb(1, 27, 59);}
    .container .company-logo{ width: 100px; margin: 20px auto;}
    .container .company-logo img{ width: 100%;}
    .container p{ font-size: 14px; padding: 10px; background: rgba(255, 255, 255, 0.3);}
    .container h4{ margin-bottom: 6px;}
    .coming-soon{ width: 100%; text-align: center;}
}


@font-face {
    font-family:'Klavika-light';
    src: url('<?php echo yii::getAlias('@web'); ?>/themes/custom/fonts/a-Light.eot');
	src: url('<?php echo yii::getAlias('@web'); ?>/themes/custom/fonts/a-Light.eot?#iefix') format('embedded-opentype'),
		url('<?php echo yii::getAlias('@web'); ?>/themes/custom/fonts/a-Light.woff2') format('woff2'),
		url('<?php echo yii::getAlias('@web'); ?>/themes/custom/fonts/a-Light.woff') format('woff'),
		url('<?php echo yii::getAlias('@web'); ?>/themes/custom/fonts/a-Light.ttf') format('truetype'),
		url('<?php echo yii::getAlias('@web'); ?>/themes/custom/fonts/a-Light.otf') format('opentype'),
		url('<?php echo yii::getAlias('@web'); ?>/themes/custom/fonts/a-Light.svg#a Light') format('svg');
    font-weight: 300;
    font-style: normal;
    font-stretch: normal;
    unicode-range: U+0020-00FE;
}
@font-face {
    font-family:'Klavika-regular-italic';
    src: url('<?php echo yii::getAlias('@web'); ?>/themes/custom/fonts/a-RegularItalic.eot');
	src: url('<?php echo yii::getAlias('@web'); ?>/themes/custom/fonts/a-RegularItalic.eot?#iefix') format('embedded-opentype'),
		url('<?php echo yii::getAlias('@web'); ?>/themes/custom/fonts/a-RegularItalic.woff2') format('woff2'),
		url('<?php echo yii::getAlias('@web'); ?>/themes/custom/fonts/a-RegularItalic.woff') format('woff'),
		url('<?php echo yii::getAlias('@web'); ?>/themes/custom/fonts/a-RegularItalic.ttf') format('truetype'),
		url('<?php echo yii::getAlias('@web'); ?>/themes/custom/fonts/a-RegularItalic.otf') format('opentype'),
		url('<?php echo yii::getAlias('@web'); ?>/themes/custom/fonts/a-RegularItalic.svg#a RegularItalic') format('svg');
    font-weight: 400;
    font-style: italic;
    font-stretch: normal;
    unicode-range: U+0020-00FE;
}
@font-face {
    font-family:'Klavika-regular';
    src: url('<?php echo yii::getAlias('@web'); ?>/themes/custom/fonts/a-Regular.eot');
	src: url('<?php echo yii::getAlias('@web'); ?>/themes/custom/fonts/a-Regular.eot?#iefix') format('embedded-opentype'),
		url('<?php echo yii::getAlias('@web'); ?>/themes/custom/fonts/a-Regular.woff2') format('woff2'),
		url('<?php echo yii::getAlias('@web'); ?>/themes/custom/fonts/a-Regular.woff') format('woff'),
		url('<?php echo yii::getAlias('@web'); ?>/themes/custom/fonts/a-Regular.ttf') format('truetype'),
		url('<?php echo yii::getAlias('@web'); ?>/themes/custom/fonts/a-Regular.otf') format('opentype'),
		url('<?php echo yii::getAlias('@web'); ?>/themes/custom/fonts/a-Regular.svg#a Regular') format('svg');
    font-weight: 400;
    font-style: normal;
    font-stretch: normal;
    unicode-range: U+0020-00FE;
}

</style>
