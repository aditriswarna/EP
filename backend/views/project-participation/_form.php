<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model frontend\models\ProjectParticipation */
/* @var $form yii\widgets\ActiveForm */
?>
<style type="text/css">
    .ui-autocomplete { height: 200px; overflow-y: auto; overflow-x: hidden;}
</style>

<div class="project-participation-form participation-border">
    <div class="col-xs-12 col-sm-6"> 
<?php $form = ActiveForm::begin(); ?>
        <div class="form-group">

            <label class="control-label">Username </label>
            <style type="text/css">
                .ui-autocomplete-input {
                    width: 100%;
                    padding: 5px;
                    margin-bottom: 10px;
                    border: 1px solid #c2cad8;
                }
				.participation-border{ float:none; width:auto;     display: -webkit-box;}
				.form-group{ position:relative;}
				
.form-group.field-projectparticipation-investment_type.has-error .help-block, .form-group.field-projectparticipation-equity_type.has-error .help-block, .form-group.field-projectparticipation-participation_type.required.has-error .help-block, .form-group.field-projectparticipation-amount.has-error .help-block{ bottom:-30px}
.form-group.field-user_ref_id .help-block{margin-top: -30px;}
            </style>

            <?php
            echo AutoComplete::widget([
                'model' => $model,
                'attribute' => 'username',
                'name' => 'user_name',
                'clientOptions' => [
                    'source' => $users,
                    //'minLength'=>'2', 
                    //'autoFill'=>true,
                    'class' => 'form-control username_drop',
                    'options' => array(
                        'minLength' => 3,
                        'autoFill' => false,
                        'focus' => 'js:function( event, ui ) {
                        $( "#projects-username" ).val( ui.item.name );
                        return false;
                    }',
                        'htmlOptions' => array('class' => 'form-control', 'style' => 'width: 100%', 'autocomplete' => 'off'),
                        'select' => 'js:function( event, ui ) {
                        $("#' . Html::getInputId($model, 'attribute_id') . '")
                        .val(ui.item.id);
                        return false;
                    }'
                    ),
                    'select' => new JsExpression("function( event, ui ) {
                    $('#user_ref_id').val(ui.item.id);
                }"),
				'change'=>new JsExpression("function( event, ui ) {
                    if (ui.item==null){
					$('input#user_ref_id').next('div').hide();
                        $('#projectparticipation-username').val('');
                        $('#projectparticipation-username').focus();
                        $('#errorUsername').text('Select valid username');
                        $('#errorUsername').css('display','block');
                    }else{
					$('input#user_ref_id').next('div').show();
                        $('#errorUsername').css('display','none');
                    }
                }")],
            ]);
            ?>
        </div>
		<div id="errorUsername" class="help-block" style="display: none; margin: -25px 50px -15px; color: #e73d4a; ">Username cannot be blank</div>
        <!--<?= "Project Name: <h4>".$project->project_title.'</h4>'; ?>-->
        <?= $form->field($model, 'project_ref_id')->hiddenInput(['value' => $project->project_id])->label(false) ?>

        <?= $form->field($model, 'user_ref_id')->hiddenInput(['id' => 'user_ref_id'])->label(false) ?>

        <?= $form->field($model, 'participation_type')->dropDownList([ 'Invest' => 'Cash', 'Support' => 'Kind', ], ['prompt'=>'--Select--']) ?>

        <?= $form->field($model, 'investment_type')->dropDownList([ 'Equity' => 'Equity', 'Grant' => 'Grant', ], ['prompt'=>'--Select--']) ?>

        <?= $form->field($model, 'equity_type')->dropDownList([ 'Principal_Protection' => 'Principal Protection', 'Interest_Earning' => 'Interest Earning', ], ['prompt'=>'--Select--']) ?>

        <?= $form->field($model, 'amount')->textInput() ?>

        <?= $form->field($model, 'interest_rate')->textInput() ?>

        <?php //echo $form->field($model, 'created_by')->textInput() ?>

        <?php //echo $form->field($model, 'created_date')->textInput() ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Participate' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
    $(function()
    {
        $('.field-projectparticipation-equity_type').attr('style', 'display: none');
        $('.field-projectparticipation-amount').attr('style', 'display: none');
        $('.field-projectparticipation-interest_rate').attr('style', 'display: none');
        $('.field-projectparticipation-investment_type').attr('style', 'display: none');
        $('#projectparticipation-participation_type').on('change', function() {
            if($('#projectparticipation-participation_type').val() == "Support") {
                $('.field-projectparticipation-investment_type').attr('style', 'display: none');
                $('.field-projectparticipation-equity_type').attr('style', 'display: none');
                $('.field-projectparticipation-amount').attr('style', 'display: none');
                $('.field-projectparticipation-interest_rate').attr('style', 'display: none');
            
                $('#projectparticipation-investment_type').val(0);
                $('#projectparticipation-equity_type').val(0);
                $('#projectparticipation-amount').val('');
                $('#projectparticipation-interest_rate').val('');
            } else {
                $('.field-projectparticipation-investment_type').attr('style', 'display: block');
                // $('.field-projectparticipation-equity_type').attr('style', 'display: block');
                // $('.field-projectparticipation-amount').attr('style', 'display: block');
                // $('.field-projectparticipation-interest_rate').attr('style', 'display: block');
            }
        });
    
        $('#projectparticipation-investment_type').on('change', function() {
            if($('#projectparticipation-investment_type').val() == "Grant") {
                $('.field-projectparticipation-equity_type').attr('style', 'display: none');
                $('.field-projectparticipation-amount').attr('style', 'display: block');
                $('.field-projectparticipation-interest_rate').attr('style', 'display: none');
                $('#projectparticipation-equity_type').val(0);
                $('#projectparticipation-amount').val('');
                $('#projectparticipation-interest_rate').val('');
            } else {
                $('.field-projectparticipation-equity_type').attr('style', 'display: block');
                //$('.field-projectparticipation-amount').attr('style', 'display: block');
                //$('.field-projectparticipation-interest_rate').attr('style', 'display: block');
            }
        });
    
        $('#projectparticipation-equity_type').on('change', function() {
            if($('#projectparticipation-equity_type').val() == "Interest_Earning") {
                $('.field-projectparticipation-interest_rate').attr('style', 'display: block');
                $('.field-projectparticipation-amount').attr('style', 'display: block');
            } else {
                $('.field-projectparticipation-interest_rate').attr('style', 'display: none');
                $('.field-projectparticipation-amount').attr('style', 'display: block');
                $('#projectparticipation-interest_rate').val('');
            }
        
     
        });
        
        $("#projectparticipation-username").on('blur',function()
        {
            if($(this).val()=="")
            {
                $('#user_ref_id').val($(this).val()); 
            }
        });
        
    });
</script>

