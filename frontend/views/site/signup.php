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
<div class="site-signup-page">
		<div class="site-login">
                <div class="row">
       			 <div class="col-md-4 col-md-offset-4">
       				 <div class="panel panel-default">
                     <div class="panel-heading"><h3 class="panel-title"><?php echo Html::encode($this->title) ?></h3></div>
		<div class="panel-body">
    			<p class="login-page-cont">Please fill out the following fields to signup</p>
        
            <?php 
            
            $UsersList= (ArrayHelper::map($usertypemodel::find()->where('user_type_id in (3, 5)')->orderBy('user_type')->all(),'user_type_id','user_type'));    
            $MediaList= (ArrayHelper::map($mediatypemodel::find()->orderBy('media_agency_name')->all(),'media_agency_id','media_agency_name'));    
        
            
            $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?php echo $form->field($model, 'email',[
                    'inputOptions' => [                      
                        'class'=>'form-control signupemail'
                    ]]) ?>

                <?php echo $form->field($model, 'password',[
                    'inputOptions' => [                      
                        'class'=>'form-control signuppassword',
                        'type'=>'password'
                    ]])->passwordInput() ?>
            
                <?php echo $form->field($model, 'confirmpassword')->passwordInput() ?>
            
             
                <?php echo  $form->field($model, 'user_type_ref_id',[
                    'inputOptions' => [                      
                        'class'=>'form-control signupusertype'
                    ]])->dropDownList($UsersList, ['prompt'=>'Select User Type'],['class' => 'usertyperefid']) ; ?>
                        
                <?php echo  $form->field($model, 'media_agency_ref_id',[
                    'inputOptions' => [                      
                        'class'=>'form-control signupmediatype'
                    ]])->dropDownList($MediaList, ['prompt'=>'Select Media Agency']) ; ?>        
                <div class="button-container1">
                    <?php echo Html::submitButton('Signup', ['class' => 'btn btn-primary lg-pgbtn', 'name' => 'signup-button','id' => 'sign-up-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
        </div>
    </div>
    </div>
    
</div>
</div>
<script>
    $(document).ready( function ()
{
    $(".field-signupform-media_agency_ref_id").hide();
    $("select[class^=signupusertype]").change(function(){
        var usertype = $("select[class^=signupusertype]").val();
        if(usertype == 9){
            $(".field-signupform-media_agency_ref_id").show();
        }else{
            $(".field-signupform-media_agency_ref_id").hide();
        }
});
$("#sign-up-button").on("click", function (event) {
    var usrtype = $(".signupusertype").val();
    var media =  $(".signupmediatype").val();
    if(usrtype == 9 && media==''){
        $(".field-signupform-media_agency_ref_id").append('<label for="signupform-media_agency_ref_id" class="error mediaagency">Media Agency cannot be blank</label>');
                $("label.addmedia-error").remove();
                $(".mediaagency").addClass('addmedia-error');
                $(".field-signupform-media_agency_ref_id").addClass('mediaerror');
                return false;
    }else{
                $(".mediaagency").removeClass('addmedia-error');
                $(".field-signupform-media_agency_ref_id").removeClass('mediaerror');
                return true;
            }
        
});
});
    </script>