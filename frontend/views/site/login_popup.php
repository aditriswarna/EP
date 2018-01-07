<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\bootstrap\Alert;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
 
?>
<style type="text/css">
.modal-content{ border-radius:2px 2px 6px 6px; -webkit-border-radius:2px 2px 6px 6px; -moz-border-radius:2px 2px 6px 6px; -o-border-radius:2px 2px 6px 6px; border:1px solid #00224c;}
   .modal-backdrop.in {  z-index:99 !important; background-color:#fff;   }
	.modal-header {		display: none;	}
	.mdal-header{ margin:0 -20px;   padding:15px; background:#00224c; text-align:center; text-transform:uppercase;
}
.modal-header button.close, .modal-header button.close:hover, .modal-header button.close:focus{ color:#fff; text-shadow:none;}


.modal-content {    width: 450px ; margin: 0 auto;z-index:9999999;overflow:auto;}
 .checkbox label{line-height:normal; font-size: 15px; font-weight:normal;    font-family: headerfont;}
 input#loginform-rememberme{     margin: 3px 0 0 -17px;}
 .checkbox { margin: 0 auto;     line-height: normal;}
 .card .footer{ margin:0 auto; line-height: normal;}

.card .input-container .bar {    top: 50px;}
.mdal-header{ line-height:normal;}
.modal-body {    padding: 0 20px;}
.mdl-bdy{ margin:20px auto;}
p.help-block.help-block-error { position: absolute; right: 0;top: -46px;font-size: 10px !important;
    font-weight: normal !important;}
div#w0 .modal-body { min-height: 325px;}
div#w0 .modal-body.mdl-bdy {    margin-top: 45px;}
 input:-webkit-autofill{-webkit-box-shadow: inset 0 0 0px 9999px white; -moz-box-shadow: inset 0 0 0px 9999px white; -ms-box-shadow: inset 0 0 0px 9999px white; -o-box-shadow: inset 0 0 0px 9999px white;}
 input:-webkit-autofill:focus{-webkit-box-shadow: inset 0 0 0px 9999px white; -moz-box-shadow: inset 0 0 0px 9999px white; -ms-box-shadow: inset 0 0 0px 9999px white; -o-box-shadow: inset 0 0 0px 9999px white;}
  input:-webkit-autofill:active{ border-bottom:#ffa500 solid 1px; -webkit-box-shadow: inset 0 0 0px 9999px white; -moz-box-shadow: inset 0 0 0px 9999px white; -ms-box-shadow: inset 0 0 0px 9999px white; -o-box-shadow: inset 0 0 0px 9999px white;}
   div#w0 .card .footer{  padding-top: 20px;}
 div#w0 .checkbox{      padding-bottom: 6px;}
 div#w0 div#error-msg{ line-height:28px;}
  div#w0 .modal-body.mdl-bdy.modalheight{    min-height: 325px !important;}
  div#w0 .form-group.field-username label.error, div#w0 .form-group.field-password label.error{ position: absolute; text-align: right; width: 100%; left: 0; color: #F44336;
    transform: initial; top: -36px; font-size: 12px !important;  font-weight: normal !important;transform: translate(0%, 0%) !important;}
div#w0 div#error-msg{    color: #ff4600;text-align: center;    margin-top: 10px;}

   @media only screen and (max-width: 670px) {
   
   body.modal-content {width:300px ;z-index:9999999;overflow-x:hidden;overflow-y:auto;}
   .projects-slides .container{padding:0px;}
   .projects-slides .container .row{margin:0px;}
   .projects-slides .container .col-md-12.col-sm-12{padding:0px;}
    body.modal-open {overflow: hidden !important;position:fixed !important;-webkit-overflow-scrolling:�touch; }
	body.noScroll {overflow: hidden;}
	
   }
   

   @media only screen and (max-width: 450px) {
	.modal-content {width:300px ;z-index:9999999;overflow:auto;}
    .card .button-container1{ margin:10px auto;}
    label.error{margin: 10px 0px;clear: both;}
	#w0 form-group{padding:10px 0px !important;}
	div#w0 .mrg-btm{margin-bottom: 40px !important;}
	.site-signup .mdal-header, .login-form-close{/*margin: 0 -10px !important;*/padding: 5px 15px  !important;}
	div#w0 .modal-body{min-height: 305px !important;}

}

	}
	@media only screen and (max-width:330px) {
	.modal-content{ margin-left:0px;}
	}
	@media only screen and (max-width:350px) {
	div#w0 .form-group.field-username label.error, div#w0 .form-group.field-password label.error{text-align: left; top: 16px;}
	}
	
	
	



</style>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<div class="mdal-header login-form-close">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <span class="title-1">Sign in</span>
            </div>
<div class="modal-body mdl-bdy login-form-popup">
                <div class="card">
 <?php  $form = ActiveForm::begin(['id' => 'login-form',
            'method' => 'post'
        ]); 
 ?>
                    
    <div class="input-container mrg-btm">
            <!--<label for="Username">Username</label>-->
            <?php echo $form->field($model, 'username', [
            'inputOptions' => [
                'class'=>'form-control input-lg c-square',
                'type'=>'text',
				'placeholder'=>'Username',
                'id'=>'username',
                'name'=>'username',
            ],
        ])->label(false); ?>
        
    </div>

    <div class="input-container">
    	<!--<label for="Password">Password</label>-->
        
        <?php echo $form->field($model, 'password', [
            'inputOptions' => [
                'class'=>'form-control input-lg c-square',
                'type'=>'password',
				'placeholder'=>'Password',
                'id'=>'password',
                'name'=>'password',
            ],
        ])->label(false); ?>
        
    </div> 
                    
    <div class="button-container1">                     
                   
<?php echo Html::submitButton('<span>Log In</span>', ['class' => 'btn btn-primary', 'name' => 'login-button', 'id' => 'login-button']) ?> 
    <div class="form-group">      
              <?php echo $form->field($model, 'rememberMe')->checkbox() ?>  
    </div>

 </div>
 <div class="footer">             
        <a class="forgot-pwd" id="resetLink" data-toggle="modal" data-target="#w2">Forgot Your Password ?</a>
    </div> 
<div id="error-msg"></div>

                              
<?php ActiveForm::end(); ?>
</div>
</div>


<!--<div class="modal-footer c-no-border">
<span class="c-text-account">Don't Have An Account Yet ?</span>
<a href="javascript:;" data-toggle="modal" data-target="#signup-form" data-dismiss="modal" class="btn c-btn-dark-1 btn c-btn-uppercase c-btn-bold c-btn-slim c-btn-border-2x c-btn-square c-btn-signup">Signup!</a>
</div>
 -->
 
 <script>
  $("#loginModal").on("click", function (event) {
	 // alert($( "#form-sign-up #signupform-user_type_ref_id" ).html());
	  if($( "#form-sign-up #signupform-user_type_ref_id" ).hasClass("error")){
		
		$( "#signupform-user_type_ref_id" ).removeClass("error");
	}
	 $('#login-form')[0].reset();
		var validator = $( "#login-form" ).validate();
validator.resetForm();
//$( "#form-sign-up" ).find(".error").removeClass("error");
 // $( "#login-form" ).siblings('label').removeClass("error");
//$( "#login-form" ).find(".error").removeClass("error");
 $('#error-msg').html('');

 });
$(function(){    
    $("#resetLink").on('click', function(){
       $('#w0').modal('hide');
       $('#w2').css('style','display:block');
       $('#email').val('');
       $('.form-control').next('.error').css('display','none');
       $('#error-pwd-msg').css('display','none');
    });
});

    var url = '<?php echo Yii::$app->urlManager->createAbsoluteUrl('site/validateuser'); ?>';
    $(function () {

$(".login-form-close").on('click', function(){
    $('#login-form')[0].reset();
    $('.error').empty();
    });
        $("#login-form").validate({
            rules: {
                username: {
                    required: true,
                    email: true,
                },
                password: {
                    required: true,
                },
            },
            messages: {
                username: {
                    required: "Username cannot be blank",
                    email: "Please enter a valid email address",
                },
                password: {
                    required: "Password cannot be blank",
                },
            }
        });
        $("#login-button").on("click", function (event) {
            $('#error-msg').empty();
            event.preventDefault();
            if ($("#login-form").valid()) {
              var uname=$('#username').val();
              var password=$('#password').val();
              var reference_url=$('#reference_url').val();
               $.ajax({
                    url: url,
                    type: "post",
                    data: {uname:uname, password:password, reference_url:reference_url},
                    success: function (data) {
                        $('.login-form-popup').addClass('modalheight');
                        jsonParsedObject = JSON.parse(data);                        
                        if(jsonParsedObject.redirect){
                            window.location.href=jsonParsedObject.redirect;                             
                        }
                        else if(jsonParsedObject.msg){
                           $('#error-msg').html(jsonParsedObject.msg);  
                        }else{
                           $('#error-msg').html("Incorrect username or password");
                        }        
                    }
                });
            }
        });
    });
</script>