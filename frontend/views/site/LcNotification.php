<!-- for like and comment in dashboard -->
<?php 
$phpdateformat = Yii::getAlias('@phpdateformat');
foreach($result_array_notifications_lc as  $notification_lc)
{
if(isset($notification_lc['project_comment_id']) && $notification_lc['project_comment_id']!="")
{
    ?>
   <li class="loaded_for_lc"><a class="notice-links comment-art" href="#">

<!--<div class="avtar-images">
           <span class="photo">
             <?php //  if(($notification_lc['user_image']) && file_exists(Yii::getAlias('@upload') .'/frontend/web/uploads/profile_images/' .$notification_lc['user_ref_id']. '/' .$notification_lc['user_image'])) { ?>
     <img alt="" class="img-circle small-avatar notice-avatar" src="<?php // echo SITE_URL. Yii::getAlias('@web').'/uploads/profile_images/'.$notification_lc['user_ref_id']. '/'.$notification_lc['user_image'];?>"/>
                                <?php // }else { ?>
                                    <img alt="" class="img-circle small-avatar notice-avatar" src="<?php // echo SITE_URL. Yii::getAlias('@web').'/images/avatar.png'?>"/>
                                <?php // } ?>
    </span></div>-->
    <div class="notce-msg">
             <span class="subject sub-avatarmail"> 
                  <div class="lik-comlt"><span class="message notice-mages"><i class="fa fa-comments comments-notice" aria-hidden="true"></i></span></div>
                          <div class="msg-lkcmt">
                            <div> 
                           <span class="from from-name"><?php echo $notification_lc['fname']." ".$notification_lc['lname'];?></span>
                            <span class="time time-line"><?php echo date($phpdateformat,strtotime($notification_lc['created_date']));?></span></div>
                                                        
                            <span class=""><?php echo $notification_lc['project_title'];?> </span>
                            <div class="clearfix"></div>
                          <span class="message notice-mages"><?php echo substr($notification_lc['comments'],0,15);?>.... </span></div>
                     </span></div>

             </a></li>
    
<?php }

else
{
?>
   <li class="loaded_for_lc"><a class="notice-links art-commt" href="#">

<!--<div class="avtar-images">
           <span class="photo">
             <?php   if(($notification_lc['user_image']) && file_exists(Yii::getAlias('@upload') .'/frontend/web/uploads/profile_images/' .$notification_lc['user_ref_id']. '/' .$notification_lc['user_image'])) { ?>
     <img alt="" class="img-circle small-avatar notice-avatar" src="<?php // echo SITE_URL. Yii::getAlias('@web').'/uploads/profile_images/'.$notification_lc['created_by']. '/'.$notification_lc['user_image'];?>"/>
                                <?php  }else { ?>
                                    <img alt="" class="img-circle small-avatar notice-avatar" src="<?php echo SITE_URL. Yii::getAlias('@web').'/images/avatar.png'?>"/>
                                <?php } ?>
    </span></div>-->
    <div class="notce-msg">
                            <span class="subject sub-avatarmail"> 
                  <div class="lik-comlt"><span class="message notice-mages"><i class="fa fa-thumbs-up linked-notice" aria-hidden="true"></i></span></div>
                            <div class="msg-lkcmt">
                            <div>
                              <span class="from from-name"><?php echo $notification_lc['fname']." ".$notification_lc['lname'];?></span>
                               <span class="time time-line"><?php echo date($phpdateformat,strtotime($notification_lc['created_date']));?></span></div>
                               <span class=""><?php echo $notification_lc['project_title'];?></span>
                               <div class="clearfix"></div>
                                   </div>                     
                                 </span>
                                         </div>
                                                </a></li>
    
<?php
}
}
?>