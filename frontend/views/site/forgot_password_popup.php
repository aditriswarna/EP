<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Forgot Password?';
?>

<style>
div#w2 .modal-body{ min-height: 180px !important;}

@media (max-width: 480px){
#request-password-reset-form label.error {left:0px !important;}
}
/*
#request-password-reset-form label.error {
    top: -30px;
    text-align: right;
    width: 100%;
    transform: none;
    font-size: 12px;
    color: #F44336;
}
*/

</style>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<div class="site-request-password-reset">
<div class="mdal-header frgt-pwd-close">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
    <span class="title-1"><?= Html::encode($this->title) ?></span>
</div>
    

    <div class="modal-body mdl-bdy mdby login-form-popup">
        <div class="card">
        	<p class="new-psd-mail">Please fill out your email. New password will be sent there.</p>
                <div class="input-container bard-bm">
                <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form',
                    'method' => 'post'
                    ]); ?>

                     <?= $form->field($model, 'email',['inputOptions' => ['id'=>'email', 'name'=>'email']])->textInput(['autofocus' => false]) ?>
                     <label id="error-pwd-msg" class="error"></label>
                </div>
                
                <div class="button-container1">
                    <?= Html::submitButton('<span>Send</span>', ['class' => 'btn btn-primary', 'id' => 'forgot-pwd-submit']) ?>
                </div>
			<div id="error-msg"></div>	
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<script>
var furl = '<?php echo Yii::$app->urlManager->createAbsoluteUrl('site/validate-forgot-password'); ?>';
$(function(){
    
    $("#btnClose").on('click', function(){
        $("div[class*='modal-backdrop']").detach();       
    });
    $(".frgt-pwd-close").on('click', function(){
    $('#request-password-reset-form')[0].reset();
    $('.error').empty();
    });
    $("#request-password-reset-form").validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                }
            },
            messages: {
                email: {
                    required: "Email cannot be blank",
                    email: "Please enter a valid email address",
                }
            }
        });
        $("#forgot-pwd-submit").on("click", function (event) {
        $("#request-password-reset-form .help-block").hide();
        $('#error-pwd-msg').css("display","none");
           $('#error-pwd-msg').empty();
            event.preventDefault();
            if ($("#request-password-reset-form").valid()) {
              var email=$('#email').val();
               $.ajax({
                    url: furl,
                    type: "post",
                    data: {email:email},
                    success: function (data) {
                        $('.login-form-popup').addClass('modalheight');
                        jsonParsedObject = JSON.parse(data);                        
                        if(jsonParsedObject.redirect){
                            window.location.href=jsonParsedObject.redirect;                             
                        }
                        else if(jsonParsedObject.msg){
                           $('#error-pwd-msg').html(jsonParsedObject.msg);
                           $('#error-pwd-msg').css("display","block");
                           $("#request-password-reset-form .help-block").hide();
                        }else{
                           $('#error-pwd-msg').html("There is no user with such email.");
                           $('#error-pwd-msg').css("display","block");
                           $("#request-password-reset-form .help-block").hide();
                        } 
                        
                    }
                });
            }
        });
        
        $('#email').on('keyup',function(){
             $("#request-password-reset-form .help-block").hide();
            $('#error-pwd-msg').css("display","none");
             $('#error-pwd-msg').empty();
        });
});
</script>