<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    .addmedia-error{
        display:block !important;
    }
	.modal-content{ border-radius:2px 2px 6px 6px; -webkit-border-radius:2px 2px 6px 6px; -moz-border-radius:2px 2px 6px 6px; -o-border-radius:2px 2px 6px 6px; border:1px solid #00224c;}
    .modal-backdrop.in {  z-index: 99 !important;}
	.modal-header {		display: none;	}
	.mdal-header{ margin:0 -20px;   padding:15px; background:#00224c; text-align:center; text-transform:uppercase;
}
.modal-header button.close, .modal-header button.close:hover, .modal-header button.close:focus{ color:#fff; text-shadow:none;}
.modal-content {width: 430px ;   margin: 0 auto;}
 .checkbox label{  font-size: 15px; font-weight:normal;    font-family: headerfont;}
.type-us label.control-label{ display:none;}
div#w1 .modal-body { min-height:380px !important;}
.sp-sing{ margin-bottom:30px;}
.input-container.type-us p.help-block.help-block-error { top: -21px; right: 19px;}
div#w1 .modal-body.mdl-bdy { margin-top: 45px;}
div#w1 .mrg-btm1{ margin-bottom:33px;}
div#w1  select{font-size: 16px; width: 63%; margin: 0 auto;}
div#w1  select#signupform-user_type_ref_id:focus{border-color:#ffab13;}
div#w1 .has-success .form-control{    border-color:#ffab13}
div#w1 .modal-body.mdl-bdy.modalheight{    min-height:426px !important;}
#sign-up-error-msg{    margin: 0 auto;    padding: 0;    text-align: center;    line-height: 3;    color: red;}
.signup-form-popup .help-block-error{    display:none;}
div#w1 .signup-form-popup label.error{ position: absolute;    text-align: right;  width: 100%;  left: 0;  color: #F44336 !important; transform: initial; top: -36px; font-size: 11px !important;font-weight: normal !important; transform: translate(0%, 0%) !important;}
div#w1 .signup-form-popup .usertype-error-msg label.error{ top: -36px;    padding-right: 0px;}

 @media (min-width: 451px and max-width: 670px) {
 div#w1 .signup-form-popup label.error{top:-20px;font-size:11px !important;}
 div#w0 .form-group.field-username label.error, div#w0 .form-group.field-password label.error{top:-20px;font-size:11px !important;}
 }

   @media only screen and (max-width: 450px) {
	.modal-content {width:300px !important; }
.card .button-container1{ margin:10px auto;}
div#w1 .signup-form-popup .usertype-error-msg label.error{font-size:11px !important;}

	}
	@media only screen and (max-width:330px) {
	div#w1 .signup-form-popup label.error{text-align: left; top: 16px;}
	div#w1 .signup-form-popup .usertype-error-msg label.error{ top:17px;}
	}
	@media only screen and (max-width:330px) {
	.modal-content{ margin-left:0px;}
	#sign-up-error-msg{font-size: 10px;}
	}
</style>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<div class="site-signup">
<div class="mdal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
    <span class="title-1">Create An Account</span>
       <!-- <p>Please fill in below form to create an account with us</p>-->
</div>
   <div class="modal-body mdl-bdy signup-form-popup">
                <div class="card">
        <div class="col-xs-12 signup-form">
            <?php 
            
            $UsersList= (ArrayHelper::map($usertypemodel::find()->where('user_type_id in (3, 5)')->orderBy('user_type')->all(),'user_type_id','user_type')); 
            $MediaList= (ArrayHelper::map($mediatypemodel::find()->orderBy('media_agency_name')->all(),'media_agency_id','media_agency_name'));    
            $form = ActiveForm::begin(['id' => 'form-sign-up','method' => 'post']); ?>
            <div class="input-container mrg-btm">
            
                <?= $form->field($model, 'email',[
                    'inputOptions' => [
                        'class'=>'form-control input-lg c-square usremail',
                        'type'=>'text',
						'placeholder'=>'Username'
                       
                    ],
                ])->label(false); ?>
                 <!--<label for="Username">Username</label>-->
              </div>
                
            <div class="input-container mrg-btm">
                <?= $form->field($model, 'password', [
                    'inputOptions' => [
                       
                        'class'=>'form-control input-lg c-square userpassword',
                        'type'=>'password',
						'placeholder'=>'Password'
                    ],
                ])->label(false); ?> 
                 <!--<label for="Password">Password</label>-->
                    </div>
            
            <div class="input-container mrg-btm1">
                <?= $form->field($model, 'confirmpassword', [
                    'inputOptions' => [
                       
                        'class'=>'form-control input-lg c-square usercofrm-password',
                        'type'=>'password',
						'placeholder'=>'Confirm Password'
                    ],
                ])->label(false); ?> 
               <!--<label for="Password">Confirm Password</label>-->
            </div>
            
            <div class="input-container type-us">
                <?=  $form->field($model, 'user_type_ref_id')->dropDownList($UsersList, ['prompt'=>'Select User Type', 'class'=>'form-control usertyperefid']) ; ?>
            </div>
            
            <div class="input-container type-us media_agency">
                <?=  $form->field($model, 'media_agency_ref_id')->dropDownList($MediaList, ['prompt'=>'Select Media Agency', 'class'=>'form-control mediaagencyid']) ; ?>
            </div>
            
            <div class="button-container1 signup-form-submit">
                <?= Html::submitButton('<span class="sp-sing">Signup</span>', ['class' => 'btn btn-primary', 'name' => 'signup-button', 'id' => 'signup-button']) ?>
            </div>
<div id="sign-up-error-msg"></div>
            <?php ActiveForm::end(); ?>
        </div>
    </div></div>
</div>

<script>

$(".close").on("click", function (event) {
	//alert('hi');
	if($( "#signupform-user_type_ref_id" ).hasClass("error")){
		
		$( "#signupform-user_type_ref_id" ).removeClass("error");
	}
	
	//return false;
	 });
 $("#signupModal").on("click", function (event) {
	 if($( "#signupform-user_type_ref_id" ).hasClass("error")){
		
		$( "#signupform-user_type_ref_id" ).removeClass("error");
	}
	 $('#form-sign-up')[0].reset();
		var validator = $( "#form-sign-up" ).validate();
validator.resetForm();
 $('#sign-up-error-msg').html('');
  //$( "#form-sign-up" ).siblings('label').removeClass("error");
$( "#form-sign-up" ).find(".error").removeClass("error");
//$( "div" ).find(".error").removeClass("error");
 //form.find(".error").removeClass("error");

 });
$('.field-signupform-user_type_ref_id').addClass('usertype-error-msg');

    var surl = '<?php echo Yii::$app->urlManager->createAbsoluteUrl('site/validatesignupuser'); ?>';
    $(function () {
	
	
        $("#form-sign-up").validate({
			focusCleanup: true,
            rules: {
                'SignupForm[email]': {
                    required: true,
                    email: true,
                },
                'SignupForm[password]': {
                    required: true,
                    minlength:6,
                },
                'SignupForm[confirmpassword]': {
                    required: true,
                    equalTo: "#signupform-password"
                },
                'SignupForm[user_type_ref_id]': {
                    required: true,
                },
                 'SignupForm[media_agency_ref_id]': {
                     required: true, 
                 },
            },
            messages: {
                'SignupForm[email]': {
                    required: "Username cannot be blank",
                    email: "Please enter a valid email address",
                },
                'SignupForm[password]': {
                    required: "Password cannot be blank",
                    minlength: "Password should contain atleast 6 characters",
                },
                'SignupForm[confirmpassword]': {
                    required: "Confirm Password cannot be blank",
                    equalTo:"Passwords do not match"
                },
                'SignupForm[user_type_ref_id]': {
                    required: "User Type cannot be blank",
                },
                
                'SignupForm[media_agency_ref_id]': {
                    required:"Media agency cannot be blank",
                    
                },
            },
            
        });
        $("#signup-button").on("click", function (event) {
			//alert('hi');
			
            $('#sign-up-error-msg').empty();
            event.preventDefault();
            var usertype = $("select[id^=signupform-user_type_ref_id]").val();
         /*   if(usertype == 9){
            var mediaagency = $("select[id^=signupform-media_agency_ref_id]").val();
            if(mediaagency == ''){
                $(".field-signupform-media_agency_ref_id").append('<label for="signupform-media_agency_ref_id" class="error mediaagency">Media Agency cannot be blank</label>');
                $(".mediaagency").addClass('addmedia-error');
                $(".field-signupform-media_agency_ref_id").addClass('mediaerror');
            }else{
                $(".mediaagency").removeClass('addmedia-error');
                $(".field-signupform-media_agency_ref_id").removeClass('mediaerror');
            }
        }*/
            if ($("#form-sign-up").valid()) {
                
              var uemail=$('.usremail').val();
              var password=$('.userpassword').val();
              var usertype=$('.usertyperefid').val();
              var reference_url=$('#signup_reference_url').val();
              var mediatype=$('.mediaagencyid').val();
               $.ajax({
                    url: surl,
                    type: "post",
                    data: {uemail:uemail, password:password, usertype:usertype, reference_url:reference_url, mediatype:mediatype},
                    success: function (data) {
                        $('.signup-form-popup').addClass('modalheight');                        
                        jsonParsedObject = JSON.parse(data);                        
                        if(jsonParsedObject.redirect){
                            window.location.href=jsonParsedObject.redirect;                             
                        }
                        else if(jsonParsedObject.msg){
                           $('#sign-up-error-msg').html(jsonParsedObject.msg);  
                        }else{
                           $('#sign-up-error-msg').html("The email Address has already been taken");
                        }   
                        
                    }
                });
            }
        });
        $(".field-signupform-media_agency_ref_id").css('display','none');
    $("select[id^=signupform-user_type_ref_id]").change(function(){
        var usertype = $(this).val();
        if(usertype == 9){
            $(".field-signupform-media_agency_ref_id").css('display','block');
        }else{
            $(".field-signupform-media_agency_ref_id").css('display','none');
        }
});
    });

    

</script>
