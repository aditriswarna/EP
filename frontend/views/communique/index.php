<?php
    use yii\helpers\Url;
use yii\widgets\DetailView;
$this->registerJsFile(Yii::getAlias('@web/themes/custom/plugins/owl-carousel/assets/js/jquery-1.9.1.min.js'),['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile(Yii::getAlias('@web/themes/metronic/assets/global/plugins/ckeditor/ckeditor.js'),['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile(Yii::getAlias('@web/themes/metronic/assets/global/plugins/chosen/chosen.jquery.js'),['position' => \yii\web\View::POS_HEAD]);
$this->registerCssFile(Yii::getAlias('@web/themes/metronic/assets/global/plugins/chosen/chosen.css'),['position' => \yii\web\View::POS_HEAD]);
?>
<div class="mail-container">
<div class="mail-communique">
<div id="inbox"></div>
<div id="sent"></div>
</div>
</div>
<script>
    $(this).scrollTop(0);
    var mailviewurl = '<?php echo Yii::$app->urlManager->createAbsoluteUrl('communique/mail-view'); ?>';
    var inboxurl = '<?php echo Yii::$app->urlManager->createAbsoluteUrl('communique/inbox-mails'); ?>';
    var senturl = '<?php echo Yii::$app->urlManager->createAbsoluteUrl('communique/sent-mails'); ?>';
    var newmsgurl = '<?php echo Yii::$app->urlManager->createAbsoluteUrl('communique/new-message'); ?>';
    
    function openmailbox(mailid,tbox){
        $(this).scrollTop(0);
        if(tbox=='inbox'){
        location.hash = 'inbox/'+mailid;
    }else if(tbox=='sent'){
        location.hash = 'sent/'+mailid;
    }
    }
    function opencontent(mailid){
        $(this).scrollTop(0);
           var mailid = mailid;
            $('#inbox').empty();
            $('#sent').empty();
            $.ajax({
                    url: mailviewurl,
                    type: "post",
                    dataType: "html",
                    data: {mailid:mailid},
                    success: function (data) {
                        $('#inbox').html(data);
                        
                    }
                });
       }
       function inboxmails(){
           $(this).scrollTop(0);
           $('#inbox').empty();
           $('#sent').empty();
          // $('#newmessage').empty();
           $.ajax({
                    url: inboxurl,
                    type: "post",
                    dataType: "html",
                    success: function (data) {
                        $('#inbox').html(data);
                        
                    }
                });
            }
       function sentmailsbox(){
           $(this).scrollTop(0);
           $('#inbox').empty();
           $('#sent').empty();
           //$('#newmessage').empty();
           $.ajax({
                    url: senturl,
                    type: "post",
                    dataType: "html",
                    success: function (data) {
                        $('#sent').html(data);
                        
                    }
                });
           
       }
       function newmessage(){
           $(this).scrollTop(0);
           $('#inbox').empty();
           $('#sent').empty();
           //$('#newmessage').empty();
           $.ajax({
                    url: newmsgurl,
                    type: "post",
                    success: function (data) {
                        $('#newmessage').append(data);
                        
                    }
                });
           
       }
     
    $(function(){ 
        changecontent();
    $(window).bind('hashchange', function(e) {
        $(this).scrollTop(0);
    var what_to_do = window.location.hash;    
   if (what_to_do=="#inbox"){
       inboxmails();
   }else if(what_to_do=="#sent"){
       sentmailsbox();
   }else if(what_to_do=="#new-message"){
       newmessage();
   }
   if (what_to_do.indexOf("sent/") >= 0){
            var msgid = what_to_do.split('sent/')[1];
            opencontent(msgid);
        }else if (what_to_do.indexOf("inbox/") >= 0){
            var msgid = what_to_do.split('inbox/')[1];
            opencontent(msgid);
        }
    });
});
function changecontent(){
    $(this).scrollTop(0);
    var what_to_do = window.location.hash;    
   if (what_to_do=="#inbox"){
       inboxmails();
   }else if(what_to_do=="#sent"){
       sentmailsbox();
   }else if(what_to_do=="#new-message"){
       newmessage();
   }
   if (what_to_do.indexOf("sent/") >= 0){
            var msgid = what_to_do.split('sent/')[1];
            opencontent(msgid);
        }else if (what_to_do.indexOf("inbox/") >= 0){
            var msgid = what_to_do.split('inbox/')[1];
            opencontent(msgid);
        }
}
    
    </script>

<style>
    .unread{
     font-weight:bold;   
}
    </style>
