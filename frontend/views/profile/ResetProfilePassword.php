<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\UserProfile */
/* @var $form ActiveForm */
//print_r($userdata->user_type_ref_id); exit;
?>
<script language="javascript">
    function checkValidation() {
        if($('#resetprofilepasswordform-password').val().trim() != '') {
            if($('#resetprofilepasswordform-password').val() == $('#resetprofilepasswordform-changepassword').val()) {
                $('#errMsg').html("Old password and change password should not be equal");
                return false;
            } else {
                return true;
            }
        }
    }
</script>
    
<div class="col-lg-5">
    <div class="has-error"><div class="help-block" id="errMsg"></div></div>
    <?php $form = ActiveForm::begin(['action' => Yii::$app->urlManager->createUrl('/profile/reset-profile-password')],['options' => ['enctype' => 'multipart/form-data']]); ?>
<?php  

        echo $form->field($resetpasswordmodel, 'password')->passwordInput();
        echo $form->field($resetpasswordmodel, 'changepassword')->passwordInput();
        echo $form->field($resetpasswordmodel, 'reenterpassword')->passwordInput();


?>
       
    
        <div class="form-group">
            <?php echo Html::submitButton('Submit', ['class' => 'btn btn-primary', 'onClick' => 'return checkValidation();']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- profile -->
  