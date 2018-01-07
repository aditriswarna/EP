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
    
<?php $this->beginPage() ?>

<!DOCTYPE html>
 <html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:fb="http://ogp.me/ns/fb#" lang="<?= Yii::$app->language ?>">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="icon" href="images/favicon.png" type="image/x-icon" />
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>EquiPPP</title>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>  
      <script src="<?=Yii::getAlias('@web').'/js/bootstrap.min.js'?>"></script>
     <script src="<?=Yii::getAlias('@web').'/js/animate.min.js'?>"></script>
     <script src="<?php echo Yii::getAlias('@web'); ?>/js/google_map_new/js/notify.min.js"></script>
    <!-- Bootstrap -->
    <link href="<?=Yii::getAlias('@web').'/css/bootstrap.min.css'?>" rel="stylesheet">
    <link href="<?=Yii::getAlias('@web').'/css/main-style.css'?>" rel="stylesheet">
      <link href="<?=Yii::getAlias('@web').'/css/fonts.css'?>" rel="stylesheet">
      <link href="<?=Yii::getAlias('@web').'/css/animate.css'?>" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Titillium+Web:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Anton&amp;subset=latin-ext" rel="stylesheet"> 
  </head>
<body>
<?php $this->beginBody() ?> 
    <?php require_once("_header-construction.php"); ?>
            <?= $content ?> 
    <!-- <div class="wrap home-toggle">
        <div class="page-container">           
            <?php //require_once ('_content.php');?>
           
        </div>
        </div> -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

