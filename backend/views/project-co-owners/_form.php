<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model frontend\models\ProjectCoOwners */
/* @var $form yii\widgets\ActiveForm */
?>
<script>
            
    $(function () {
	$('.submitbtn').click(function(){
	var username = $('#projectcoowners-username').val();
 if(username == ''){
 $('#errorUsername').css('display', 'block');
 return false;
 }else{
 $('#errorUsername').css('display', 'none');
  return true;
 }
	});
	});

 </script>
<div class="project-co-owners-form participation-border fl-left">
<div class="col-xs-12 col-sm-6">

    <?php $form = ActiveForm::begin(['options' => ['id' => 'createProjectCoOwner' ,'name' => 'createProjectCoOwner']]); ?>

    <?php echo $form->field($model, 'project_ref_id')->hiddenInput(['value' => $project->project_id])->label(False); ?>
    <?php echo '<h4 class="project-usnme">Project Name: <span>' . $project->project_title.'</span></h4>'; ?>

    <?php //echo $form->field($model, 'user_ref_id')->textInput() ?>
    <?php //echo $form->field($model, 'user_ref_id')->dropDownList($usernames) ?>
    
    <div class="form-group">
    
    <label class="control-label">Username: </label>
    <style type="text/css">
        .ui-autocomplete-input {
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
            border: 1px solid #c2cad8;
        }
    </style>
     
    <?php echo AutoComplete::widget([
                'model' => $model,
                'attribute' => 'username',
                'name'=>'user_name',
                'clientOptions' => [
                'source' => $users,
                //'minLength'=>'2', 
                //'autoFill'=>true,
                'class' => 'form-control',
                'options' => array(
                    'minLength'=>3,
                    'autoFill'=>false,
                    'focus'=> 'js:function( event, ui ) {
                        $( "#projects-username" ).val( ui.item.name );
                        return false;
                    }',
                    'htmlOptions'=>array('class'=>'form-control', 'style' => 'width: 100%', 'autocomplete'=>'off'),
                    'select'=>'js:function( event, ui ) {
                        $("#'.Html::getInputId($model,'attribute_id').'")
                        .val(ui.item.id);
                        return false;
                    }'
                 ),
                'select' => new JsExpression("function( event, ui ) {
                    $('#user_ref_id').val(ui.item.id);
                }"),
				'change'=>new JsExpression("function( event, ui ) {
                    if (ui.item==null){
                        $('#projectcoowners-username').val('');
                        $('#projectcoowners-username').focus();
                        $('#errorUsername').text('Select valid username');
                        $('#errorUsername').css('display','block');
                    }else{
                        $('#errorUsername').css('display','none');
                    }
                }")],
            ]);
    ?>
    <div id="errorUsername" class="help-block" style="display: none; margin: 0px 0px 15px; color: #e73d4a;">Username cannot be blank</div>
	
    <?php echo Html::activeHiddenInput($model, 'user_ref_id', ['id' => 'user_ref_id'])?>

    </div>

   <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success submitbtn' : 'btn btn-primary submitbtn']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div></div>

