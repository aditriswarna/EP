<?php 
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\bootstrap\Alert;
use common\models\Storage;

if (($flash = Yii::$app->session->getFlash('emailverified')) || ($flash = Yii::$app->session->getFlash('coownerstatus'))  || ($flash = Yii::$app->session->getFlash('signupsuccess')) || ($flash = Yii::$app->session->getFlash('forgotpassword')) || ($flash = Yii::$app->session->getFlash('resetpassword'))) {
    echo Alert::widget(['options' => ['class' => 'alert-success front-noti', 'id' => 'flashmodal'], 'body' => $flash]);
}
?>   
<style>
    .modal-backdrop.in {
        z-index: 9999 !important;
    }
</style>
<header class="eqp-head">
      <nav class="navbar navbar-inverse navbar-fixed-top">
      
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?=Yii::$app->getUrlManager()->getBaseUrl();?>/../../"><img src="<?=Yii::getAlias('@web').'/images/coming-soon/equippp-logo.png'?>" alt="EquiPPP" /></a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav top-nav sin-log-menu">   
            <?php if(!Yii::$app->user->id) { ?>
                <li><a href="#Projects">Projects</a></li>
                <li><a href="#HowItWorks">How it Works</a></li>
                <li> <a type="button" id="signupModal" data-toggle="modal" data-target="#w1">SIGN UP</a> </li>
                <li> <a type="button" id="loginModal" data-toggle="modal" data-target="#w0">SIGN IN</a></li>
            <?php } ?>
           <?php if(Yii::$app->user->id){ ?>
              <!--<li class="mobile-no-show"><a href="<?php //echo yii::getAlias('@web'); ?>/../../" class="txt-white home_page header_links">Home</a></li>-->
               <li class="mobile-no-show"><a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl("../../dashboard");?>" class="txt-white header_links">Dashboard</a></li>
              <li><a href="<?=Yii::$app->getUrlManager()->getBaseUrl();?>/../../create-project" class="txt-white header_links"><!--<i class="flaticon-upload pad-rt10 size20"></i>-->Create Project</a></li>
        <?php } ?>
      
         <?php if(Yii::$app->user->id){ ?>
		<li class="mobile-no-show"><a href="#HowItWorks" class="txt-white how_itworks header_links">How It Works</a></li>
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
                        
                    </ul>
                    
                </li>
            <?php } ?>

<!--            <li><a href="<?php // echo Yii::$app->getUrlManager()->getBaseUrl();?>/../../site/coming-soon">sign Up</a></li>-->
          </ul>
        </div><!--/.nav-collapse -->
      
    </nav>
      </header>
            <?php 
            Modal::begin();
            $model = new common\models\LoginForm();
            echo $this->render('/site/login_popup', ['model' => $model]);
            Modal::end();
            ?>
            
            <!--<button type="button" class="btn btn-pink-tp pad-tb5 size16" data-toggle="modal" data-target="#w1"><i class="flaticon-people size17"></i> SIGNUP</button>  -->    
            <?php 
            Modal::begin([     
                            'closeButton' => [
                              'label' => 'X',
                              'class' => 'close',
                            ],
                            //'size' => 'modal-lg',
                        ]);
            $model = new frontend\models\SignupForm();
            $usertypemodel = new common\models\UserType();
            $mediatypemodel = new common\models\MediaAgencies();
            echo $this->render('/site/signup_popup', ['model' => $model,'usertypemodel'=>$usertypemodel,'mediatypemodel' => $mediatypemodel]);
            Modal::end();
            ?>
<!--<button type="button" data-toggle="modal" data-target="#signup-form"  class="btn btn-pink-tp pad-tb5"><i class="flaticon-people size17"></i>SIGNUP</button>   -->
            
            <?php 
            Modal::begin([                                
                           'closeButton' => [
                               'label' => 'X',
                               'class' => 'close',
                           ],
                           //'size' => 'modal-lg',
                       ]);
           $model = new \frontend\models\PasswordResetRequestForm();                
           echo $this->render('/site/forgot_password_popup', ['model' => $model]);
           Modal::end();
           ?>
<script>
$(function(){
$("#signupModal").on('click', function(){
 $("form#form-sign-up").append('<div class=\"rurl\"><input type=\"hidden\" id=\"signup_reference_url\" name=\"signup_reference_url\" value=\"referrer" /></div>');
    });

    $("#loginModal").on('click', function(){
        $("div[class*='modal-backdrop']").detach();     
        $("#flashmodal").css('display','none');        
        $("div.rurl").remove();
        $("form#login-form").append('<div class=\"rurl\"><input type=\"hidden\" id=\"reference_url\" name=\"reference_url\" value=\"referrer" /></div>');
    });
     $('.start_project').on('click',function(e){   
         e.preventDefault();
         // $('#loginModal').trigger('click');  
          $("div.rurl").remove();      
          $("form#login-form").append('<div class=\"rurl\"><input type=\"hidden\" id=\"reference_url\" name=\"reference_url\" value=\"<?=Yii::$app->getUrlManager()->getBaseUrl();?>/../../create-project\" /></div>');   
       
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
//for mobile menu click

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
 
 //for mobile
 $(function()
{
   if (window.matchMedia("(max-width: 740px)").matches) 
     {
    $('li .dropdown-user').on('click',function()
{
    
  $('dropdown-menu-default').css('display','block'); 
});
    
        }  
});
</script>