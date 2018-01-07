<?php 
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\bootstrap\Alert;
use common\models\Storage;

if (($flash = Yii::$app->session->getFlash('emailverified')) || ($flash = Yii::$app->session->getFlash('coownerstatus')) || ($flash = Yii::$app->session->getFlash('signupsuccess')) || ($flash = Yii::$app->session->getFlash('forgotpassword')) || ($flash = Yii::$app->session->getFlash('resetpassword'))) {
    echo Alert::widget(['options' => ['class' => 'alert-success front-noti', 'id' => 'flashmodal'], 'body' => $flash]);
}
?>
<link href="https://fonts.googleapis.com/css?family=Titillium+Web:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Anton&amp;subset=latin-ext" rel="stylesheet"> 
<style>
    .front-noti{ position: fixed;top: 40%;left: 50%;z-index: 9999;margin-left: -245px;}
    .menutoglr{background-image: url(<?php echo yii::getAlias('@web'); ?>/themes/metronic/assets/layouts/layout/img/sidebar_toggler_icon_darkblue.png);display: block;cursor: pointer;opacity:0.7;filter: alpha(opacity=70); width: 24px;height: 24px;background-repeat: no-repeat;background-position: center center;border: 1px solid #fff;right: -7px;position: absolute; top:20px;}
	


	.menutoglr:hover{ opacity:1;}
    .ddd { margin: 0 auto;    width: 180px;    position: relative;}
    .hidelogo{display:none;    }
    .movenav{left:-30px; }
    @media (max-width:670px){
        .navbar-brand > img {
            display: block;
            padding-left: 11px;
        }
    }
</style>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container ">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
        <div class="ddd">
      <a class="navbar-brand" href="<?php echo yii::getAlias('@web'); ?>/../../"><img src="<?= $baseUrl;?>/images/Equippp-logo.png" alt="EquiPPP"></a>
        <div class="menu-toggler sidebar-toggler menutoglr"> </div>
    </div>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav top-nav sin-log-menu">
        <!--<li><a href="<?php echo Yii::$app->request->BaseUrl; ?>/site/comingsoon" class="txt-white"><i class="flaticon-cogwheel pad-rt10 size20"></i>How it Works </a></li>-->
          <?php if(Yii::$app->user->id){ ?>
		<li class="mobile-no-show"><a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl("../../dashboard");?>" class="txt-white home_page header_links">Dashboard</a></li>
         <?php } ?>
        <?php if(Yii::$app->user->id){ ?>
        <li><a href="<?=Yii::$app->getUrlManager()->getBaseUrl();?>/../../create-project" class="txt-white start_project header_links"><!--<i class="flaticon-upload pad-rt10 size20"></i>-->Create Project</a></li>
        <?php } else {?>
            <li><a href="#" class="txt-white start_project header_links"><!--<i class="flaticon-upload pad-rt10 size20"></i>-->Create Project</a></li>
        <?php } ?>
         <?php if(Yii::$app->user->id){ ?>
		<li class="mobile-no-show"><a href="<?=Yii::$app->getUrlManager()->getBaseUrl();?>/../../#HowItWorks" class="txt-white how_itworks header_links">How It Works</a></li>
         <?php } ?>    
         <?php if(Yii::$app->user->id){ ?>        
                <li class="username-drpdwn">
                    <ul class="nav navbar-nav pull-right">
                      
                        <li class="dropdown dropdown-user">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                             <?php  if(Yii::$app->user->id)
                              {?>
                                <?php  $sql = 'select user_image from user_profile where user_ref_id='. Yii::$app->user->id;
                                $image = yii::$app->db->createCommand($sql)->queryAll();                                
                                $keyname = 'uploads/profile_images/'.Yii::$app->user->identity->id.'/'.@$image[0]['user_image']; 
                                $s = new Storage();
                                $file = $s->download(Yii::getAlias('@bucket'),$keyname); 
                                if((@$image[0]['user_image']) && isset($file['@metadata']['effectiveUri'])) { 
                                   ?>
                                    <img alt="" class="img-circle small-avatar" src="<?php echo $file['@metadata']['effectiveUri']; ?> "/>
                                <?php } else { ?>
                                    <img alt="" class="img-circle small-avatar" src="<?php echo SITE_URL. Yii::getAlias('@web').'/images/avatar.png'?>"/>
                                <?php } ?>
                                <span class="username username-hide-on-mobile"><?php echo yii::$app->user->identity->username;?></span>
                                <?php }?>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">
                                <?php /*?><li>
                                    <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl("../../dashboard");?>">
                                        <i class="icon-grid"></i> Dashboard </a>
                                </li>
<?php */?>                                <li>
                                    <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl("../../profile");?>">
                                        <i class="icon-user"></i> My Profile </a>
                                </li>

                                <li>
                                    <form action="<?php echo Yii::$app->homeUrl;?>site/logout" method="post">
                                        <input type="hidden" name="_csrf" value="<?php echo Yii::$app->request->getCsrfToken(); ?>">
                                            <a href="javascript:void(0)" class="tp-nav-btn">
                                                <button type="submit" class="no-style">
                                                <i class="icon-power"></i> Log Out 
                                                </button>
                                            </a>
                                    </form>
                                </li>
                            </ul>
                        </li>
                        <!-- END USER LOGIN DROPDOWN -->
                        <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                        <!-- END QUICK SIDEBAR TOGGLER -->
                    </ul>
                    
                </li>
            <?php } ?>        
        <!--<li class="c-search-toggler-wrapper"> <a href="#" class="c-btn-icon c-search-toggler"><i class="glyphicon glyphicon-search"></i></a></li>-->
                <?php if(!Yii::$app->user->id) { ?>
			 <li><button type="button" class="btn btn-pink-tp pad-tb5 size16" data-toggle="modal" data-target="#w1">SIGNUP</button> </li>
            <li><button type="button" id="loginModal" class="btn btn-blue-tp pad-tb5 size16" data-toggle="modal" data-target="#w0">SIGNIN</button></li>
                <?php } ?>
            
      </ul>
      <div class="srch-toggler">
	 	 <input type="text" placeholder="Search Here" /> 
			<input type="button" class="btn btn-primary no-radius no-border" value="Go"/>
	  </div>
	   <!-- BEGIN: QUICK SEARCH 
                        <form class="c-quick-search" action="#">
                            <input type="text" name="query" placeholder="Type to search..." value="" class="form-control" autocomplete="off">
                            <span class="c-theme-link">&times;</span>
                        </form>
                        <!-- END: QUICK SEARCH -->
     
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<script>
$(function(){
    $("#loginModal").on('click', function(){
        $("div[class*='modal-backdrop']").detach();     
        $("#flashmodal").css('display','none');
    });
     $('.start_project').on('click',function(){
    
      <?php if(isset(Yii::$app->user->id) && Yii::$app->user->id)
      {?>
       window.location.href ="<?=Yii::$app->getUrlManager()->getBaseUrl();?>/../../create-project";     
    <?php }
    else{
        ?> 
          $('#loginModal').trigger('click');          
       <?php } ?> 
    });
    
    var controller = '<?php echo Yii::$app->controller->id; ?>';
    var action = '<?php echo Yii::$app->controller->action->id; ?>';

if(controller=='site' && action=='index'){
    $('.top-nav li a.home_page').addClass('active');
    }else if(controller=='projects' && action=='create'){
        $('.top-nav li a.start_project').addClass('active');
    }else if(controller=='site' && action=='comingsoon'){
        $('.top-nav li a.how_itworks').addClass('active');
    }
});
</script>

<script>
    $(".menutoglr").click(function(){
    $(".navbar-brand").toggleClass("hidelogo");
    $(".menu-toggler").toggleClass("movenav");
});
$(window).scroll(function() {
    if ($(this).scrollTop()  >= 500){  
        $('.header_hide .navbar-inverse').addClass("header-css");
      }
      else{
        $('.header_hide .navbar-inverse').removeClass("header-css");
      }
 });
 <?php if(Yii::$app->controller->action->id=='index' && Yii::$app->controller->id=='site'){?>
 $('body').addClass('header_hide');
 <?php }else{?>
	  $('body').removeClass('header_hide');
 <?php }?>
</script>