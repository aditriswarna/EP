<?php foreach($dash_notifications as $notifcations)  
{
  ?>
  <li class="loaded"><a class="notice-links" href="#">

<div class="avtar-images">
           <span class="photo">
             <?php   if(($notifcations['user_image'])) { ?>
     <img alt="" class="img-circle small-avatar notice-avatar" src="https://s3.ap-south-1.amazonaws.com/<?php echo Yii::getAlias('@bucket').'/uploads/profile_images/'.$notifcations['created_by']. '/'.$notifcations['user_image'];?>"/>
                                <?php  }else { ?>
                                    <img alt="" class="img-circle small-avatar notice-avatar" src="<?php echo SITE_URL. Yii::getAlias('@web').'/images/avatar.png'?>"/>
                                <?php } ?>
    </span></div>
    <div class="notce-msg">
                                                    <span class="subject sub-avatarmail"> 
                                                        <span class="from from-name"><?php echo $notifcations['fname']." ".$notifcations['lname'];?></span>
                                                        <span class="time time-line"><?php echo $notifcations['created_date'];?></span>
                                                    </span>
                                                    <span class="message notice-mages"><?php echo $notifcations['subject'];?></span></div>
                                                </a></li>
 

<?php }?>