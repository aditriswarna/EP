<style type="text/css">
    .dropdwn-proj-partici{
        width: 170px;
    }
    .notifyDiv{
      background-color: #B5EBE0;  
    }
</style>
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\jui\DatePicker;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Project Participations';
$this->params['breadcrumbs'][] = $this->title;
$dateformat = Yii::getAlias('@phpdatepickerformat');
?>
<div class="project-participation-index">

    <h1 class="box-title"><?php echo Html::encode($this->title) ?></h1>
    
    <div class="participation-border fl-left">
    
    <div style="width: 100%; float: left;" class="searchBox ">
    <div class="col-sm-12 p-left0">
        <?php $form = ActiveForm::begin(); ?>
        <?php //echo $form->field($model, 'project_category_ref_id', ['options' => ['class' => 'col-sm-2 col-xs-12']])->dropDownList($categories, ['prompt' => 'Category']) ?>
        <?php echo $form->field($model, 'participation_type', ['options' => ['class' => 'col-sm-2 col-xs-12 p-left0 prj-fiter']])->dropDownList(array('Invest' => 'Cash', 'Support' => 'Kind'), ['prompt' => 'Participation Type']) ?>
        <?php echo $form->field($model, 'investment_type', ['options' => ['class' => 'col-sm-2 col-xs-12 prj-fiter dropdwn-proj-partici']])->dropDownList(array('Equity' => 'Equity', 'Grant' => 'Grant'), ['prompt' => 'Investment Type']) ?>
        <?php echo $form->field($model, 'equity_type', ['options' => ['class' => 'col-sm-2 col-xs-12 prj-fiter dropdwn-proj-partici']])->dropDownList(array('Principal Protection' => 'Principal Protection', 'Interest Earning' => 'Interest Earning'), ['prompt' => 'Equity Type']) ?>
        <div class="col-sm-2 col-xs-12 custom-calendar prj-fiter">
            <?php echo $form->field($model, 'from_date')->widget(DatePicker::classname(), [
                            'value'  => @$value, 'dateFormat' => $dateformat, 'value' => date('Y-m-d'), 'options' => ['class' => 'col-sm-3 col-xs-12'],
                            'clientOptions' => [
                                'changeMonth' => true,
                                'yearRange' => "2000:2070",
                                'changeYear' => true,
                                'showOn' => 'button',
                                'buttonImage' => 'images/calendar.gif',
                                'buttonImageOnly' => true,
                                'buttonText' => 'Select date',
                                'buttonImage' => Yii::$app->request->BaseUrl.'/images/calendar.gif',
                                'onSelect' => new \yii\web\JsExpression("function(dateStr) {
                                    $('#projectparticipation-to_date').val('');  
                                    var toDate = $(this).datepicker('getDate');
                                    var fromDate = $(this).datepicker('getDate');
                                    fromDate.setDate(toDate.getDate()+1);                                
                                    $('#projectparticipation-to_date').datepicker('option', 'minDate', fromDate); 
                                    }"
                                ),
                            ],
                    ])->textInput(['readonly' => true]);
            ?>
        </div>
        <div class="col-sm-2 col-xs-12 custom-calendar prj-fiter">
            <?php echo $form->field($model, 'to_date')->widget(DatePicker::classname(), [
                            'value'  => @$value, 'dateFormat' => $dateformat, 'value' => date('Y-m-d'), 'options' => ['class' => 'col-sm-3 col-xs-12'],
                            'clientOptions' => [
                                'changeMonth' => true,
                                'yearRange' => "2000:2070",
                                'changeYear' => true,
                                'showOn' => 'button',
                                'buttonImage' => 'images/calendar.gif',
                                'buttonImageOnly' => true,
                                'buttonText' => 'Select date',
                                'buttonImage' => Yii::$app->request->BaseUrl.'/images/calendar.gif',                        
                            ],
                    ])->textInput(['readonly' => true]);
            ?>
        </div>
        <div class="col-sm-2 btn-sbrt prj-fiter1">
       <div class="col-sm-6 col-xs-12 searchBtn">
                    <?php echo Html::submitButton('<i class="icon-magnifier"></i>' , ['class' => 'btn btn-success', 'id' => 'btnSearch']) ?>
                    <input type="hidden" value="<?php echo Yii::$app->request->BaseUrl; ?>//project-participation/index" id="searchUrl">
                </div>
                <div class="col-sm-6 col-xs-12 searchBtn" style="padding:0;">
                    <?php echo Html::submitButton('<i class="fa fa-refresh"></i>' , ['class' => 'btn btn-success res-bnt', 'id' => 'btnReset']) ?>
                </div>
    </div>
        <?php ActiveForm::end(); ?>
       
    </div>
    </div>
    <div class='participation-border fl-left notifyDiv' style="display:none;">You have successfully deleted the participation.</div>    
