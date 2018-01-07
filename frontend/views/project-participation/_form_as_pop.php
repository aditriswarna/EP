<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model frontend\models\ProjectParticipation */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .fancybox-skin{ margin:0 auto; padding:0 !important; }
    .fancybox-skin .fancybox-inner{ width:430px !important;}
    .fancybox-inner .project-participation-form.participation-border { margin: 20px auto; padding: 0 20px; float: left; width: 100%;}
    .fancybox-inner select { border-color:#d8d8d8; background: rgba(150, 139, 139, 0.09);}
    .fancybox-inner select:focus { border-color: #ffab13;}
    .prj-popup-btn{color: #9d9d9d;font-family: headerfont;}
    .button-container1 button.prj-popup-btn:hover { background: none;    border-color: #ffa500; color:#ffa500}
    .fancybox-inner .project-participation-form.participation-border  .button-container1{text-align: center;    margin: 25px auto 10px;}
    .button-container1 button.prj-popup-btn:focus {background: #ffa500;border-color: #ffa500; -o-transition: .3s ease; -webkit-transition: .3s ease; -ms-transition: .3s ease; transition: .3s ease;  -moz-transition: .3s ease; color:#fff !important;}
    .fancybox-skin .fancybox-close{ background:url(<?php echo yii::getAlias('@web');?>/themes/custom/css/closeimage.jpg) 0 0 no-repeat;top: 14px; right: 10px;}
    .fancybox-inner .project-participation-form.participation-border input{    border-color:#d8d8d8; background:#fff;}
    .fancybox-inner .project-participation-form.participation-border input:focus {border-color: #ffa500; -o-transition: .3s ease; -webkit-transition: .3s ease; -ms-transition: .3s ease; transition: .3s ease;  -moz-transition: .3s ease;}
	@media (max-width: 770px){
.fancybox-skin .fancybox-inner {    width: 100% !important;}
}
 @media (max-width:400px){
.fancybox-opened {  width:100% !important; padding: 0 10px !important; left:auto !important;}
} 
    /*input#projectparticipation-amount:hover { background: none;    border-color: #ffa500; }*/

</style>
<?php Pjax::begin(); ?>
<div class="project-participation-form participation-border">
    <div class="col-xs-12 col-sm-12 equit-fullwd"> 
        <?php $form = ActiveForm::begin(['id' => 'create_form']); ?>

    <!--<?= "Project Name: <h4>".$project->project_title.'</h4>'; ?>-->
        <?= $form->field($model, 'project_ref_id')->hiddenInput(['value' => $project->project_id])->label(false) ?>

        <?= $form->field($model, 'user_ref_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false) ?>

        <?= $form->field($model, 'participation_type')->dropDownList([ 'Invest' => 'Cash', 'Support' => 'Kind', ], ['prompt'=>'--Select--','class'=>'participation_type form-control required']) ?>
        <div class="participation_type"></div>
        <?= $form->field($model, 'investment_type')->dropDownList([ 'Equity' => 'Equity', 'Grant' => 'Grant', ], ['prompt'=>'--Select--','class'=> 'form-control required']) ?>

        <?= $form->field($model, 'equity_type')->dropDownList([ 'Principal_Protection' => 'Principal Protection', 'Interest_Earning' => 'Interest Earning', ], ['prompt'=>'--Select--','class'=> 'form-control required']) ?>

        <?= $form->field($model, 'amount')->textInput( array ('class' => ' form-control  required number')) ?>

        <?= $form->field($model, 'interest_rate')->textInput(array ('class' => ' form-control  required number')) ?>

        <div class='limit_cross' style='display:none'>
            <span>Pledged amount is exceeding the budget amount, you  still want to proceed</span><span><input type='checkbox'  name="check_to_go" class='check_to_go required' style='display:none'></span></div>
        <?php //echo $form->field($model, 'created_by')->textInput()  ?>

        <?php //echo $form->field($model, 'created_date')->textInput()  ?>

        <div class="button-container1">
            <?= Html::submitButton($model->isNewRecord ? 'Participate' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success prj-popup-btn' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
        <?php Pjax::end(); ?>

    </div>
</div>

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>-->

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
    
        $('.btn-success').on('click',function(e)
        {
            e.preventDefault();
            var t_amount=$('.total_p_amt').text();
            var  bud_amount=$('.total_b_amt').text();
            if($('#projectparticipation-amount').val() >0)
            {
                if((parseInt($('#projectparticipation-amount').val()))+ parseInt(t_amount) > bud_amount)
                {
                    $('.limit_cross').css('display','block'); 
                    $('.check_to_go').css('display','block');
                }
                else
                    {
                       $('.limit_cross').css('display','none'); 
                       $('.check_to_go').css('display','none');  
                        
                    }
                  
            }
             
            if($("#create_form").valid())
            {
              $(".prj-popup-btn").text("Please wait")
               $(".prj-popup-btn").attr('disabled','disabled');
                $.ajax({
                    url: $('#create_form').attr('action'),
                    type: "post",
                    data: $('#create_form').serialize(),
                    success: function(html){   
                        if($('#projectparticipation-participation_type').val()=="Support")
                        {
                            
                            var tt_amount=parseInt(t_amount);
                            var  tbud_amount=parseInt(bud_amount);
                            var already_participated= ($('.already_partipated').val()!='') ? parseInt($('.already_partipated').val()):0;
                            var new_amount=tt_amount-already_participated;
                            $('.total_p_amt').text(new_amount);
                            var per_bar=(((new_amount)/(tbud_amount))*100);
                            $('.progress-bar').css('width',Math.round(per_bar)+'%');
                            $('.progress-bar').text(Math.round(per_bar)+'%'); 
                            $('.list-focus .r_total_amount').text(new_amount);
                
                        }
                        if($('#projectparticipation-amount').val()!="")
                        {
                        
                            var tt_amount=parseInt(t_amount);
                            var  tbud_amount=parseInt(bud_amount);
                            var part_text= $('#projectparticipation-amount').val();
                            var already_participated= ($('.already_partipated').val()!='') ? parseInt($('.already_partipated').val()):0;
                            var new_amount=tt_amount+parseInt(part_text)-already_participated;
                            $('.total_p_amt').text(new_amount);
                            var per_bar=(((new_amount)/(tbud_amount))*100);
                            $('.progress-bar').css('width',Math.round(per_bar)+'%');
                            $('.progress-bar').text(Math.round(per_bar)+'%');  
                            $('.list-focus .r_total_amount').text(new_amount);
                        }
                        $.fancybox.close();
                        $.notify(html, "success");
                    }
                });                      
            }
        });

    });
    
</script>