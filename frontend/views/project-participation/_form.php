<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\ProjectParticipation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="project-participation-form participation-border ">
    <div class="col-xs-12 col-sm-6 equit-fullwd"> 
    <?php $form = ActiveForm::begin(); ?>

    <!--<?= "Project Name: <h4>".$project->project_title.'</h4>'; ?>-->
    <?= $form->field($model, 'project_ref_id')->hiddenInput(['value' => $project->project_id])->label(false) ?>

    <?= $form->field($model, 'user_ref_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false) ?>

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
    });
</script>