<?php Pjax::begin(['id' => 'pjax-list', 'timeout' => false, 'enablePushState'=>false, 'clientOptions' => ['container' => 'pjax-container','method' => 'POST']]); ?>
    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'project_participation_id',
            //'project_ref_id',
            //'user_ref_id',
            'username',
            'project_title',
            // 'participation_type',            
            [
                'attribute' => 'participation_type',
                'value' => function ($data) {
                    return $data['participation_type'] == 'Invest' ? 'Cash' : 'Kind';
                }
            ],
            'investment_type',
            //'equity_type',
            [
                'attribute' => 'equity_type',
                'value' => function ($data) {
                    return str_replace('_', ' ', $data['equity_type']);
                }
            ],
            [
                'attribute' => 'amount',
                'value' => function ($data) {
                    return !empty($data['amount']) ? $data['amount'] : '';
                }
            ],
            [
                'attribute' => 'interest_rate',
                'value' => function ($data) {
                    return !empty($data['interest_rate']) ? $data['interest_rate'] : '';
                }
            ],
            // 'created_by',
            'created_date',

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view}{delete}',
                'buttons'=>[                    
                    'view'=>function ($url, $model) {     
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('yii', 'view'),
                           'class'=>'view',
                           'rel'=>'fancybox'
                        ]);                                

                      },
                    'delete'=>function($url, $model) {                        
                        return HTML::a('<span class="glyphicon glyphicon-trash"></span>',$url,[
                            'title' => Yii::t('yii', 'delete'),
                            'aria-label'=>"Delete",
                            'class' => 'ajaxDelete', 
                            'delete-url' => $url, 
                            'pjax-container' => 'pjax-list',
                            'data-confirm'=>'Are you sure you want to delete this participation?',
                        ]);
                    },
                  ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        return Url::toRoute(['view', 'id' => $model['project_participation_id']]);
                    }                    
                    else if ($action === 'delete') {
                        return Url::toRoute(['delete', 'id' => $model['project_participation_id']]);
                    } 
                } 
            ],             
        ],
    ]); ?>
      <?php \yii\widgets\Pjax::end(); ?>

<input type="hidden" value="" id="updateUrl">
<input type="hidden" value="" id="ajaxContainer">
<?php
$this->registerJs(" $(document).on('ready pjax:success', function () {  var deleteUrl; 
  $('.ajaxDelete').on('click', function (e) {
    e.preventDefault();
    deleteUrl     = $(this).attr('delete-url');
    $('#updateUrl').val(deleteUrl);
    var pjaxContainer = $(this).attr('pjax-container');
    $('#ajaxContainer').val(pjaxContainer);
    
    $('#dataConfirmLabel').text($(this).attr('data-confirm'));
    $('#dataConfirmModal').css('display','block');
   
    return false;
 
});
    $(document).on('pjax:timeout', function(event) {
      // Prevent default timeout redirection behavior
      event.preventDefault()
    });
}); 

");
                ?>
</div>
</div>
<div id="dataConfirmModal" class="confirm-box" style="display:none;">
    <h3 id="dataConfirmLabel" >Please Confirm</h3>   
    <div style="text-align:right;margin-top:10px;">
        <input class="dataConfirmCancel btn btn-secondary" onclick="$('#dataConfirmModal').css('display','none');" type="button" value="Cancel">
        <input class="dataConfirmOK btn btn-primary" onclick="updateStatus()" type="button" value="Ok">
    </div>
</div>

