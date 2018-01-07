<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
$id =  Yii::$app->getRequest()->getQueryParam('id');
if(isset($id)){
$admin_location_res = app\models\AdminLocation::find()->where(['user_ref_id' => $id])->one();
@$admin_location_id = $admin_location_res->location_ref_id; 
$model->location = @$admin_location_id;
}
    $selectedOptionArray = Array();
    $assigned_user_types = \backend\models\AdminAssignedUserTypes::find()->where(['user_ref_id'=>$id])->all();
    if(isset($assigned_user_types))
        {
            // snipe product list and build the option array            
            $x = 0;
            foreach ($assigned_user_types as $val)
            {
                $selectedOptionArray[$val['user_type_ref_id']] = array('selected '=>'selected');
                $x++;
            }
        }       
?>

<div class="site-signup participation-border fl-left">
     <div class="row">
        <div class="col-xs-12 col-sm-6">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>    
    <?= $form->field($model, 'email')->input('email',['readonly' => !$model->isNewRecord]) ?>    
    <?php // echo $form->field($model, 'location')->dropDownList($items, array('prompt'=>'Select Location')) ?>    
    <?= $form->field($model, 'user_type_ref_id[]')->listBox($types,['multiple'=>'true','size'=>4,'options'=>$selectedOptionArray, 'prompt'=>'Select User Type','onBlur' => 'checkErrors()']) ?>
        <div id="errorUserType" class="help-block" style="display: none; margin: -17px 49px 15px; color: #e73d4a;">User type cannot be blank</div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'onClick' => 'return checkErrors()']) ?>
    </div>

    <?php ActiveForm::end(); ?>
        </div>
     </div>
</div>

<script>
    var selectedData = '<?php echo count($selectedOptionArray); ?>';
    $(function(){
      //  console.log(selectedData);
        if(selectedData == 0 || selectedData == ''){
            $('#user-user_type_ref_id option[value = ""]').attr('selected', true); 
        }
       
    });
    function checkErrors() {
        $('#errorUserType').css('display', 'none');
        if($("#user-user_type_ref_id").val() == ''){
             $('#errorUserType').css('display', 'block');
             return false;
        }
        return true;
    }
</script>