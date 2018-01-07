<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
?>
<div class="profile-content">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light">
                <div class="portlet-title tabbable-line">
                    <div class="caption caption-md">
                        <i class="icon-globe theme-font hide"></i>
                        <span class="caption-subject font-blue-madison bold uppercase">Profile Account</span>
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1_1" data-toggle="tab">Personal Info</a>
                        </li>
                        <li>
                            <a href="#tab_1_2" data-toggle="tab">Change Avatar</a>
                        </li>
                        <li>
                            <a href="#tab_1_3" data-toggle="tab">Change Password</a>
                        </li>
                    </ul>
                </div>
                <div class="portlet-body">
                    <div class="tab-content">
                        <!-- PERSONAL INFO TAB -->
                        <div class="tab-pane active" id="tab_1_1">
                            <?php echo Yii::$app->controller->renderPartial('/profile/profile', ['userformmodel' => $userformmodel, 'userdata' => $userdata, 'model' => $model, 'userdatamodel' => $userdatamodel]); ?>
                        </div>
                        <!-- END PERSONAL INFO TAB -->
                        <!-- CHANGE AVATAR TAB -->
                        <div class="tab-pane" id="tab_1_2">
                            <?php echo  Yii::$app->controller->renderPartial('/profile/changeProfileImage', ['imagemodel' => $imagemodel]); ?>
                        </div>
                        <!-- END CHANGE AVATAR TAB -->
                        <!-- CHANGE PASSWORD TAB -->
                        <div class="tab-pane" id="tab_1_3">
                            <?php echo  Yii::$app->controller->renderPartial('/profile/ResetProfilePassword',['resetpasswordmodel' => $resetpasswordmodel]); ?>
                       
                        </div>
                        <!-- END CHANGE PASSWORD TAB -->
                        <!-- PRIVACY SETTINGS TAB -->
                        
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>