<style>
    .searchBox .form-group { margin: 0px;    }
    .help-block { display: none; }
    @media (max-width: 1030px){
        .grid-view {overflow-x: scroll; float: left;}
    }
    @media(max-width: 770px) {
        .col-sm-2.col-xs-12.p-left0.prj-fiter.field-projectparticipation-participation_type.required, .col-sm-2.col-xs-12.prj-fiter.field-projectparticipation-equity_type {margin: 10px auto;}
        .custom-calendar.prj-fiter{ padding-left: 0;}
        .prj-fiter1{
            padding-left: 0px;
            margin-left: -14px;
        }
    }
    @media(max-width: 670px){
        div#pjax-list{ 
            margin: 0 auto;
            float: left;
            width: 100%;
        }
        .grid-view{ float:none;}
        .fl-left { display: block;    float: left !important;}
    }
</style>
<script>
    $(function(){
        $("a[rel=fancybox]").fancybox({type:'ajax'});
        $('.view').on('click',function(e)
        {
            e.preventDefault();
            // $("a[rel=fancybox]").fancybox({type:'ajax'});
        });
        
        $("#projectparticipation-from_date").attr("placeholder", "From Date");
        $("#projectparticipation-to_date").attr("placeholder", "To Date");
        
        $('#btnSearch').on('click', function (e) {
            var searchUrl = $('#searchUrl').val();
            var pjaxContainer = 'pjax-list' ;
            var participationType = $('#projectparticipation-participation_type').val();
            var investmentType = $('#projectparticipation-investment_type').val();
            var equityType = $('#projectparticipation-equity_type').val();
            var from = $('#projectparticipation-from_date').val();
            var to = $('#projectparticipation-to_date').val();
            var pjaxReloadURL = searchUrl+'?pType='+participationType+'&iType='+investmentType+'&eType='+equityType+'&from='+from+'&to='+to;

             $.ajax({
                url:   searchUrl,
                type: 'get',
                data: {'pType': participationType, 'iType': investmentType, 'eType': equityType, 'from': from, 'to': to},
                success: function(data){
                    if(data){
                        //$.pjax.reload({url: pjaxReloadURL, container: '#' + $.trim(pjaxContainer, )});
                        $.pjax.reload({url: pjaxReloadURL, container: '#' + $.trim(pjaxContainer), async:false});
                        return false;
                    }
                },
                error: function (xhr, status, error) {
                   alert('There was an error with your request.' + xhr.responseText);
                 }
             }); 
             return false;
        });
        
        $('#btnReset').on('click', function (e) {
            var searchUrl = $('#searchUrl').val();
            var pjaxContainer = 'pjax-list' ;
            var pjaxReloadURL = searchUrl;

             $.ajax({
                url:   searchUrl,
                type: 'get',
                //data: {'title': projectTitle, 'cat': projectCategory, 'type': projectType, 'status': projectStatus, 'from': from, 'to': to},
                success: function(data){
                    if(data){ 
                        //$.pjax.reload({url: pjaxReloadURL, container: '#' + $.trim(pjaxContainer, )});
                        $('#projectparticipation-participation_type').val('');
                        $('#projectparticipation-investment_type').val('');
                        $('#projectparticipation-equity_type').val('');
                        $('#projectparticipation-from_date').datepicker('setDate', null);
                        $('#projectparticipation-to_date').datepicker('setDate', null);
                        $.pjax.reload({url: pjaxReloadURL, container: '#' + $.trim(pjaxContainer), async:false});
                        return false;
                    }
                },
                error: function (xhr, status, error) {
                   alert('There was an error with your request.' + xhr.responseText);
                 }
             }); 
             return false;
        });
    });
    
    function updateStatus(){
    var deleteUrl = $('#updateUrl').val();
    var pjaxContainer = $('#ajaxContainer').val();

     $.ajax({
     url:   deleteUrl,
     type: 'post',
     success: function(data){        
       if(data){ 
           $('#dataConfirmModal').css('display','none');
           $.pjax.reload({container: '#' + $.trim(pjaxContainer)});
           $('.notifyDiv').slideDown('slow',function () {
               $(this).delay(2000).fadeOut(1000);
           });           
       }
     },
     error: function(xhr, status, error) {
        // alert('There was an error with your request.' + xhr.responseText);
     }
     }); 
     return false;
 }   
</script>
