<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<style>
   
    .fancybox-skin{ margin:0 auto; padding:0 !important; width: 430px !important}
    .fancybox-skin .fancybox-inner{ width:430px !important;}
    .fancybox-inner .project-participation-form.participation-border { margin: 20px auto; padding: 0 20px; float: left; width: 100%;}
    .fancybox-inner select { border-color:#d8d8d8; background: rgba(150, 139, 139, 0.09);}
    .fancybox-inner select:focus { border-color: #ffab13;}
    .prj-popup-btn{color: #9d9d9d;font-family: headerfont;}
    .button-container1 button.prj-popup-btn:hover { background: none !important;   border-color: #ffa500 !important; color:#ffa500}
    .fancybox-inner .project-participation-form.participation-border  .button-container1{text-align: center;    margin: 25px auto 10px;}
    .button-container1 button.prj-popup-btn:focus {background: #ffa500 !important;border-color: #ffa500 !important; -o-transition: .3s ease; -webkit-transition: .3s ease; -ms-transition: .3s ease; transition: .3s ease;  -moz-transition: .3s ease; color:#fff !important;}
    .fancybox-skin .fancybox-close{ background:url(<?php echo yii::getAlias('@web');?>/themes/custom/css/closeimage.jpg) 0 0 no-repeat;top: 14px; right: 10px;}
    .fancybox-inner .project-participation-form.participation-border input{    border-color:#d8d8d8; background:#fff;}
    .fancybox-inner .project-participation-form.participation-border input:focus {border-color: #ffa500; -o-transition: .3s ease; -webkit-transition: .3s ease; -ms-transition: .3s ease; transition: .3s ease;  -moz-transition: .3s ease;}
	 .button-container1 button { margin: 0 auto; padding: 5px 25px; background:none !important; border: 1px solid #e3e3e3 !important;font-size: 20px;font-weight: 600;border-radius: 20px !important;}
	 .mail-sharpopup{}
	 
	 
	    @media (min-width: 1280px) {
 /* .fancybox-opened { width: 90% !important; z-index: 999999 !important;}*/
   .fancybox-opened { width: 100% !important; left:0 !important; right:0 !important; padding:0 !important;  z-index: 999999 !important; }
   
    } 
	
	   @media (max-width: 1024px) {
 /* .fancybox-opened { width: 90% !important; z-index: 999999 !important;}*/
   .fancybox-opened { width: 100% !important; left:0 !important; z-index: 999999 !important; }
   
    } 
	
	
	 @media (max-width: 500px) {
	 .fancybox-skin, .fancybox-skin .fancybox-inner{width:100% !important;}
	 }
	  @media (max-width: 680px) {
 /* .fancybox-opened { width: 90% !important; z-index: 999999 !important;}*/
   .fancybox-opened { width: 100% !important; left:0 !important; z-index: 999999 !important; }
   
    } 
	
		
			

    /*input#projectparticipation-amount:hover { background: none;    border-color: #ffa500; }*/
    
	 
</style>

<div id="email_form" class="project-participation-form participation-border mail-sh-bd">
    <div class="col-xs-12 col-sm-12 mail-fromsht"> 
        <?php $form = ActiveForm::begin(['id' => 'email_share_form']); ?>

        <?php  echo $form->field($model, 'email')->textInput(['class' => ' form-control  ','placeholder'=>'Email','title'=>'You can add multiple emails with comma separated list']) ?> 
        <label id="emailform-email-error" class="error" style="display:none;">This field is required.</label>
        
        <?php  echo $form->field($model, 'message')->textarea(['class' => ' form-control  ']) ?>
        <label id="emailform-message-error" class="error" style="display:none;">This field is required.</label>

        <div class="button-container1">            
        <?php echo Html::submitButton('Send', ['class' => 'btn btn-success prj-popup-btn']); ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>



<script>
           
    var pattern = /^(([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5}){1,25})+([,;.](([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5}){1,25})+)*$/;
    
    $(function(){
        
        $('.btn-success').on('click', function(e){
            e.preventDefault();
            var email = $.trim($('#emailform-email').val());
            var message = $.trim($('#emailform-message').val());
            
            if(email == '' && message != ''){                
                $('#emailform-message-error').hide();
                $('#emailform-email-error').show();                
                return false;               
            }
            else if(email != '' && !pattern.test(email) && message != ''){               
                $('#emailform-message-error').hide();
                $('#emailform-email-error').text('Please enter valid email');
                $('#emailform-email-error').show();
                return false;                
            }
            else if(email != '' && !pattern.test(email) && message == ''){               
                $('#emailform-message-error').hide();
                $('#emailform-email-error').text('Please enter valid email');
                $('#emailform-email-error').show();
                $('#emailform-message-error').show();
                return false;                
            }
            else if(email != '' && pattern.test(email) && message == ''){               
                $('#emailform-email-error').hide();  
                $('#emailform-message-error').show();
                return false;                
            }
            else if(email == '' && message == ''){                 
                $('#emailform-email-error').show();  
                $('#emailform-message-error').show();
                return false;                
            }
            else if(email != '' && message != '' && pattern.test(email)){
                $.ajax({
                    url: $('#email_share_form').attr('action'),
                    type: "post",
                    data: $('#email_share_form').serialize(),
                    success: function (html) {
                        $.fancybox.close();   
                        $('body div .notifyDiv').html('You have successfully sent the mail to recipients');
                        $('body div .notifyDiv').slideDown('slow',function () {
                            $(this).delay(2000).fadeOut(1000);
                        });     
                        $.notify(html, "success"); 
                    }
                });
               return false;
            }
            return false;
        });
    });

</script>